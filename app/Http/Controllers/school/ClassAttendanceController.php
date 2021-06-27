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
use Illuminate\Support\Facades\Auth;

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
          'taken_by'=>auth::user()->id,
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
        ->join('users','class_attendance.taken_by','=','users.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->join('setings as atten','class_attendance.attendance_for','=','atten.s_d')
        ->where('taken_on',$request->date)
        ->select('class_attendance.date','std_class.name as class','class_stream.name as stream',
      'taken_on',db::raw("CONCAT(users.first_name,' ',COALESCE(users.middle_name,''),' ' ,users.last_name)as taken_by"),'atten.key_name as attendance_for','add_stream.id as class_id')
        ->groupBy('class_attendance.date')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $ClassAttendance]);
    }
    public function attendanceView(request $request)
    {
        $classDetail = ClassAttendance::leftjoin('add_stream','class_attendance.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('setings as atten','class_attendance.attendance_for','=','atten.s_d')
        ->where('class_attendance.class',$request->class)
        ->where('taken_on',$request->date)
        ->select('std_class.name as class','class_stream.name as stream',
      'taken_on','atten.key_name as attendance_for')
        ->groupBy('taken_on')
        ->first();
        $present = ClassAttendance::
        where('class_attendance.class',$request->class)
        ->where('taken_on',$request->date)
        ->where('present',1)
        ->count();
        $absent = ClassAttendance::
        where('class_attendance.class',$request->class)
        ->where('taken_on',$request->date)
        ->where('present',0)
        ->count();
        $studentDetail = ClassAttendance::join('admission','class_attendance.student','=','admission.admission_id')
        ->leftjoin('add_stream','class_attendance.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->where('class_attendance.class',$request->class)
        ->where('taken_on',$request->date)
        ->select(
        db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),'remark',
      db::raw('present=1 as present'),db::raw('present=0 as absent'))->get();
        return response()->json(['status' => 'Success', 'classDetail' => $classDetail,
                                                         'studentDetail'=>$studentDetail,
                                                         'total_present'=>$present,
                                                       'total_absent'=>$absent]);
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
    public function ClassStudentTeacher(request $request)
    {
      $student=Admission::join('add_stream as u','admission.class','=','u.id')
                          ->join('staff','u.teacher','=','staff.employee_id')
                          ->join('class_stream','u.stream','=','stream_id')
                          ->join('std_class','u.class','=','std_class.class_id')
                          ->select(db::raw("COUNT('admission.class')as total_student"),
                          db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),' ',staff.last_name)as teacher"),
                          'std_class.name as class','class_stream.name as stream','u.id as class_id')
                          ->orderBy('admission.class')
                          ->get();
                          return response()->json([
                            'message'=>'success',
                            'data'=>$student
                          ]);
    }
    public function StudentByclass(request $request)
    {
      $atten=StudentClass::where('student_class.class',$request->class_id)
      ->join('admission','student_class.student','=','admission.admission_id')
      ->select(db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as student"),
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
      ->join('users','class_attendance.taken_by','=','users.id')
      ->where('class',$request->class_id)
      ->select('class_attendance.date','std_class.name as class','class_stream.name as stream','taken_on',db::raw("CONCAT(users.first_name,' ',COALESCE(users.middle_name,''),' ' ,users.last_name)as taken_by"))->get();
      return response()->json([
        'message'=>'success',
        'data'=>$atten
      ]);
    }
    public function AttendanceListByDate(request $request)
    {
      $date=$request->date;
      $dateD=date("Y-m-d");
      if($date>=date("Y-m-d"))
      {
        $atten=ClassAttendance::leftjoin('admission','class_attendance.student','=','admission.admission_id')
        
        ->leftjoin('add_stream','class_attendance.class','=','add_stream.id')
        ->where('add_stream.teacher',$request->staff)
        ->where('class_attendance.date','!=',$request->date)
      ->select('admission_id as student',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as name"),
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
      ->select('admission_id as student',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as name"),
      'present')->get();
      return response()->json([
        'message'=>'success',
        'data'=>$atten
      ]);
      }

      
    }
    public function studentMyAttendance(request $request)
    {
     
      $present = ClassAttendance::where('student',$request->student)
      ->where('present',1)
      ->select('date')
      ->get(); 
      $absent = ClassAttendance::where('student',$request->student)
      ->where('present',0)
      ->select('date')
      ->get(); 
      
  return response()->json(['present'=>$present,'absent'=>$absent ,'message'=>'success']);
    }
}
