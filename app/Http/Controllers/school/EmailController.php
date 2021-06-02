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
use App\Models\Email;
use App\Models\User_group;

class EmailController extends Controller
{
    public function store(Request $Email)
    {
      $validator =  Validator::make($Email->all(), [
            'send_to'=> ['required'],
            
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $date=date('d-m-Y');
        $Email=Email::create([
          'send_to'  =>$Email->send_to,
        'subject'  =>$Email->subject,
        'cc'  =>$Email->cc,
        'file'  =>$Email->file,
        'description'  =>$Email->description,
        'date'  =>$date,
       
         ]);
        if($Email->save()){
                  return response()->json([
                 'message'  => 'Email saved successfully',
                 'data'  => $Email 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Email = Email::find($request->id);
             if(!empty($Email)){
                    return response()->json([
                    'data'  => $Email      
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
        $Email = Email::join('user_group','email.send_to','=','user_group.group_id')->select('subject','cc','file','email.description','date','user_group.name as sent_to','email.id')->where('status',null)->get();
        return response()->json(['status' => 'Success', 'data' => $Email]);
    }
    public function trash()
    {
        $Email = Email::join('user_group','email.send_to','=','user_group.group_id')->select('subject','cc','file','email.description','date','user_group.name as sent_to','email.id')->where('status',1)->get();
        return response()->json(['status' => 'Success', 'data' => $Email]);
    }

public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
          'send_to'=> ['required'],
            
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Email = Email::find($request->id);
         $Email->send_to= $request->send_to;
        $Email->subject= $request->subject;
         $Email->cc= $request->cc;
        $Email->file= $request->file;
        $Email->description= $request->description;
       

        if($Email->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Email
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Email = Email::find($request->id );
         $Email->status= 1;
        if(!empty($Email))

                {
                  if($Email->save()){
                  return response()->json([
                  'message'  => 'successfully removed'
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
    public function emailInbox(request $request)
    {
        
    }
}
