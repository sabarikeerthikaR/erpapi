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
use App\Models\Admission;
use Illuminate\Database\Migrations\Migration;
use App\Models\ClassAttendance;
use App\Models\StudentClass;
use App\Models\AddStream;
use App\Models\Std_class;
use App\Models\Class_stream;

class ClassAttendanceController extends Controller
{
 public function store(Request $ClassAttendance)
    {
         $attendance=$ClassAttendance->attendance;
         $errors=[];
        foreach($attendance as $g)
        {
          
          if ($ClassAttendance->date=='') 
          {
           return response()->json(apiResponseHandler([],'The date field is required',400), 400);
          }
        
        $ClassAttendance = new ClassAttendance(array(
          'student'=>$g['student'],
          'present'=>$g['present'],
          'remark' =>$g['remark'],
          'date'   =>$ClassAttendance->date,
          'class'  =>$ClassAttendance->class,
          'attendance_for'=>$ClassAttendance->attendance_for,
          'taken_by'=>'admin',
          'taken_on'=>date("Y-m-d"),
         )); 
          if(!$ClassAttendance->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'ClassAttendance saved successfully',
              'attendance'=>$attendance,
              'date'=>$ClassAttendance->date,
              'class'=>$ClassAttendance->class,
              'attendance_for'=>$ClassAttendance->attendance_for,
                  ]);
              }
              else 
               {
                  return response()->json([
                   'message'  => 'failed',
                   'errors'=>$errors,
                 ]);
               }
    }
 
public function show(request $request)
   {
    $ClassAttendance = ClassAttendance::find($request->id);
      
             if(!empty($ClassAttendance)){
                    return response()->json([
                    'data'  => $ClassAttendance,      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
   }
   public function index(request $request)
    {
        $ClassAttendance = ClassAttendance::join('admission','class_attendance.student','=','admission.admission_id')
        ->join('add_stream','class_attendance.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->where('taken_on',$request->date)
        ->select('std_class.name as class','class_stream.name as stream',
        db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),'present','remark',
      'taken_on','taken_by','attendance_for','class_attendance.id','add_stream.id as class','admission_id')->get();
        return response()->json(['status' => 'Success', 'data' => $ClassAttendance]);
    }


public function update(Request $request)

   {

    $ClassAttendance = ClassAttendance::find($request->id);
        $ClassAttendance->student = $request->student ;
        $ClassAttendance->class = $request->class ;
         $ClassAttendance->attendance_for = $request->attendance_for ;
        $ClassAttendance->present = $request->present;
         $ClassAttendance->remark = $request->remark;
         $ClassAttendance->date = $request->date ;
        if($ClassAttendance->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $ClassAttendance
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $ClassAttendance = ClassAttendance::find($request->id);
        if(!empty($ClassAttendance))

                {
                  if($ClassAttendance->delete()){
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
    public function StudentByclass(request $request)
    {
      $atten=StudentClass::where('student_class.class',$request->class_id)
      ->join('admission','student_class.student','=','admission.admission_id')
      ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
      'admission_id','student_class.class')->get();
      return response()->json([
        'message'=>'success',
        'data'=>$atten
      ]);
    }
    public function classAttendanceView(request $request)
    {
      $atten=ClassAttendance::join('add_stream','class_attendance.class','=','add_stream.id')
      ->join('std_class','add_stream.class','=','std_class.class_id')
      ->join('class_stream','add_stream.class','=','class_stream.stream_id')
      ->where('class',$request->class_id)
      ->select('date','std_class.name as class','class_stream.name as stream','taken_on','taken_by')->get();
      return response()->json([
        'message'=>'success',
        'data'=>$atten
      ]);
    }
    public function AttendanceListByDate(request $request)
    {
      $date=$request->date;
      $dateD=date("Y-m-d");
      if($date<=date("Y-m-d"))
      {
        $atten=ClassAttendance::leftjoin('admission','class_attendance.student','=','admission.admission_id')
        
        ->leftjoin('add_stream','class_attendance.class','=','add_stream.id')
        ->where('add_stream.teacher',$request->staff)
        ->where($dateD,$request->date)
      ->select('admission_id as student',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as name"),
      'present')->get();
      return response()->json([
        'message'=>'success',
        'data'=>$atten
      ]);
        }else{
        $atten=ClassAttendance::leftjoin('admission','class_attendance.student','=','admission.admission_id')
        
        ->leftjoin('add_stream','class_attendance.class','=','add_stream.id')
        ->where('add_stream.teacher',$request->staff)
        ->where('class_attendance.date',$request->date)
      ->select('admission_id as student',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as name"),
      'present')->get();
      return response()->json([
        'message'=>'success',
        'data'=>$atten
      ]);
      }

      
    }
    public function studentAttenCalenderView(request $request)
    {
     
      
    }
}
