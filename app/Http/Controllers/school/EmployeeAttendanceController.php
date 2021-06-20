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

class EmployeeAttendanceController extends Controller
{
     public function store(Request $EmployeeAttendance)
    {
        
        $attendance=$EmployeeAttendance->attendance;
        $errors=[];
        foreach($attendance as $g)
        {
          
        
        $EmployeeAttendance = new EmployeeAttendance(array(
          'date'=>$g['date'],
          'employee'=>$g['employee'],    
          'time_in'=>$g['time_in'],
           'time_out'=>$g['time_out'],
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
              'data'     => $EmployeeAttendance
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

    $EmployeeAttendance = EmployeeAttendance::find($request->id);
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
        $EmployeeAttendance = EmployeeAttendance::join('staff','employee_attendance.employee','=','staff.employee_id')->select('employee_attendance.date','time_in','time_out','id',DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as employee"))->get();
        return response()->json(['status' => 'Success', 'data' => $EmployeeAttendance]);
    }


public function update(Request $request)

   {
        $attendance=$request->attendance;
        $errors=[];
        foreach($attendance as $g)
        {
         
         $EmployeeAttendance = EmployeeAttendance::find($request->id);
        $EmployeeAttendance['date']=$request['date'];
        $EmployeeAttendance['employee']=$request['employee'];  
        $EmployeeAttendance['time_in']=$request['time_in'];
         $EmployeeAttendance['time_out']=$request['time_out'];
         $EmployeeAttendance->save();
         $dateRoom = new EmployeeAttendance(array(
          'date'=>$g['date'],
          'employee'=>$g['employee'],    
          'time_in'=>$g['time_in'],
           'time_out'=>$g['time_out'],
         ));
       
          if(!$dateRoom->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'EmployeeAttendance updated successfully',
              'data'  =>$dateRoom,
              'updated'=>$EmployeeAttendance
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
    $p=$request->all();
        $id=$p['employee_type'];
        DB::enableQueryLog();
    $emp=db::table('staff')->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),'employee_id')->where('employee_type','=',$id)->get(); 
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
          
        $attendance = EmployeeAttendance::where('employee',$request->staff)
            ->select('date')
            ->get(); 
            
        return response()->json(apiResponseHandler($attendance, 'success'));

     
   }
}
