<?php

namespace App\Http\Controllers\school;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Models\AddStream;
use Illuminate\Database\Migrations\Migration;
use App\Models\Exam;
use App\Models\Settings;
use App\Models\ExamMark;
use App\Models\User;
use App\Models\Admission;
use App\Models\ExamTimetable;
use App\Models\Gradings;
use App\Models\Subject;
use App\Models\Terms;
use App\Models\SubjectClass;
use App\Helper;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
   public function store(Request $Exam)
    {
      $validator =  Validator::make($Exam->all(), [
           'title' => ['required'],
            'term' => ['required'],
            'year' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Exam=Exam::create([

        'title'  =>$Exam->title ,
        'term'  =>$Exam->term ,
        'year'  =>$Exam->year ,
        'weight'  =>$Exam->weight ,
        'start_date'  =>$Exam->start_date ,
        'end_date'  =>$Exam->end_date ,
        'description'  =>$Exam->description ,
         ]);
         $settings= new Settings([
            'group_name'=>'Exam',
            'key_name'=>$Exam->title,
            'key_value'=>$Exam->exam_id,
            ]);
            $settings->save();
        if($Exam->save()){
                  return response()->json([
                 'message'  => 'Exam saved successfully',
                 'data'  => $Exam 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Exam = Exam::find($request->exam_id);
             if(!empty($Exam)){
                    return response()->json([
                    'data'  => $Exam      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
   }
   public function index()
    {
        $Exam = Exam::join('terms','exam.term','=','terms.term_id')
        ->join('setings as year','exam.year','=','year.s_d')
        ->select('exam_id','title','weight','start_date','end_date','terms.name as term',
    'year.key_name as year','description')->get();
        return response()->json(['status' => 'Success', 'data' => $Exam]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'title' => ['required'],
        'term' => ['required'],
        'year' => ['required'],
        'start_date' => ['required'],
        'end_date' => ['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Exam = Exam::find($request->exam_id);
        $Exam->title = $request->title ;
        $Exam->term = $request->term ;
        $Exam->year = $request->year ;
        $Exam->weight = $request->weight ;
        $Exam->start_date = $request->start_date ;
        $Exam->end_date = $request->end_date ;
        $Exam->description = $request->description ;
        $settings=Settings::where('group_name','=','Exam')->where('key_value',$request->exam_id)->first();
        $settings->key_name= $request->title;
        $settings->save();
        if($Exam->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Exam
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Exam = Exam::find($request->exam_id);
        $settings=Settings::where('group_name','=','Exam')->where('key_value',$request->exam_id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($Exam))

                {
                  if($Exam->delete()){
                  return response()->json([
                  'message'  => 'successfully deleted'
                   ]);
               }else {
                  return response()->json([
                  'message'  => 'failed'
                ]);
               }
           }else
           {
           return response()->json([
                 'message'  => 'No data found in this id'  
                 ]);
            }
    }
    public function markEntry(Request $request)
    {
        $exam=$request->exam_id;
        $studentsub=Admission::where('class',$request->class)
        ->select('admission_id',db::raw("CONCAT(first_name,' ',coalesce(middle_name,''),' ',last_name)as student")
        )->get();
        $subject=SubjectClass::where('subject_class.class',$request->class)
        ->leftjoin('subjects','subject_class.subject','=','subjects.subject_id')
        ->leftjoin('exam','subject_class.term','=','exam.term')
        ->select('subjects.name as subject','subject_id',
            db::raw('(CASE when sub_units = "339" then "1"
                       else "0"  end) as sub_units'))
        ->groupBy('subject_id')
        ->get();
       
        if(!empty($studentsub))
        {
            return response()->json([
                'message'=>'success',
                'student'=>$studentsub,
                'subject'=>$subject,
                'exam_id'=>$exam,
                'class'=>$request->class
            ]);
        }
    }
    public function markentryPost(request $request)
    {
        $data=$request->data;
        $errors=[];
        $subjects=Subject::where('subject_id',$request->subject)
        ->where('sub_units',339)
        ->count();
        foreach($data as $k => $g)
        {
            if($subjects > 0)
            {
                $exam1[$k]['student'] = $g['student'];
                $exam1[$k]['mark_one'] = $g['mark_one'];
                $exam1[$k]['mark_two'] = $g['mark_two'];
                $exam1[$k]['total_mark'] = $g['mark_one'] + $g['mark_two'];
                $exam1[$k]['exam'] = $request->exam_id;
                $exam1[$k]['subject'] = $request->subject;
                $exam1[$k]['grading_system']=$request->grading_system;
                 $exam1[$k]['convert_percentage']=$request->convert_percentage;
                 $exam1[$k]['class']=$request->class;
            }
            else
            {
                $exam1[$k]['student'] = $g['student'];
                $exam1[$k]['total_mark'] =$g['total_mark'];
                $exam1[$k]['exam'] = $request->exam_id;
                $exam1[$k]['subject'] = $request->subject;
                $exam1[$k]['grading_system']=$request->grading_system;
                 $exam1[$k]['convert_percentage']=$request->convert_percentage;
                 $exam1[$k]['class']=$request->class;
            }
            $id=auth::user()->id;
            //activity
            sendActivities($id, 'student','mark', 'you have created new exam mark',0);
        
            
            
        }
        
        $exam=new ExamMark();   
            if($exam->insert($exam1)) 
            {
              
                  return response()->json([
                  'message'  => 'Exam mark saved successfully',
                  'data'=>$exam1
                 
                      ]);
                  }
                  else 
                  {
                      return response()->json([
                       'message'  => 'failed',
                       'errors'=>$errors
                     ]);
                   }
            }

    public function ExamMarkView(request $request)
    {

        $ExamMark=ExamMark::where('exam_mark.class',$request->class)
        ->where('exam_mark.exam',$request->exam)
        ->where('subject',$request->subject)
       ->leftjoin('admission','exam_mark.student','=','admission.admission_id')
        ->select(db::raw("concat(first_name,' ',coalesce(middle_name,''),' ',last_name)as student"), 'total_mark','exam_mark.id')
        ->get();
        if(!empty($ExamMark)){
            return response()->json([
            'data'  => $ExamMark          
            ]);
        }else
        {
          return response()->json([
         'message'  => 'No data found in this id'  
          ]);
         }



    }
    public function examTimetable(request $request)
    {
        $data=$request->data;
        $errors=[];
       
        foreach($data as $g)
        {
           
                    $timetable = new ExamTimetable(array(
                    'total_mark'=>$g['total_mark'],
                    'minimum_mark'=>$g['minimum_mark'],  
                    'date'  =>$g['date'],
                    'start_time'  =>$g['start_time'],
                    'end_time'  =>$g['end_time'],
                    'subject'  =>$g['subject'],
                    'exam'=>$request->exam,
                    'class'=>$request->class,
                    ));
            
          if(!$timetable->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'request saved successfully',
              'data'  => $data,
              'exam'=>$request->exam,
              'subject'=>$request->subject,
              'class'=>$request->class,
                  ]);
              }
              else 
              {
                  return response()->json([
                   'message'  => 'failed',
                   'errors'=>$errors
                 ]);
               }
        
    }
    public function termForExam()
    {
        $term=Exam::select('exam_id','title','start_date as from_date','end_date as to_date')
        ->get();
        return response()->json([
            'message'  => 'success',
            'data'=>$term
          ]);

    }

    public function viewExamTimetableStudent(request $request)
    {
        $id=Auth::user()->id;
        $class=User::where('users.id',$id)
        ->leftjoin('admission','users.admission_id','=','admission.admission_id')
        ->leftjoin('add_stream','admission.class','=','add_stream.id')
        ->select('admission.class')->first();
        $timetable=Exam::where('exam_id',$request->exam)
        ->leftjoin('exam_timetable','exam.exam_id','=','exam_timetable.exam')
        ->leftjoin('subjects','exam_timetable.subject','=','subjects.subject_id')
        ->where('exam_timetable.class',$class->class)
        ->select('exam_id','title','exam.term','exam_timetable.class','subject_id','total_mark','minimum_mark',
        'date','start_time','end_time','subjects.name as subject','exam_timetable.id')
        ->get();
        return response()->json([
            'message'  => 'success',
            'data'=>$timetable
          ]);

    }
    public function viewExamTimetableStaff(request $request)
    {
       
        $timetable=Exam::leftjoin('exam_timetable','exam.exam_id','=','exam_timetable.exam')
        ->leftjoin('subjects','exam_timetable.subject','=','subjects.subject_id')
        ->where('exam_timetable.class',$request->class)
        ->where('exam_timetable.exam',$request->exam)
        ->select('exam_id','title','exam.term','exam_timetable.class','subject_id','total_mark','minimum_mark',
        'date','start_time','end_time','subjects.name as subject','exam_timetable.id')
        ->get();
        return response()->json([
            'message'  => 'success',
            'data'=>$timetable
          ]);

    }
    public function viewExamTimetableAdmin(request $request)
    {
       
        $timetable=Exam::
        leftjoin('exam_timetable','exam.exam_id','=','exam_timetable.exam')
        ->leftjoin('subjects','exam_timetable.subject','=','subjects.subject_id')
        ->select('exam_id','title','exam.term','exam_timetable.class','subject_id','total_mark','minimum_mark',
        'date','start_time','end_time','subjects.name as subject','exam_timetable.id')
        ->get();
        return response()->json([
            'message'  => 'success',
            'data'=>$timetable
          ]);

    }
    public function destroyExamTimetable(request $request)
    {
        $exam = ExamTimetable::find($request->id);
        if(!empty($exam))

                {
                  if($exam->delete()){
                  return response()->json([
                  'message'  => 'successfully deleted'
                   ]);
               }else {
                  return response()->json([
                  'message'  => 'failed'
                ]);
               }
           }else
           {
           return response()->json([
                 'message'  => 'No data found in this id'  
                 ]);
            }
    }
    public function examReportStaffView(request $request)
    {
       $grade=AddStream::where('add_stream.teacher',$request->staff)
        ->leftjoin('exam_mark','add_stream.id','=','exam_mark.class')
        ->where('exam_mark.exam',$request->exam)
       ->leftjoin('grading_system','exam_mark.grading_system','=','grading_system.grading_systm_id')
        ->leftjoin('gradings','grading_system.grading_systm_id','=','gradings.grading_system_id')
        ->leftjoin('grade','gradings.grade','=','grade.gradings_id')
        ->select('grade.title as grade','min_mark','max_mark',
        'grade.remarks')
        ->groupBy('gradings_id')
        ->get();

        $studentName=AddStream::where('add_stream.teacher',$request->staff)
        ->leftjoin('exam_mark','add_stream.id','=','exam_mark.class')
        ->where('exam_mark.exam',$request->exam)
         ->leftjoin('admission','exam_mark.student','=','admission.admission_id')
         ->select('admission_id',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as name"))
         ->groupBy('exam_mark.student')
         ->get();


      //$mark=[];

        $mark=AddStream::where('add_stream.teacher',$request->staff)
        ->leftjoin('exam_mark','add_stream.id','=','exam_mark.class')
        //->where('exam_mark.student',$g['student'])
        ->where('exam_mark.exam',$request->exam)
        ->leftjoin('exam','exam_mark.exam','=','exam.exam_id')
        ->leftjoin('subjects','exam_mark.subject','=','subjects.subject_id')
        ->select('subjects.name as subject','student',
        'total_mark')
        ->get()->toArray(); 
    
    
     $marks=[];
      
     foreach($studentName as  $k => $g)

     {

          $data=array_filter($mark, function($m) use ($g){

            return array($m['student']==$g['admission_id']);

            //return is_array($datad)? array_values($datad): array();   
           
            //return (is_array($m) && $m['student'] == $g['admission_id']);
           //print_r($m);
         });

           $marks[]=array("name"=>$g["name"],"data"=>$data);

     }
        return response()->json([
            'message'  => 'success',
            // 'student'=> $data,
           'mark'=>$marks,
            'grade'=>$grade
          ]);
    }

     public function ExamResults(request $request)
    {
         $grade=ExamMark::where('exam_mark.student',$request->student)
         ->where('exam_mark.exam',$request->exam)
       ->leftjoin('grading_system','exam_mark.grading_system','=','grading_system.grading_systm_id')
        ->leftjoin('gradings','grading_system.grading_systm_id','=','gradings.grading_system_id')
        ->leftjoin('grade','gradings.grade','=','grade.gradings_id')
        ->select('grade.title as grade','min_mark','max_mark',
        'grade.remarks')
        ->groupBy('gradings_id')
        ->get();

        $mark=ExamMark::where('exam_mark.student',$request->student)
         ->where('exam_mark.exam',$request->exam)
        ->leftjoin('exam','exam_mark.exam','=','exam.exam_id')
        ->leftjoin('subjects','exam_mark.subject','=','subjects.subject_id')
        ->leftjoin('admission','exam_mark.student','=','admission.admission_id')
        ->select('subjects.name as subject',
        'total_mark')
        //->groupBy('exam_mark.id')
        ->get(); 

        return response()->json([
            'message'  => 'success',
            'data'=>$mark,
            'grade'=>$grade
          ]);
    }
    public function examList(request $request)
    {
        $mark=ExamMark::where('exam_mark.class',$request->class)
                        ->where('exam_mark.subject',$request->subject)
                        ->where('exam_mark.exam',$request->exam)
                        ->leftjoin('admission','exam_mark.student','=','admission.admission_id')
                        ->leftjoin('subjects','exam_mark.subject','=','subjects.subject_id')
                        ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
                                 'total_mark','exam_mark.id',
                             db::raw('(CASE when sub_units = "339" then "1"
                       else "0"  end) as sub_units'))
                        ->get();
                        return response()->json([
            'message'  => 'success',
            'data'=>$mark
          ]);


    }
    public function MarkSelectshow(request $request)
   {

    $subUnit=ExamMark::where('id',$request->id)
    ->leftjoin('subjects','exam_mark.subject','=','subjects.subject_id')
    ->where('sub_units',339)
    ->count(); 
    if($subUnit > 0)
    {
         $ExamMark=ExamMark::where('id',$request->id)
    ->leftjoin('admission','exam_mark.student','=','admission.admission_id')
     ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as name"),
                             'mark_one','mark_two','total_mark','exam_mark.id','exam_mark.student','exam_mark.subject')
    ->first(); 

    }else
    {
        $ExamMark=ExamMark::where('id',$request->id)
    ->leftjoin('admission','exam_mark.student','=','admission.admission_id')
     ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as name"),
                              'total_mark','exam_mark.id','exam_mark.student',
                                 'exam_mark.subject')
    ->first(); 

    }
          if(!empty($ExamMark)){
                    return response()->json([
                    'data'  => $ExamMark          
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
   }
     public function markEdit(request $request)
    {
         $subUnit=ExamMark::where('exam_mark.id',$request->id)
    ->join('subjects','exam_mark.subject','=','subjects.subject_id')
        ->where('subjects.sub_units', 339)
        ->groupBy('exam_mark.id')
        ->count(); 
        if($subUnit > 0)
            {
        $mark=ExamMark::find($request->id);
         $mark->mark_one = $request->mark_one ;
         $mark->mark_two = $request->mark_two ;
         $mark->total_mark = $request->mark_one +  $request->mark_two;
     }
     else
     {
        $mark=ExamMark::find($request->id);
         $mark->total_mark = $request->total_mark ;

     }
     $id=auth::user()->id;
        //activity
        sendActivities($id, 'student','mark', 'you have updated exam mark',0);

        if($mark->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $mark
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }


    }
    // public function ExamCertificate(request $request)
    // {
        
    // }
    // public function ExamCertificateview(request $request)
    // {
        
    // }
    // public function ExamCertificateedit(request $request)
    // {
        
    // }
    // public function ExamCertificatedelete(request $request)
    // {
        
    // }
    // public function ExamCertificateselect(request $request)
    // {
        
    // }
    
   
}
