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

class LeaveRequestController extends Controller
{
    public function sendRequestStudent(request $request)
    {
        $id=Auth::user()->id;
        $Teacher=User::where('users.id',$id)
        ->join('admission','users.parent','=','admission.parent')
        ->join('add_stream','admission.class','=','add_stream.id')
        ->select('add_stream.teacher')->first();
        $leaveRequest= LeaveRequest::create([

            'from_date'=>$request->from_date,
            'to_date'=>$request->to_date,
            'reason'=>$request->reason,
            'request_by'=>Auth::user()->id,
            'request_to'=>$Teacher->teacher,
            'date'=>date('Y-m-d')
        ]);
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
        $id=Auth::user()->id;

        $getRequest=LeaveRequest::where('request_by',$id)
        ->select('date','from_date','to_date','reason',
        db::raw('(CASE when accept = "542" then "Accepted"
                       when accept = "543" then "Rejected"
                       else "pending"  end) as request'),'created_at','updated_at'
        )->get();
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
}