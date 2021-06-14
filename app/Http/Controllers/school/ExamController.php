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
use Illuminate\Database\Migrations\Migration;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\User;
use App\Models\Admission;
use App\Models\ExamTimetable;
use App\Models\Subject;
use App\Models\Settings;
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
        $Exam = Exam::join('setings as set','exam.term','=','set.s_d')
        ->join('setings as year','exam.year','=','year.s_d')
        ->select('exam_id','title','weight','start_date','end_date','set.key_name as term',
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
        ->select('admission_id',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
        )->get();
        $subject=Subject::join('exam','subjects.term','=','exam.term')
        ->where('class',$request->class)
        ->select('name as subject','subject_id')
        ->groupBy('subject_id')
        ->get();
        if(!empty($studentsub))
        {
            return response()->json([
                'message'=>'success',
                'student'=>$studentsub,
                'subject'=>$subject,
                'exam_id'=>$exam
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
        foreach($data as $g)
        {
            if($subjects->sub_units=339)
            {
                    $exam = new ExamMark(array(
                    'student'=>$g['student'],
                    'mark_one'=>$g['mark_one'],  
                    'mark_two'  =>$g['mark_two'],
                    'total_mark'  =>$g['mark_one'] + $g['mark_two'],
                    'exam'=>$request->exam_id,
                    'subject'=>$request->subject,
                    'grading_system'=>$request->grading_system,
                    ));
            }
            else
            {
                $exam = new ExamMark(array(
                    'student'=>$g['student'],
                    'total_mark'  =>$g['total_mark'],
                    'exam'=>$request->exam_id,
                    'subject'=>$request->subject,
                    'grading_system'=>$request->grading_system,
                ));
            }
          if(!$exam->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'request saved successfully',
              'data'  => $data,
              'exam'=>$request->exam_id,
              'subject '=>$request->subject,
              'grading_system'=>$request->grading_system,
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
                    'exam'=>$request->exam,
                    'subject'=>$request->subject,
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
        $term=Settings::where('group_name','term')
        ->get();
        return response()->json([
            'message'  => 'success',
            'errors'=>$term
          ]);

    }
    public function viewExamTimetableStudent(request $request)
    {
        $id=Auth::user()->id;
        $class=User::where('users.id',$id)
        ->join('admission','users.parent','=','admission.parent')
        ->join('add_stream','admission.class','=','add_stream.id')
        ->select('admission.class')->first();
        $timetable=Exam::where('exam.term',$request->term)
        ->join('exam_timetable','exam.exam_id','=','exam_timetable.exam')
        ->join('subjects','exam_timetable.subject','=','subjects.subject_id')
        ->where('exam_timetable.class',$class->class)
        ->select('exam_id','title','exam.term','exam_timetable.class','subject_id','total_mark','minimum_mark',
        'date','start_time','end_time','subjects.name as subject')
        ->get();
        return response()->json([
            'message'  => 'success',
            'errors'=>$timetable
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
