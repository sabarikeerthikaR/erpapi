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
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Migrations\Migration;
use App\Models\Staff;
use App\Models\Group_staff;
use App\Models\User;
use App\Models\Settings;
use App\Models\Contracts;
use App\Models\Department;

class StaffController extends Controller
{
    public function store(Request $Staff)
    {
    	 $valiDationArray =  [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'gender' => ['required'],
            'DOB' => ['required'],
            'marital_status' => ['required'],
            'id_number' => ['required'],
            'status' => ['required'],
            'pin_number' => ['required'],
            'date_employed' => ['required'],
            'contract_id' => ['required'],
            'department' => ['required'],
            'position' => ['required'],
            'qualification' => ['required'],
            'proposed_leaving_date' => ['required'],
            'phone' => ['required', 'numeric', 'digits:10'],
            'email' => ['required', 'email', 'unique:users'],
            'tsc_employee' => ['required'],
            'kuppet_member' => ['required'],
           
        ]; 
        if($Staff->passport_photo)
        {
          $valiDationArray["passport_photo"]='required|file';
        }
        if($Staff->credential_certificate)
        {
          $valiDationArray["credential_certificate"]='required|file';
        }
        if($Staff->national_id_copy)
        {
          $valiDationArray["national_id_copy"]='required|file';
        }
        if($Staff->tsc_letter)
        {
          $valiDationArray["tsc_letter"]='required|file';
        }
        $validator =  Validator::make($Staff->all(),$valiDationArray); 
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
         }
         $passport_photo='';$credential_certificate='';
         if($Staff->file('passport_photo')){
         $passport_photo = $Staff->file('passport_photo');
         $imgName = time() . '.' . pathinfo($passport_photo->getClientOriginalName(), PATHINFO_EXTENSION);
         Storage::disk('public_uploads')->put('/staff-photo/' . $imgName, file_get_contents($passport_photo));
         $passport_photo=config('app.url').'/public/uploads/staff-photo/' . $imgName;
         }
         if($Staff->file('credential_certificate')){
           $credential_certificate= $Staff->file('credential_certificate');
           $cerName = time() . '.' . pathinfo($credential_certificate->getClientOriginalName(), PATHINFO_EXTENSION);
           Storage::disk('public_uploads')->put('/credential-certificate/' . $cerName, file_get_contents($credential_certificate));
           $credential_certificate=config('app.url').'/public/uploads/credential-certificate/' . $cerName;
         }
         $national_id_copy='';$tsc_letter='';
         if($Staff->file('national_id_copy')){
         $national_id_copy = $Staff->file('national_id_copy');
         $imgName = time() . '.' . pathinfo($national_id_copy->getClientOriginalName(), PATHINFO_EXTENSION);
         Storage::disk('public_uploads')->put('/staff-national-id/' . $imgName, file_get_contents($national_id_copy));
         $national_id_copy=config('app.url').'/public/uploads/staff-national-id/' . $imgName;
         }
         if($Staff->file('tsc_letter')){
           $tsc_letter= $Staff->file('tsc_letter');
           $cerName = time() . '.' . pathinfo($tsc_letter->getClientOriginalName(), PATHINFO_EXTENSION);
           Storage::disk('public_uploads')->put('/tsc-letter/' . $cerName, file_get_contents($tsc_letter));
           $tsc_letter=config('app.url').'/public/uploads/tsc-letter/' . $cerName;
         }
        $Staff=Staff::create([
        'employee_type'=>1,
        'first_name'    =>$Staff->first_name,
        'middle_name'          =>$Staff->middle_name,
        'last_name'         =>$Staff->last_name,
        'gender'        =>$Staff->gender,
        'DOB'        =>$Staff->DOB,
        'religion'        =>$Staff->religion,
        'marital_status'        =>$Staff->marital_status,
        'id_number'        =>$Staff->id_number,
        'pin_number'        =>$Staff->pin_number,
        'status'        =>$Staff->status,
        'tsc_employee'        =>$Staff->tsc_employee,
        'tsc_number'        =>$Staff->tsc_number,
        'knut_member'        =>$Staff->knut_member,
        'knut_number'        =>$Staff->knut_number,
        'kuppet_member'        =>$Staff->kuppet_member,
        'kuppet_number'        =>$Staff->kuppet_number,
        'date_employed'        =>$Staff->date_employed,
        'contract_id'        =>$Staff->contract_id,
        'department'        =>$Staff->department,
        'position'        =>$Staff->position,
        'qualification'        =>$Staff->qualification,
        'proposed_leaving_date	'        =>$Staff->proposed_leaving_date	,
        'subjects_specialized'        =>$Staff->subjects_specialized,
        'phone'        =>$Staff->phone,
        'email'        =>$Staff->email,
        'address'        =>$Staff->address,
        'employment_histroy'        =>$Staff->employment_histroy,
        'full_name'        =>$Staff->full_name,
        'phone_1'        =>$Staff->phone_1,
        'email_1'        =>$Staff->email_1,
        'address_1'        =>$Staff->address_1,
        'additional'        =>$Staff->additional,
        'passport_photo'        =>$passport_photo,
        'credential_certificate'        =>$credential_certificate,
        'national_id_copy'        =>$national_id_copy,
        'tsc_letter'        =>$tsc_letter,
         ]);        $Staff->save();
         $name=$Staff->first_name.' '.$Staff->middle_name .' '. $Staff->last_name;
        $settings=Settings::create([
          'key_name'=>$name,
          'group_name'=>'teaching_staff',
          'key_value'=>$Staff->employee_id,
        ]);
$settings->save();
$settingss=Settings::create([
    'key_name'=>$name,
    'group_name'=>'staff',
    'key_value'=>$Staff->employee_id,
  ]);
$settingss->save();
$fname = staff::select('first_name','middle_name','last_name')
->where('employee_id',$Staff->employee_id)
->first();
         $password = randomFunctionNumber(8);
         $objUser = User::create([  'user_role'=>3,
         'first_name' => ($fname)? $fname->first_name : null,
         'middle_name' => ($fname)? $fname->middle_name : null,
         'last_name' => ($fname)? $fname->last_name : null,
                                    'email' =>$Staff->email,
                                    'staff_id'=>$Staff->employee_id,
                                    'password' => Hash::make($password),
                                ]); 
                                 $objUser->save();
                                 $userData=$objUser;
        // email functions
        // send email with the template
    $email_data=[
      "url"=>config("app.url"),
      "email"=>$Staff->email,
      "password"=>$password,
      "name"=>$fname->first_name." ".$fname->middle_name." ".$fname->last_name
    ];
    Mail::send('email.welcome', $email_data, function ($message) use ($email_data) {
        $message->to($email_data['email'], $email_data['name'])
            ->subject('Welcome to RICO ERP')
            ->from('no-reply@arulphpdeveloper.com', 'RICO ERP');
    });
         
