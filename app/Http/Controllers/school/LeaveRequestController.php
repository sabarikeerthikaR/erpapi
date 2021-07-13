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
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Helper;

class LeaveRequestController extends Controller
{
    public function sendRequestStudent(request $request)
     {
       $id=$request->request_id;
       
       if(Auth::user()->user_role==4)
       {
       
         $Teacher=User::where('users.admission_id',$id)
         ->join('admission','users.admission_id','=','admission.admission_id')
         ->join('add_stream','admission.class','=','add_stream.id')
         ->select('add_stream.teacher')->first();
       $request_to=$Teacher->teacher;
       }
       else
       {
         $adminData=User::where("user_role",2)->first();
         $request_to=$adminData->id;
       }
         $leaveRequest= LeaveRequest::create([
 
             'from_date'=>$request->from_date,
             'to_date'=>$request->to_date,
             'reason'=>$request->reason,
             'request_by'=>$id,
           'request_to'=>$request_to,
             'date'=>date('Y-m-d')
         ]);

         $id=auth::user()->id;
         //activity
         sendActivities($id,$request_to,'leaveRequest', 'requested for leave',0);


        if($leaveRequest->save()){
            return response()->json([
           'message'  => 'leaveRequest saved successfully',
           'data'  => $leaveRequest 
            ]);
        }else {
            return response()->json([
           'message'  => 'failed'
           ]);
    }
    }
    public function ShowLeaveRequestStudent(request $request)
    {
       $id=$request->id;
       $user=Auth::user();
        $getRequest=LeaveRequest::where('request_by',$id)
        ->whereMonth('created_at', '=', date('m'))
        ->where(function($query) use($user){
           if($user->user_role==4)
           $query->join('admission as u','users.admission_id','=','u.admission_id');
           else
            $query->join('staff as u','users.staff_id','=','u.employee_id');   
         })->select('leave_request.date','from_date','to_date','leave_request.reason','leave_request.id',
       db::raw('(CASE when accept = "1" then "Accepted"
                      when accept = "2" then "Rejected"
                       else "pending"  end) as request'),'leave_request.created_at','leave_request.updated_at'
        )->orderBy('leave_request.id', 'desc')
         ->get();

        if(!empty($getRequest)){
            return response()->json([
            'data'  => $getRequest      
            ]);
        }else
        {
          return response()->json([
         'message'  => 'No data found'  
          ]);
         }
    }
    public function AcceptLeaveRequest(request $request)
    {
       $id=$request->id;
    
            if(Auth::user()->user_role==2)
            {
                        $getRequest=LeaveRequest::where('request_to',$id)
                        ->join('staff as u','leave_request.request_by','=','u.employee_id')   
                        ->select('leave_request.date','from_date','to_date','leave_request.reason',
                        'leave_request.id as leave_request_id','employee_id',
                    db::raw("CONCAT(u.first_name,' ',COALESCE(u.middle_name,''),' ',u.last_name) as full_name"),
                    db::raw('(CASE when accept = "1" then "Accepted"
                                    when accept = "2" then "Rejected"
                                    else "pending"  end) as request'),'leave_request.created_at','leave_request.updated_at'
                      )->orderBy('leave_request.id', 'desc')
                        ->get();
            }
        else{
                  $getRequest=LeaveRequest::where('request_to',$id)   
                  ->join('admission as u','leave_request.request_by','=','u.admission_id')
                  ->select('leave_request.date','from_date','to_date','leave_request.reason',
                  'leave_request.id as leave_request_id','admission_id',
              db::raw("CONCAT(u.first_name,' ',COALESCE(u.middle_name,''),' ',u.last_name) as full_name"),
              db::raw('(CASE when accept = "1" then "Accepted"
                              when accept = "2" then "Rejected"
                              else "pending"  end) as request'),'leave_request.created_at','leave_request.updated_at'
                )->orderBy('leave_request.id', 'desc')
                  ->get();

           }
     

        if(!empty($getRequest)){
            return response()->json([
            'data'  => $getRequest      
            ]);
        }else
        {
          return response()->json([
         'message'  => 'No data found'  
          ]);
         }
    }
    public function studentLeaveAdminView(request $request)
    {
        $leave=LeaveRequest::join('admission','leave_request.request_by','=','admission.admission_id')
        ->where('admission.class',$request->class)
        ->select('leave_request.date','from_date','to_date','leave_request.reason',
                  'leave_request.id as leave_request_id','admission_id',
              db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name) as full_name"),
              db::raw('(CASE when accept = "1" then "Accepted"
                              when accept = "2" then "Rejected"
                              else "pending"  end) as request'),'leave_request.created_at','leave_request.updated_at'
                )->orderBy('leave_request.id', 'desc')
        ->get();
        if(!empty($leave)){
            return response()->json([
            'data'  => $leave      
            ]);
        }else
        {
          return response()->json([
         'message'  => 'No data found'  
          ]);
         }
    }
    public function AcceptReject(request $request)
    {
        
        $leaveReq=LeaveRequest::find($request->leaveRequest_id);
        $leaveReq->accept=$request->acceptReject;

        $id=auth::user()->id;
        //activity
        sendActivities($id,$request_to,'leaveRequest', 'leave request is accepted',0);

        if($leaveReq->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
}