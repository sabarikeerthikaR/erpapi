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
    'year.key_name as year')->get();
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
        ->select('admission_id',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student")
        )->get();
        $subject=Subject::join('exam','subjects.term','=','exam.term')
        ->where('class',$request->class)
        ->select('name as subject','subject_id','sub_units')
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
        ->select('sub_units')
        ->first();
        foreach($data as $k => $g)
        {
            if($subjects->sub_units==339)
            {
                $exam1[$k]['student'] = $g['student'];
                $exam1[$k]['mark_one'] = $g['mark_one'];
                $exam1[$k]['mark_two'] = $g['mark_two'];
                $exam1[$k]['total_mark'] = $g['mark_one'] + $g['mark_two'];
                $exam1[$k]['exam'] = $request->exam_id;
                $exam1[$k]['subject'] = $request->subject;
                $exam1[$k]['grading_system']=$request->grading_system;
                 $exam1[$k]['convert_percentage']=$request->convert_percentage;
            }
            else
            {
                $exam1[$k]['student'] = $g['student'];
                $exam1[$k]['total_mark'] =$g['total_mark'];
                $exam1[$k]['exam'] = $request->exam_id;
                $exam1[$k]['subject'] = $request->subject;
                $exam1[$k]['grading_system']=$request->grading_system;
                 $exam1[$k]['convert_percentage']=$request->convert_percentage;
            }
                    
            
            
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

    // public function ExamMarkView(request $request)
    // {

    // }
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
        $term=Terms::select('terms.*',DB::raw("DATE_FORMAT(terms.from_year, '%Y-%m') as from_date"),
        DB::raw("DATE_FORMAT(terms.to_year, '%Y-%m') as to_date"))
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
        $timetable=Exam::where('exam.term',$request->term)
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
       
        $timetable=Exam::where('exam.term',$request->term)
        ->leftjoin('exam_timetable','exam.exam_id','=','exam_timetable.exam')
        ->leftjoin('subjects','exam_timetable.subject','=','subjects.subject_id')
        ->where('exam_timetable.class',$request->class)
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
       
        $teacher=AddStream::where('add_stream.teacher',$request->staff)
        ->leftjoin('exam_mark','add_stream.id','=','exam_mark.class')
        ->leftjoin('exam','exam_mark.exam','=','exam.exam_id')
        ->leftjoin('grading_system','exam_mark.grading_system','=','grading_system.grading_systm_id')
        ->leftjoin('gradings','grading_system.grading_systm_id','=','gradings.grading_system_id')
        ->leftjoin('grade','gradings.grade','=','grade.gradings_id')
        ->leftjoin('subjects','exam_mark.subject','=','subjects.subject_id')
        ->leftjoin('admission','exam_mark.student','=','admission.admission_id')
       // ->whereBetween('exam_mark.total_mark',['max_mark','min_mark'])
       ->where('exam_mark.total_mark','>=','gradings.min_mark')
       ->where('exam_mark.total_mark','<=','gradings.max_mark')
        ->select('exam.title as exam','subjects.name as subject',
        db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
        'total_mark','grading_system.title as grading_system','grade.title as grade',
        'grade.remarks')
        ->groupBy('exam_mark.id')
        ->get(); 
        return response()->json([
            'message'  => 'success',
            'data'=>$teacher
          ]);
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
    
    // public function ExamResults(request $request)
    // {
        
    // }
}
