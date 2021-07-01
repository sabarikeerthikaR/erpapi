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
use App\Models\OnlineRegistration;

class OnlineRegistrationController extends Controller
{
     public function store(Request $Online_registration)
    {
    	 $valiDationArray =[
            'first_name' => ['required'],
            'last_name' => ['required'],
            'admission_for' => ['required'],
            'dob' => ['required'],
            'gender' => ['required'],
            'first_parent' => ['required'],
            'email_f' => ['required','email' ],
            'phone_f' => ['required','numeric','digits:10'],
            'address_f'=> ['required'],
            'former_school' => ['required'],
            'grade_completed' => ['required'],
            'disability_if_any'=> ['required'],
        ]; 
        if($Online_registration->image)
        {
          $valiDationArray["image"]='required|file';
        }
        $validator =  Validator::make($Online_registration->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $image='';
        if($Online_registration->file('image')){
            $image = $Online_registration->file('image');
            $imgName = time() . '.' . pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            Storage::disk('public_uploads')->put('/reg-students-photo/' . $imgName, file_get_contents($image));
            $image=config('app.url').'/public/uploads/reg-students-photo/' . $imgName;
            }
        $Online_registration=OnlineRegistration::create([
        	'date'    =>date('Y-m-d'),
        'first_name'    =>$Online_registration->first_name,
        'middle_name'          =>$Online_registration->middle_name,
        'last_name'         =>$Online_registration->last_name,
        'admission_for'        =>$Online_registration->admission_for,
        'dob'        =>$Online_registration->dob,
        'gender'        =>$Online_registration->gender,
        'religion'        =>$Online_registration->religion,
        'nationality'        =>$Online_registration->nationality,
        'first_parent'        =>$Online_registration->first_parent,
        'first_parent_occupation'=>$Online_registration->first_parent_occupation,
        'relation_f'        =>$Online_registration->relation_f,
        'email_f'        =>$Online_registration->email_f,
        'phone_f'        =>$Online_registration->phone_f,
        'address_f'      =>$Online_registration->address_f,
        'second_parent'        =>$Online_registration->second_parent,
        'second_parent_occupation'=>$Online_registration->second_parent_occupation,
        'relation_s'        =>$Online_registration->relation_s,
        'email_s'        =>$Online_registration->email_s,
        'phone_s'        =>$Online_registration->phone_s,
        'address_s'      =>$Online_registration->address_s,
        'former_school'   =>$Online_registration->former_school,
        'reason_for_leaving' =>$Online_registration->reason_for_leaving,
        'grade_completed'=>$Online_registration->grade_completed,
        'disability_if_any'=>$Online_registration->disability_if_any,
        'local_guardian'  =>$Online_registration->local_guardian,
        'comments'        =>$Online_registration->comments,
        'image'        =>$image,
         'address'        =>$Online_registration->address,
          'phone'        =>$Online_registration->phone,
         ]);
        if($Online_registration->save()){
                  return response()->json([
                 'message'  => 'Online_registration saved successfully',
                 'data'  => $Online_registration 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Online_registration = OnlineRegistration::where('online_reg_id',$request->online_reg_id)
               ->leftjoin('add_stream','online_registration.admission_for','=','add_stream.id')
               ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
               ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
               ->select('online_registration.status','phone','address','image','comments','local_guardian','disability_if_any',
               'grade_completed','reason_for_leaving','former_school','address_s','phone_s','email_s','relation_s',
               'second_parent_occupation','second_parent','address_f','phone_f','email_f','relation_f','first_parent_occupation',
               'first_parent','nationality','religion','gender','dob',
               db::raw("CONCAT(std_class.name,' ',class_stream.name)as admission_for"),'last_name','middle_name',
               'first_name','online_registration.date','online_reg_id')
               ->first(); 

             if(!empty($Online_registration)){
                    return response()->json([
                    'data'  => $Online_registration      
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
        $Online_registrations = OnlineRegistration::where('status',NULL)->get();
        return response()->json(['message' => 'Success', 'data' => $Online_registrations]);
    }


public function update(Request $request)

   {
   	 $valiDationArray =  [
        'first_name' => ['required'],
            'last_name' => ['required'],
            'admission_for' => ['required'],
            'dob' => ['required'],
            'gender' => ['required'],
            'first_parent' => ['required'],
            'email_f' => ['required','email' ],
            'phone_f' => ['required', 'numeric', 'digits:10'],
            'address_f'=> ['required'],
            'former_school' => ['required'],
            'grade_completed' => ['required'],
            'disability_if_any'=> ['required'],
            'image' => ['required']
        ];
        if($request->image)
     {
       $valiDationArray["image"]='required|file';
     } 
     $validator =  Validator::make($request->all(),$valiDationArray); 
     if ($validator->fails()) {
         return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
     }
     $Online_registration = OnlineRegistration::find($request->online_reg_id); 
  
     if($request->file('image')){
        $image = $request->file('image');
        $imgName = time() . '.' . pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
        Storage::disk('public_uploads')->put('/students-photo/' . $imgName, file_get_contents($image));
        $image=config('app.url').'/public/uploads/students-photo/' . $imgName;
        $Online_registration->image=$image;
        }
       $Online_registration->first_name= $request->first_name;
       $Online_registration->middle_name= $request->middle_name;
       $Online_registration->last_name= $request->last_name;
       $Online_registration->admission_for= $request->admission_for;
       $Online_registration->dob= $request->dob;
       $Online_registration->gender= $request->gender;
       $Online_registration->religion= $request->religion;
       $Online_registration->nationality= $request->nationality;
       $Online_registration->first_parent= $request->first_parent;
       $Online_registration->first_parent_occupation= $request->first_parent_occupation;
       $Online_registration->relation_f= $request->relation_f;
       $Online_registration->email_f= $request->email_f;
       $Online_registration->phone_f= $request->phone_f;
       $Online_registration->address_f= $request->address_f;
       $Online_registration->second_parent= $request->second_parent;
      $Online_registration->second_parent_occupation= $request->second_parent_occupation;
       $Online_registration->relation_s= $request->relation_s;
       $Online_registration->email_s= $request->email_s;
       $Online_registration->phone_s= $request->phone_s;
       $Online_registration->address_s= $request->address_s;
       $Online_registration->former_school= $request->former_school;
       $Online_registration->reason_for_leaving= $request->reason_for_leaving;
       $Online_registration->grade_completed= $request->grade_completed;
       $Online_registration->disability_if_any= $request->disability_if_any;
       $Online_registration->local_guardian= $request->local_guardian;
       $Online_registration->comments= $request->comments;
       $Online_registration->image= $request->image;
        $Online_registration->address= $request->address;
       $Online_registration->phone= $request->phone;
        if($Online_registration->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Online_registration
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Online_registration = OnlineRegistration::find($request->online_reg_id);
        if(!empty($Online_registration))

                {
                  if($Online_registration->delete()){
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

}
