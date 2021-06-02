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
use App\Models\Sms;
use App\Models\SmsList;

use App\Models\User_group;
use App\Models\Admission;
use Illuminate\Support\Facades\Auth;
use App\Models\Std_class;
class SmsController extends Controller
{
    public function store(Request $request)
    {
        $sender = $request->input('sender');
        if (Auth::user()->type == 2) {
            SmsList::create([
                
            ]);
        } else if (Auth::user()->type == 3) {
            SmsList::create([
                'student_id' =>$request->input('receiver'),
                'teacher_id' =>  Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'last_message' => $request->input('message')
            ]);
            
        }
        else if (Auth::user()->type == 4){
            SmsList::create([
                'student_id' => Auth::user()->id,
                'teacher_id' => $request->input('receiver'),
                'updated_by' => Auth::user()->id,
                'last_message' => $request->input('message')
            ]);

        }
        $Sms=Sms::create([
            'sender' => Auth::user()->id,
            'receiver' => $request->input('receiver'),
            'message' => $request->input('message')
       
         ]);
        if($Sms->save()){
                  return response()->json([
                 'message'  => 'Sms saved successfully',
                 'data'  => $Sms 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Sms = Sms::find($request->id);
             if(!empty($Sms)){
                    return response()->json([
                    'data'  => $Sms      
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
      
        $Sms = Sms::join('user_group','sms.send_to','=','user_group.group_id')->select(
    'message','sms.created_at as date_time', 'send_by','sms.id as id','user_group.name as send_to','group_id')->get();
    $records= Sms::count();
        return response()->json(['status' => 'Success', 'data' => $Sms,
        'total_recods'=>$records]);
    }


public function update(Request $request)

   {
    
    $Sms = Sms::find($request->id);
         $Sms->send_to= $request->send_to;
        $Sms->message= $request->message;
       

        if($Sms->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Sms
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Sms = Sms::find($request->id );
        if(!empty($Sms))

                {
                  if($Sms->delete()){
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
    public function listStudent(request $request)
    {
       $std1=Admission::join('std_class','admission.class','=','std_class.class_id')->where('class',$request->class)->select
       (db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),'std_class.name as class',
       'admission_no','admission_id')->get();
       return response()->json([
       'message'=>'success',
       'student'=>$std1
       ]);
    }
    public function customSms(request $request)
    {
        $data=$request->data;
        $errors=[];
        foreach($data as $g)
        {
          
        $Sms = new Sms(array(
          'student'=>$g['student'],
          'message'=>$request->message,
          'send_by'=>'admin',
         ));
          if(!$Sms->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'Sms send successfully',
              'message'=>$request->message,
          'data'=>$data,
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
    public function SmsRandomNo(request $request)
    {
        $Sms=Sms::create([
        'number'=>$request->number,
        'message'=>$request->message
       ]);
        if($Sms->save()){
                  return response()->json([
                 'message'  => 'Sms send successfully',
                 'data'  => $Sms 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
        }
          public function MessageFeedback()
          {

              // there is 3 types of receivers they were "send_to"
              //                                           "student"
              //                                            "number"
             // select if send_to AND student = NULL THEN number
             //        if student AND number = NULL THEN send_to
             //                                     ELSE student
         $sms=Sms::leftjoin('user_group','sms.send_to','=','user_group.group_id')
         ->leftjoin('admission','sms.student','=','admission.admission_id')
         ->select('message as title','send_by as from','created_at','user_group.name as sent_to','number',
         db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"))->get();
         return response()->json([
         'message'=>'success',
         'data'=>$sms
         ]);
          }
    public function leaveRequest(request $request)
    {
        
    }
}