        if($Staff->save()){
                  return response()->json([
                 'message'  => 'Staff saved successfully',
                 'data'  => $Staff ,
                 'user'  => $objUser
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Staff = Staff::find($request->employee_id);
             if(!empty($Staff)){
                    return response()->json([
                    'data'  => $Staff      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
    }
   public function index(request $request)
    {
        $Staff = Staff::where('employee_id',$request->employee_id)->where('employee_type','=',1)
        ->leftjoin('contracts','staff.contract_id','=','contracts.contract_id')
        ->leftjoin('staff_department','staff.department','=','staff_department.id')
        ->leftjoin('setings as gen','staff.gender','=','gen.s_d')
        ->leftjoin('setings as re','staff.religion','=','re.s_d')
        ->leftjoin('setings as ma','staff.marital_status','=','ma.s_d')
        ->leftjoin('setings as st','staff.status','=','st.s_d')
        ->leftjoin('setings as tsc','staff.tsc_employee','=','tsc.s_d')
        ->leftjoin('setings as knut','staff.knut_member','=','knut.s_d')
        ->leftjoin('setings as kuppet','staff.kuppet_member','=','kuppet.s_d')
        ->select(db::raw("CONCAT (first_name,' ',COALESCE(middle_name,''),' ',last_name)As staff"),'contracts.name as contracts','staff_department.name as department',
        'gen.key_name as gender','re.key_name as religion','ma.key_name as marital_status',
        'st.key_name as status','tsc.key_name as tsc_employee','knut.key_name as knut_member',
        'kuppet.key_name as kuppet_member','DOB','id_number','pin_number','tsc_number','knut_number',
        'kuppet_number','date_employed','position','qualification','proposed_leaving_date','subjects_specialized',
        'phone','email','address','employment_histroy','full_name','phone_1','email_1','address_1',
        'additional','passport_photo','credential_certificate','national_id_copy','tsc_letter','disable','employee_id')
        ->first();

             if(!empty($Staff)){
                    return response()->json([
                    'data'  => $Staff      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
    }


public function update(Request $request)

   {
   	 $valiDationArray =  [
        'first_name' => ['required'],
        'last_name' => ['required'],
        'gender' => ['required'],
        'DOB' => ['required'],
        'marital_status' => ['required'],
        'id_number' => ['required'],
        'status' => ['required'],
        'pin_number' => ['required'],
        'date_employed' => ['required'],
        'contract_id' => ['required'],
        'department' => ['required'],
        'position' => ['required'],
        'qualification' => ['required'],
        'proposed_leaving_date' => ['required'],
        'phone' => ['required', 'numeric', 'digits:10'],
        'email' => ['required', 'email', 'unique:users,email,'.$request->employee_id],
        'tsc_employee' => ['required'],
        'kuppet_member' => ['required'],
        ]; 
        if($request->passport_photo)
        {
          $valiDationArray["passport_photo"]='required|file';
        }
        if($request->credential_certificate)
        {
          $valiDationArray["credential_certificate"]='required|file';
        }
        if($request->national_id_copy)
        {
          $valiDationArray["national_id_copy"]='required|file';
        }
        if($request->tsc_letter)
        {
          $valiDationArray["tsc_letter"]='required|file';
        }
        $validator =  Validator::make($request->all(),$valiDationArray); 
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
         }
    $Staff = Staff::find($request->employee_id);
          if($request->file('passport_photo')){
              $passport_photo = $request->file('passport_photo');
              $imgName = time() . '.' . pathinfo($passport_photo->getClientOriginalName(), PATHINFO_EXTENSION);
              Storage::disk('public_uploads')->put('/staff-photo/' . $imgName, file_get_contents($passport_photo));
              $passport_photo=config('app.url').'/public/uploads/staff-photo/' . $imgName;
              $Staff->passport_photo=$passport_photo;
              }
              if($request->file('credential_certificate')){
                $credential_certificate= $request->file('credential_certificate');
                $cerName = time() . '.' . pathinfo($credential_certificate->getClientOriginalName(), PATHINFO_EXTENSION);
                Storage::disk('public_uploads')->put('/credential-certificate/' . $cerName, file_get_contents($credential_certificate));
                $credential_certificate=config('app.url').'/public/uploads/credential-certificate/' . $cerName;
                $Staff->credential_certificate=$credential_certificate;
                }
                if($request->file('national_id_copy')){
                  $national_id_copy = $request->file('national_id_copy');
                  $imgName = time() . '.' . pathinfo($national_id_copy->getClientOriginalName(), PATHINFO_EXTENSION);
                  Storage::disk('public_uploads')->put('/staff-national-id/' . $imgName, file_get_contents($national_id_copy));
                  $national_id_copy=config('app.url').'/public/uploads/staff-national-id/' . $imgName;
                  $Staff->national_id_copy=$national_id_copy;
                  }
                  if($request->file('tsc_letter')){
                    $tsc_letter= $request->file('tsc_letter');
                    $cerName = time() . '.' . pathinfo($tsc_letter->getClientOriginalName(), PATHINFO_EXTENSION);
                    Storage::disk('public_uploads')->put('/tsc-letter/' . $cerName, file_get_contents($tsc_letter));
                    $tsc_letter=config('app.url').'/public/uploads/tsc-letter/' . $cerName;
                    $Staff->tsc_letter=$tsc_letter;
                    }

       
       $Staff->first_name= $request->first_name;
       $Staff->middle_name= $request->middle_name;
       $Staff->last_name= $request->last_name;
       $Staff->gender= $request->gender;
       $Staff->DOB= $request->DOB;
       $Staff->religion= $request->religion;
       $Staff->marital_status= $request->marital_status;
       $Staff->id_number= $request->id_number;
       $Staff->pin_number= $request->pin_number;
       $Staff->status= $request->status;
       $Staff->tsc_employee= $request->tsc_employee;
       $Staff->tsc_number= $request->tsc_number;
       $Staff->knut_member= $request->knut_member;
       $Staff->knut_number= $request->knut_number;
       $Staff->kuppet_member= $request->kuppet_member;
       $Staff->kuppet_number= $request->kuppet_number;
       $Staff->date_employed= $request->date_employed;
       $Staff->contract_id= $request->contract_id;
       $Staff->department= $request->department;
       $Staff->position= $request->position;
       $Staff->qualification= $request->qualification;
       $Staff->proposed_leaving_date= $request->proposed_leaving_date;
       $Staff->subjects_specialized= $request->subjects_specialized;
       $Staff->phone= $request->phone;
       $Staff->email= $request->email;
       $Staff->address= $request->address;
       $Staff->employment_histroy= $request->employment_histroy;
       $Staff->full_name= $request->full_name;
       $Staff->phone_1= $request->phone_1;
       $Staff->email_1= $request->email_1;
       $Staff->address_1= $request->address_1;
       $Staff->additional= $request->additional;
       $name=$request->first_name.' '. $request->middle_name .' '. $request->last_name;
       $settings=Settings::where('group_name','=','teaching_staff')->where('key_value',$request->employee_id)->first();
       $settings->key_name= $name;
       $settings->save();
       $settingss=Settings::where('group_name','=','staff')->where('key_value',$request->employee_id)->first();
       $settingss->key_name= $name;
       $settingss->save();
        if($Staff->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Staff
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Staff = Staff::find($request->employee_id);
        if(!empty($Staff))

                {
                  if($Staff->delete()){
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

    public function teaching(request $request)
   {
   
    $emp=db::table('staff')->join('setings as gen','staff.gender','=','gen.s_d')
    ->join('setings as st','staff.gender','=','st.s_d')
    ->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),'passport_photo','gen.key_name as gender','st.key_name as status','position','employee_id')->join('group_staff','staff.employee_type','=','group_staff.employee_type')
    ->where('staff.employee_type','=',1)
    ->where('staff.disable','=',NULL)
    ->get(); 
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
   public function nonteaching(request $request)
   {
    $emp=db::table('staff')->join('setings as gen','staff.gender','=','gen.s_d')
    ->join('setings as st','staff.gender','=','st.s_d')->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),'passport_photo','gen.key_name as gender','st.key_name as status','position','employee_id')->join('group_staff','staff.employee_type','=','group_staff.employee_type')
    ->where('staff.employee_type','=',2)
    ->where('staff.disable','=',NULL)
    ->get();  
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
   public function supporting(request $request)
   {
    $emp=db::table('staff')->join('setings as gen','staff.gender','=','gen.s_d')
    ->join('setings as st','staff.gender','=','st.s_d')->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),'passport_photo','gen.key_name as gender','st.key_name as status','position','employee_id')
    ->join('group_staff','staff.employee_type','=','group_staff.employee_type')
    ->where('staff.employee_type','=',3)
    ->where('staff.disable','=',NULL)
    ->get();   if(!empty($emp)){
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

   public function disable(request $request)
   {
      
       $staff = Staff::where('employee_id',$request->employee_id)->where('staff.employee_type','=',1)->first();
       $staff->disable=1;
       if($staff->save()){
           return response()->json([
                'message'  => 'disabled successfully',
                'data'=>$staff->disable
               
           ]);
       }else {
           return response()->json([
                'message'  => 'failed'
                ]);
       }
   }
   public function enable(request $request)
   {
      
       $staff = Staff::where('employee_id',$request->employee_id)->where('staff.employee_type','=',1)->first();
       $staff->disable=NULL;
       if($staff->save()){
           return response()->json([
                'message'  => 'enabled successfully',
              
           ]);
       }else {
           return response()->json([
                'message'  => 'failed'
                ]);
       }
   }
   public function inactive()
   {
    $staff = db::table('staff')->join('setings as gen','staff.gender','=','gen.s_d')
    ->join('setings as st','staff.gender','=','st.s_d')->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),'passport_photo','gen.key_name as gender','st.key_name as status','position','employee_id')->join('group_staff','staff.employee_type','=','group_staff.employee_type')
    ->where('disable','=',1)->where('staff.employee_type','=',1)
    ->get();  
       return response()->json(['status' => 'Success', 'data' => $staff]);
   }

   public function teachingstafffullprofile(request $request)
    {
        $Staff = Staff::where('employee_id',$request->employee_id)->where('employee_type','=',1)
        ->leftjoin('contracts','staff.contract_id','=','contracts.contract_id')
        ->leftjoin('staff_department','staff.department','=','staff_department.id')
        ->leftjoin('setings as gen','staff.gender','=','gen.s_d')
        ->leftjoin('setings as re','staff.religion','=','re.s_d')
        ->leftjoin('setings as ma','staff.marital_status','=','ma.s_d')
        ->leftjoin('setings as st','staff.status','=','st.s_d')
        ->leftjoin('setings as tsc','staff.tsc_employee','=','tsc.s_d')
        ->leftjoin('setings as knut','staff.knut_member','=','knut.s_d')
        ->leftjoin('setings as kuppet','staff.kuppet_member','=','kuppet.s_d')
        ->select(db::raw("CONCAT (first_name,' ',COALESCE(middle_name,''),' ',last_name)As staff"),'contracts.name as contracts','staff_department.name as department',
        'gen.key_name as gender','re.key_name as religion','ma.key_name as marital_status',
        'st.key_name as status','tsc.key_name as tsc_employee','knut.key_name as knut_member',
        'kuppet.key_name as kuppet_member','DOB','id_number','pin_number','tsc_number','knut_number',
        'kuppet_number','date_employed','position','qualification','proposed_leaving_date','subjects_specialized',
        'phone','email','address','employment_histroy','full_name','phone_1','email_1','address_1',
        'additional','passport_photo','credential_certificate','national_id_copy','tsc_letter','disable','employee_id')
        ->first();

             if(!empty($Staff)){
                    return response()->json([
                    'data'  => $Staff      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
    }

    public function teachingstaffprofile(request $request)
    {
    
     $emp=db::table('staff')->leftjoin('setings as gen','staff.gender','=','gen.s_d')
     ->leftjoin('setings as st','staff.gender','=','st.s_d')
     ->leftjoin('group_staff','staff.employee_type','=','group_staff.employee_type')
     ->where('employee_id',$request->employee_id)
     ->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),'passport_photo','gen.key_name as gender','st.key_name as status','position','employee_id')
     ->where('staff.disable','=',NULL)
     ->get(); 
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

}
