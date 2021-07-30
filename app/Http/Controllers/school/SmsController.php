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
use App\Models\Message;
use App\Models\User;
use App\Models\Subject;
use App\Models\Admission;
use Illuminate\Support\Facades\Auth;
use App\Models\AddStream;
use App\Models\Device;
use App\Models\Notification;
use \Illuminate\Contracts\Support\Renderable;

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

/// STUDENT TEACHER MESSAGE


public function selectStudentForMessage(request $request)
{
    $student= AddStream::join('admission','add_stream.id','=','admission.class')
                          ->where('add_stream.id',$request->class)
                          ->select('add_stream.id as class_id',
                          'admission_id',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name) as student"))
                          ->get();
                          return response()->json([
                          'message'=>'success',
                          'data'=>$student
                          ]);
}
public function selectStaffForMessage(request $request)
{
    $id=Auth::user()->admission_id;
    $classTeacher= User::join('admission','users.admission_id','=','admission.admission_id')
                          ->join('add_stream','admission.class','=','add_stream.id')
                          ->join('staff','add_stream.teacher','=','staff.employee_id')
                          ->where('admission.admission_id',$id)
                          ->select('add_stream.id as class_id',
                          'employee_id',db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),' ',staff.last_name) as name"))
                          ->first();
    $subjectTeacher=Subject::leftjoin('subject_class','subjects.subject_id','=','subject_class.subject')
    ->leftjoin('admission','subject_class.class','=','admission.class')
    ->where('admission.admission_id',$id)
    ->leftjoin('teacher_timetable','admission.class','=','teacher_timetable.class')
    ->leftjoin('staff','teacher_timetable.staff','=','staff.employee_id')
     ->select('subjects.name as subject','subject_id','employee_id',
     db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),' ',staff.last_name)as name"))
     ->groupBy('teacher_timetable.staff')
     ->get();

    // $merge=$classTeacher->merge($subjectTeacher);
                          return response()->json([
                          'message'=>'success',
                          'class_teacher'=>$classTeacher,
                          'subject_teacher'=>$subjectTeacher
                          ]);
}

    public function MessageTeacher(request $request)
    {
        $errors=[];

       
        $sent_to=$request->sent_to; 
        //teacher
          if(Auth::user()->user_role==3)
          {
                    $sent_by=Auth::user()->id;
                    foreach($sent_to as $g)
                    {
                    $message= new Message(array(
                
                        'sender'=>$sent_by,
                        'receiver'=>$g['admission_id'],
                        'message'=>$request->message,
                    ));
                            if(!$message->save())
                            {
                              $errors[]=$g;
                            }
                    }
          }
// admin
          elseif(Auth::user()->user_role==2)
          {
                 $sent_by=Auth::user()->id;
                    $sent_to=$request->sent_to;
                            if($sent_to==4){
                                             $users=User::where('user_role',$sent_to)->select('admission_id')->get();
                                              foreach($users as $g)
                                                    {
                                                    $message= new Message(array(
                                                
                                                        'sender'=>$sent_by,
                                                        'admission_id'=>$g['admission_id'],
                                                        'message'=>$request->message,
                                                    ));
                                                            if(!$message->save())
                                                            {
                                                              $errors[]=$g;
                                                            }
                                                    }
                                    
                                                     }
                                                     else
                                                     {
                                                         $users=User::where('user_role',$sent_to)->select('id')->get();
                                                         foreach($users as $g)
                                                            {
                                                            $message= new Message(array(
                                                        
                                                                'sender'=>$sent_by,
                                                                'receiver'=>$g['id'],
                                                                'message'=>$request->message,
                                                            ));
                                                                    if(!$message->save())
                                                                    {
                                                                      $errors[]=$g;
                                                                    }
                                                            }
                                                     }
                                 
               
          }

          else

            //student
          {
                $sent_by=Auth::user()->admission_id;

                    $message= new Message([
            
                        'sender'=>$sent_by,
                        'receiver'=>$sent_to,
                        'message'=>$request->message,
                    ]);
          }


        //   $id=Auth::user()->id;

        //   $firebase = (new Device())->where('user_id', '=', $sent_to)->select('user_id', 'device_id')->get();

        //     foreach ($firebase as $key => $item) {
        //         $notification = [
        //             'title' => 'You Have A New Message',
        //             'body' => $request->message,
        //             'type' => CHAT_MESSAGE,
        //             'data' => [
        //                 'type' => CHAT_MESSAGE,
        //                 'message' => $request->message
        //             ]
        //         ];
        //       sendFirebaseNotification($item->device_id, $notification,$item->device);
        //   }
  
        //   $notify = new Notification();
        //   $notify->action_performer  = Auth::user()->id;
        //   $notify->action_to  = $sent_to;
        //   $notify->action_title="message";
        //   $notify->description = 'You have a new message.';
        //   $notify->read_status = 0;
        //   $notify->save();
  
         
           if($message->save()){
               return response()->json([
              'message'  => 'message send successfully',
              'sender'=>$sent_by,
                    'receiver'=>$sent_to,
                    'message_data'=>$request->message,
               ]);
           }else {
               return response()->json([
              'message'  => 'failed',
              'error'  => $errors
              ]);
       }
       
    
        
    }
    public function incommingMessage(request $request)
    {
         if(Auth::user()->user_role==3)
         {
            //staff
            $receiver=Auth::user()->staff_id;
            $message=Message::where('receiver',$receiver)
                           ->leftjoin('admission','message.sender','=','admission.admission_id')
                           ->select('message.created_at','message',
                           db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name) as name"),'replay',
                           'message.id as message_id') 
                           ->orderBy('message.id', 'desc')
                           ->get();
         }
         else
         {
            //student
           $receiver=Auth::user()->admission_id;
           $message=Message::where('receiver',$receiver)
                           ->leftjoin('users','message.sender','=','users.id')
                           ->select(db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name) as name"),
                                    'message.created_at','message',
                           'message.id as message_id','replay') 
                           ->orderBy('message.id', 'desc')
                           ->get();
         }
         if(!empty($message)){
            return response()->json([
           'message'  => 'success',
           'data'  => $message 
            ]);
        }else {
            return response()->json([
           'message'  => 'no data found',
        
           ]);}
         

    }     
    public function outGoingMessage(request $request)
    {
         if(Auth::user()->user_role==3)
         {
            $sender=Auth::user()->id;
            $message=Message::where('sender',$sender)
                           ->leftjoin('admission','message.receiver','=','admission.admission_id')
                           ->select('message.created_at','message',
                           db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name) as name"),
                           'message.id as message_id','replay') 
                           ->orderBy('message.id', 'desc')
                           ->get();
         }
         else
         {
           $sender=Auth::user()->admission_id;
           $message=Message::where('sender',$sender)
                           ->leftjoin('users','message.receiver','=','users.staff_id')
                           ->select('message.created_at','message',
                           db::raw("CONCAT(users.first_name,' ',COALESCE(users.middle_name,''),' ',users.last_name) as name"),
                           'message.id as message_id','replay') 
                           ->orderBy('message.id', 'desc')
                           ->get();
         }
         if(!empty($message)){
            return response()->json([
           'message'  => 'success',
           'data'  => $message 
            ]);
        }else {
            return response()->json([
           'message'  => 'no data found',
        
           ]);}
         

    }     
    public function messageReplay(request $request)
    {
        $message=Message::find($request->message_id);
        $message->replay=$request->replay;

        if($message->save()){
            return response()->json([
                 'message'  => 'sent successfully',
                 
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
    public function staffMessageNotify(request $request)
    {
        $message=Message::where('receiver',$request->staff)->get();

    }
}
