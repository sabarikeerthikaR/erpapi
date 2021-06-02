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
use App\Models\AddStream;
use App\Models\Admission;
use App\Models\Subject;

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
        foreach($data as $g)
        {
            $subject=Subject::where('subject_id',$request->subject)
            ->select('sub_units')
            ->first();

        if($subject->sub_units=339)
        {
        $exam = new ExamMark(array(
          'student'=>$g['student'],
          'mark_one'=>$g['mark_one'],  
          'mark_two'  =>$g['mark_two'],
          'total_mark'  =>$g['mark_one'] + $g['mark_two'],
          'exam'=>$request->exam_id,
          'subject '=>$request->subject,
          'grading_system'=>$request->grading_system,
         ));
        }else
        {
            $exam = new ExamMark(array(
                'student'=>$g['student'],
                'total_mark'  =>$g['total_mark'],
                'exam'=>$request->exam_id,
                'subject '=>$request->subject,
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
    public function ExamMarkView(request $request)
    {

    }
    public function ExamCertificate(request $request)
    {
        
    }
    public function ExamCertificateview(request $request)
    {
        
    }
    public function ExamCertificateedit(request $request)
    {
        
    }
    public function ExamCertificatedelete(request $request)
    {
        
    }
    public function ExamCertificateselect(request $request)
    {
        
    }
    public function examTimetable(request $request)
    {
        
    }
    public function ExamResults(request $request)
    {
        
    }
}
