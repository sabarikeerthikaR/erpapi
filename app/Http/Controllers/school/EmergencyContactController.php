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
use App\Models\Emergency_contact;
use App\Models\Admission;
use App\Models\Suspend;

class EmergencyContactController extends Controller
{
   public function store(Request $Emergency_contact)
    {
      $validator =  Validator::make($Emergency_contact->all(), [
            'first_name_e'       => ['required', 'string'],
            'relation_e'    => ['required', 'string'],
            'phone_e'  => ['required', 'numeric', 'digits:10'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Emergency_contact=Emergency_contact::create([
        'admission_id'  =>$Emergency_contact->admission_id,
        'first_name_e'  =>$Emergency_contact->first_name_e,
        'relation_e'          =>$Emergency_contact->relation_e,
        'phone_e'         =>$Emergency_contact->phone_e,
        'email_e'        =>$Emergency_contact->email_e,
        'id_no'        =>$Emergency_contact->id_no,
        'address_e'        =>$Emergency_contact->address_e,
        'info_provided_by'        =>$Emergency_contact->info_provided_by,
         ]);
        if($Emergency_contact->save()){
                  return response()->json([
                 'message'  => 'Emergency_contact saved successfully',
                 'data'  => $Emergency_contact 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
      $p=$request->all(); 
        $id=$p['admission_id'];
        DB::enableQueryLog();
    	       $Emergency_contact = Emergency_contact::where("admission_id",$id)->first();
             if(!empty($Emergency_contact)){
                    return response()->json([
                    'data'  => $Emergency_contact      
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
        $Emergency_contact = Emergency_contact::all();
        return response()->json(['status' => 'Success', 'data' => $Emergency_contact]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
      'first_name_e'       => ['required'],
      'relation_e'    => ['required'],
      'phone_e'  => ['required', 'numeric', 'digits:10'],
    ]); 
    if ($validator->fails()) {
      return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
  }
         $p=$request->all();
        $id=$p['admission_id'];
        DB::enableQueryLog();
    $Emergency_contact = Emergency_contact::where("admission_id",$id)->first(); 
       
        $Emergency_contact->first_name_e= $request->first_name_e;
        $Emergency_contact->relation_e= $request->relation_e;
        $Emergency_contact->phone_e= $request->phone_e;
        $Emergency_contact->email_e= $request->email_e;
        $Emergency_contact->address_e= $request->address_e;

        if($Emergency_contact->save())
        {
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Emergency_contact
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
      $p=$request->all();
      $id=$p['admission_id'];
      DB::enableQueryLog();
  $Emergency_contact = Emergency_contact::where("admission_id",$id)->first(); 
        if(!empty($Emergency_contact))

                {
                  if($Emergency_contact->delete()){
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

    public function Suspend(Request $request)
    {
       $validator =  Validator::make($request->all(), [
            'date'=> ['required'],
            'reason'       => ['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
         $admission = Admission::find($request->admission_id); 
        $admission->reason =$request->reason;
       $admission->suspend_date =$request->date;
       $admission->suspend =1;
          
       if($admission->save())
       {
        
        return response()->json(apiResponseHandler( 'added successfully', $admission,200), 200);
       }
    else
    {
       return response()->json(apiResponseHandler([], 'unable to add ',400), 400);

    }
        
    }

     public function inactive(Request $request)
     {
      $inactive=Admission::join('add_stream','admission.class','=','add_stream.id')
      ->join('std_class','add_stream.class','=','std_class.class_id')
      ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
      ->select('suspend_date',DB::raw("CONCAT(admission.first_name,' ',admission.middle_name,' ',admission.last_name) as full_name"),
      'admission_no','reason','std_class.name as class','class_stream.name as stream','admission_id')->where('suspend','=',1)->get();
      if(!empty($inactive))
      {
      return response()->json(['status' => 'Success', 'data' => $inactive]);
      }else
      {
        return response()->json(['status' => 'No data found']);
      }
     }

     public function allsuspended(Request $request)
     {
      $inactive=Admission::select('reason','admission_id')->where('suspend','=',1)->first();
      if(!empty($inactive))
      {
      return response()->json(['status' => 'Success', 'data' => $inactive]);
      }else
      {
        return response()->json(['status' => 'No data found']);
      }
     }
      public function activate(Request $request)
     {
      $inactive=Admission::find($request->admission_id);
      $inactive->suspend=null;
      $inactive->reason=null;
      $inactive->suspend_date=null;
      $inactive->save();
      return response()->json(['status' => 'Success', 'data' => $inactive]);

     }

}
