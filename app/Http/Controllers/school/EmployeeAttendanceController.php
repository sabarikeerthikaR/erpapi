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
use App\Models\EmployeeAttendance;
use App\Models\Staff;
use Illuminate\Support\Carbon;
use App\Models\Holidays;

class EmployeeAttendanceController extends Controller
{
     public function store(Request $EmployeeAttendance)
    {
        
        $attendance=$EmployeeAttendance->attendance;
        $errors=[];
        foreach($attendance as $g)
        {
          
        
        $EmployeeAttendance = new EmployeeAttendance(array(
          'date'=>$EmployeeAttendance->date,
          'employee'=>$g['employee'],    
          'present'=>$g['present'],
         ));
          if(!$EmployeeAttendance->save())
          {
            $errors[]=$g;
          }
      } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'EmployeeAttendance saved successfully',
              'date'     => $EmployeeAttendance->date,
              'data'=>$attendance

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
public function show(request $request)
   {

    $EmployeeAttendance = EmployeeAttendance::where('id',$request->id)
    ->leftjoin('staff','employee_attendance.employee','=','staff.employee_id')
        ->select('employee_attendance.date','present','employee_attendance.id','employee_id',
                 DB::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as employee"))
        ->first();
             if(!empty($EmployeeAttendance)){
                    return response()->json([
                    'data'  => $EmployeeAttendance      
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
        $EmployeeAttendance = EmployeeAttendance::leftjoin('staff','employee_attendance.employee','=','staff.employee_id')
        ->select('employee_attendance.date','present','id',
                 DB::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as employee"))
        ->get();
        return response()->json(['status' => 'Success', 'data' => $EmployeeAttendance]);
    }


public function update(Request $request)

   {
        $attendance=$request->attendance;
         $EmployeeAttendance = EmployeeAttendance::find($request->id);
        $EmployeeAttendance->employee = $request->employee;
        $EmployeeAttendance->present = $request->present;
        if($EmployeeAttendance->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $EmployeeAttendance
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $EmployeeAttendance = EmployeeAttendance::find($request->id);
        if(!empty($EmployeeAttendance))

                {
                  if($EmployeeAttendance->delete()){
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
   public function selectEmployee(request $request)
   {
   
    $emp=db::table('staff')->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),'employee_id','employee_type')->get(); 
     if(!empty($emp)){
                    return response()->json([
                    'data'  => $emp      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found'  
                  ]);
                 }
   }
   public function EmployeeMyAttendance(request $request)
   {
          
       $present = EmployeeAttendance::where('employee',$request->employee)
      ->where('present',1)
      ->select('date')
      ->get(); 
      $absent = EmployeeAttendance::where('employee',$request->employee)
      ->where('present',0)
      ->select('date')
      ->get(); 
      
  return response()->json(['present'=>$present,'absent'=>$absent ,'message'=>'success']);
    
   }
}
