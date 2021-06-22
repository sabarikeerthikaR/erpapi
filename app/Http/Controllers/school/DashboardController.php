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
use App\Models\Add_event;
use App\Models\Add_item;
use App\Models\AddStream;
use App\Models\Admission;
use App\Models\ClassAttendance;
use App\Models\StudentTimetable;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\Assignment;
use App\Models\Fee_payment;
use App\Models\Item_stock;
use App\Models\OnlineRegistration;
use App\Models\Settings;
use App\Models\Sms;
use App\Models\Std_class;
use App\Models\ExamTimetable;
use App\Models\LeaveRequest;
use App\Models\Message;
use App\Models\TeacherTimetable;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function dashboard()
     {
        $id=Auth::user()->admission_id; 
        $class=Admission::where('admission_id',$id)->pluck('class');
    	$exam=ExamTimetable::join('admission','exam_timetable.class','=','admission.class')
        ->whereDate('exam_timetable.date', '>=', date('Y-m-d'))
        ->select('exam_timetable.class','exam_timetable.date')
       ->where('admission.admission_id',$id)
        ->count(); 
        $event=Add_event::whereDate('end_date','>=',date("Y-m-d"))
        ->count();
        $timetable=StudentTimetable::join('admission','student_timetable.class','=','admission.class')
        ->where('admission.admission_id',$id)
        ->join('subjects','student_timetable.subject','=','subjects.subject_id')
        ->join('setings as day','student_timetable.day','=','day.s_d')
        ->join('add_stream','student_timetable.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->select(
            "student_timetable.id",
            "student_timetable.start_time",
            "student_timetable.end_time",
            "day.key_name as day",
            "subjects.name as subject",
            DB::raw("CONCAT(std_class.name,' ',class_stream.name) as class"),
      
        )->get();
        $emp=db::table('staff')->join('add_stream','staff.employee_id','=','add_stream.teacher')
        ->where('add_stream.id',$class)
        ->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),
        'email','employee_id','phone','passport_photo')->first(); 
        $Assignment=Assignment::where('class',$class)
                                ->whereDate('end_date','>=',date("Y-m-d"))            
                                ->count();
                                $studentAttendancePresent=ClassAttendance::
                                where('class',$class)
                                ->where('student',$id)
                                ->where('present',1)
                                ->whereMonth('created_at','=',date('m'))
                               ->count(); 
                                $studentAttendanceAbsent=ClassAttendance::
                                where('class',$class)
                                ->where('student',$id)
                                ->where('present',0)
                                ->whereMonth('created_at','=',date('m'))
                                ->count(); 
      //  $duefee
           $date = Carbon::now()->subDays(7);
       $messageList=Message::where('receiver',$id)
    ->join('staff','message.sender','=','staff.employee_id')
    ->where('message.created_at','>=',$date)
    ->select('message',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as name"),
    DB::raw("DATE_FORMAT(message.created_at, '%Y-%m-%d') as date"))
     ->get();
    	return response()->json([
                 'message'  => ' success',
                 'Assignment'  => $Assignment,
                 'exam'  => $exam,
                 'event'  => $event,
                 'timetable'  => $timetable,
                 'classTeacher'  => $emp,
                 'myAttendancePresentCount'=>$studentAttendancePresent,
                 'myAttendanceAbsentCount'=>$studentAttendanceAbsent,
                 'messageList'=>$messageList
                 ]);
        
    }

    // public function getweeklyattendance()
    // {
    //     $dates = collect();

    //     foreach (range(-6, 0) AS $i) {
    //         $date = Carbon::now()->addDays($i)->format('Y-m-d');
    //         $dates->put($date, 0);
    //     }

    //     $attendance = ClassAttendance::where('created_at', '>=', $dates->keys()->first())
    //         ->groupBy('date')
    //         ->orderBy('date')
    //         ->get([
    //             DB::raw('DATE( created_at ) as date'),
    //             DB::raw("COUNT('present=1') as present"),
    //             DB::raw("COUNT('present=0') as absent")
    //         ])->pluck('count', 'date');

    //     $dates = $dates->merge($attendance);

    //     $res = [];

    //     foreach ($dates as $key => $date) {
    //         $data = ["date" => $key, "count" => $date];
    //         $res[] = $data;
    //     }

    //     return response()->json(apiResponseHandler($res, 'success'));
    // }

    // public function getMonthlyAttendance()
    // {
    //     $months = collect();

    //     foreach (range(-11, 0) AS $i) {
    //         $month = Carbon::now()->addMonths($i)->format('F');
    //         $months->put($month, 0);
    //     }

    //     $firstDay = $month = Carbon::now()->addMonths(-12)->format('Y-m-d');

    //     $attendance = ClassAttendance::where('created_at', '>=', $firstDay)
    //         ->groupBy('date')
    //         ->orderBy('created_at')
    //         ->get([
    //             DB::raw('DATE_FORMAT(created_at, "%M") as month'),
    //         ])->pluck('month');
    //     //$present= ClassAttendance::where('present',0)->count(); 
       
       
    //    // $absent= ClassAttendance::where('present',1)->count();
       
    //     $months = $months->merge($attendance);

    //     $res = [];

    //     foreach ($months as $key => $t_month) {
    //         $data = ["month" => $key, "present" => $t_month, "absent" => $t_month];
    //         $res[] = $data;
    //     }

    //     return response()->json(apiResponseHandler($res, 'success'));
    // }

    public function admindashboard()
    {
        $student=Admission::count();
        $staff=Staff::count();
        $sock=Item_stock::count();
        $registerd=OnlineRegistration::join('std_class','online_registration.admission_for','=','std_class.class_id')->join('setings','online_registration.gender','=','setings.s_d')->select('online_reg_id','date','setings.key_name as gender','std_class.name as admission_for',
        db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as name"))->get();
        $feePayment=Fee_payment::join('admission','fee_payment.student','=','admission.admission_id')->select('amount','fee_payment.date','created_by',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"))->get();
       $message=Sms::get();
       return response()->json(apiResponseHandler(['student'=>$student,
                                                   'staff'=>$staff,
                                                   'stock'=>$sock,
                                                   'registerd'=>$registerd, 
                                                   'fee'=>$feePayment,
                                               'message'=>$message
                                            ], 'success'));
    }
    public function activitiesAct()
    
    {
        $act=db::table('logs')->get();
        return response()->json([
         'message'=>'success',
         'data'=>$act   
        ]);
    }
  
    public function teacherDashBoard(request $request)
    {
        $id=Auth::user()->staff_id; 
        $subjectclass=TeacherTimetable::join('staff','teacher_timetable.staff','=','staff.employee_id')
                                ->where('employee_id',$id)->pluck('teacher_timetable.class');
        $class=AddStream::where('teacher',$id)->pluck('id'); 
    	$exam=ExamTimetable::join('teacher_timetable','exam_timetable.subject','=','teacher_timetable.subject')
        ->whereDate('exam_timetable.date', '>=', date('Y-m-d'))
        ->select('exam_timetable.subject','exam_timetable.date')
       ->where('teacher_timetable.staff',$id)
        ->count(); 
       $message=Message::where('receiver',$id)
       ->whereDate('created_at','=',date('Y-m-d'))
       ->count();
    //    $Assignment=Assignment::where('class',$class)
    //    ->whereDate('end_date','>=',date("Y-m-d"))            
    //    ->count();
    $event=Add_event::whereDate('end_date','>=',date("Y-m-d"))
    ->count();
    $leave=LeaveRequest::where('request_to',$id)
       ->whereDate('created_at','=',date('Y-m-d'))
       ->count();
    $studentAttendancePresent=ClassAttendance::
    where('class',$class)
    ->where('present',1)
    ->whereMonth('created_at','=',date('m'))
   ->count(); 
    $studentAttendanceAbsent=ClassAttendance::
    where('class',$class)
    ->where('present',0)
    ->whereMonth('created_at','=',date('m'))
    ->count(); 
    $teacherTimetable=TeacherTimetable::where('teacher_timetable.staff',$id)
    ->join('subjects','teacher_timetable.subject','=','subjects.subject_id')
    ->join('setings as day','teacher_timetable.day','=','day.s_d')
    ->join('add_stream','teacher_timetable.class','=','add_stream.id')
    ->join('std_class','add_stream.class','=','std_class.class_id')
    ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
    ->select(
        "teacher_timetable.id",
        "teacher_timetable.start_time",
        "teacher_timetable.end_time",
        "day.key_name as day",
        "subjects.name as subject",
        DB::raw("CONCAT(std_class.name,' ',class_stream.name) as class"),
  
    )->get();
    $date = Carbon::now()->subDays(7);
    $messageList=Message::where('receiver',$id)
    ->join('admission','message.sender','=','admission.admission_id')
    ->where('message.created_at','>=',$date)
    ->select('message',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as name"),
    DB::raw("DATE_FORMAT(message.created_at, '%Y-%m-%d') as date"))
     ->get();
    $examList=Exam::
    whereDate('end_date', '>=', date('Y-m-d'))
        ->select('title','start_date','end_date')
        ->get(); 


        return response()->json([
            'message'  => 'success',
            'upcoming_exam'  => $exam,
            'today_message'  => $message,
            'student_today_leave_req'  => $leave,
            'upcoming_event'  => $event,
            'month_attendance_present'  => $studentAttendancePresent,
            'month_attendance_absent'  => $studentAttendanceAbsent,
            'timeTable'=>$teacherTimetable,
            'messageList'=>$messageList,
            'ExamList'=>$examList
            ]);
    }
} 
