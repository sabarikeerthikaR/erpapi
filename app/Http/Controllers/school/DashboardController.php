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
use App\Models\Admission;
use App\Models\ClassAttendance;
use App\Models\StudentTimetable;
use App\Models\Staff;
use App\Models\Assignment;
use App\Models\Fee_payment;
use App\Models\Item_stock;
use App\Models\Online_registration;
use App\Models\Settings;
use App\Models\Sms;
use App\Models\Std_class;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function dashboard()
    {
    	$exam=Exam::count();
        $event=Add_event::count();
        $timetable=StudentTimetable::select()->get();
        $emp=db::table('staff')->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),'email','employee_id')->join('group_staff','staff.employee_type','=','group_staff.employee_type')->where('staff.employee_type','=',1)->get(); 
        $Assignment=Assignment::count();
      //  $duefee
       
        if(!empty($exam)){
    	return response()->json([
                 'message'  => ' success',
                 'Assignment'  => $Assignment,
                 'exam'  => $exam,
                 'event'  => $event,
                 'timetable'  => $timetable,
                 'staff'  => $emp,
                 ]);
        }
        else{
            return response()->json([
                'message' => 'not found'
            ]);
        }
    }

    public function getweeklyattendance()
    {
        $dates = collect();

        foreach (range(-6, 0) AS $i) {
            $date = Carbon::now()->addDays($i)->format('Y-m-d');
            $dates->put($date, 0);
        }

        $attendance = ClassAttendance::where('created_at', '>=', $dates->keys()->first())
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE( created_at ) as date'),
                DB::raw("COUNT('present=1') as present"),
                DB::raw("COUNT('present=0') as absent")
            ])->pluck('count', 'date');

        $dates = $dates->merge($attendance);

        $res = [];

        foreach ($dates as $key => $date) {
            $data = ["date" => $key, "count" => $date];
            $res[] = $data;
        }

        return response()->json(apiResponseHandler($res, 'success'));
    }

    public function getMonthlyAttendance()
    {
        $months = collect();

        foreach (range(-11, 0) AS $i) {
            $month = Carbon::now()->addMonths($i)->format('F');
            $months->put($month, 0);
        }

        $firstDay = $month = Carbon::now()->addMonths(-12)->format('Y-m-d');

        $attendance = ClassAttendance::where('created_at', '>=', $firstDay)
            ->groupBy('date')
            ->orderBy('created_at')
            ->get([
                DB::raw('DATE_FORMAT(created_at, "%M") as month'),
            ])->pluck('month');
        //$present= ClassAttendance::where('present',0)->count(); 
       
       
       // $absent= ClassAttendance::where('present',1)->count();
       
        $months = $months->merge($attendance);

        $res = [];

        foreach ($months as $key => $t_month) {
            $data = ["month" => $key, "present" => $t_month, "absent" => $t_month];
            $res[] = $data;
        }

        return response()->json(apiResponseHandler($res, 'success'));
    }

    public function admindashboard()
    {
        $student=Admission::count();
        $staff=Staff::count();
        $sock=Item_stock::count();
        $registerd=Online_registration::join('std_class','online_registration.admission_for','=','std_class.class_id')->join('setings','online_registration.gender','=','setings.s_d')->select('online_reg_id','date','setings.key_name as gender','std_class.name as admission_for',
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
        
    }
} 
