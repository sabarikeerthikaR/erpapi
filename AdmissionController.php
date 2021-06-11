<?php

namespace App\Http\Controllers\school;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Exceptions;
use Whoops\Handler;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use Illuminate\Database\Migrations\Migration;
use App\Mail\BirthdayRemainder;
use App\Models\ParentStudents;
use App\Models\Admission;
use App\Models\Online_registration;
use App\Models\First_parent;
use App\Models\Second_parent;
use App\Models\Emergency_contact;
use App\Models\AssignBed;
use App\Models\Discipline;
use App\Models\Fee_payment;
use App\Models\HostelBed;
use App\Models\HostelRooms;
use App\Models\NewPlacement;
use App\Models\Transport;
use App\Models\Settings;
use App\Models\Counties;
use App\Models\SubCounty;
use App\Models\Std_class;
use App\Models\Student_house;
use App\Models\Position;
use App\Models\Hostel;
use App\Models\Staff;

use App\Models\PaymentMethod;
use App\Models\Bank_name;
use App\Models\Bank_account;
use Config\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
class AdmissionController extends Controller
{

     public function store(Request $Admission)
    {
      $adminValidaton=$Admission;
      $valiDationArray=[
        'boarding' => ['required'],
        'first_name' => ['required'],
        'last_name' => ['required'],
        'dob' => ['required'],
        'gender' => ['required'],
        'student_status' => ['required'],
        'disabled' => ['required'],
        'scholarship' => ['required'],
        
        'citizenship'=> ['required'],
        'home_country'=> ['required'],
       
        'residence'=> ['required'],
        'emergency_phone' => ['required', 'numeric','digits:10'],
       
  
       ];
       if($Admission->image)
       {
         $valiDationArray["image"]='required|file';
       }
       if($Admission->birth_certificate)
       {
         $valiDationArray["birth_certificate"]='required|file';
       }
       $validator =  Validator::make($Admission->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $image='';$birth_certificate='';
        if($Admission->file('image')){
        $image = $Admission->file('image');
        $imgName = time() . '.' . pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
        Storage::disk('public')->put('students-photo/' . $imgName, file_get_contents($image));
        $image=config('app.url').'students-photo/' . $imgName;
        }
        if($Admission->file('birth_certificate')){
          $birth_certificate= $Admission->file('birth_certificate');
          $cerName = time() . '.' . pathinfo($birth_certificate->getClientOriginalName(), PATHINFO_EXTENSION);
          Storage::disk('public')->put('students-certificate/' . $cerName, file_get_contents($birth_certificate));
          $birth_certificate=config('app.url').'students-certificate/' . $cerName;
        }
        $dummy=NULL;
  
        $count=Admission::count();
        $date=date("Y").date('m').date("d");
        $value = 11;
       
        $parent_id=$Admission->parent_id;
        
        $Admission=Admission::create([
      
        'boarding'    =>$Admission->boarding,
        'first_name'    =>$Admission->first_name,
        'middle_name'          =>$Admission->middle_name,
        'last_name'         =>$Admission->last_name,
        'dob'        =>$Admission->dob,
        'gender'        =>$Admission->gender,
        'student_status'        =>$Admission->student_status,
        'disabled'        =>$Admission->disabled,
        'blood_group'        =>$Admission->blood_group,
        'allergies'        =>$Admission->allergies,
        'religion'        =>$Admission->religion,
        'former_Scl'        =>$Admission->former_Scl,
        'entry_mark'        =>$Admission->entry_mark,
        'doctor_name'        =>$Admission->doctor_name,
        'doctor_phone'        =>$Admission->doctor_phone,
        'preferred_hospital'        =>$Admission->preferred_hospital,
        'scholarship'        =>$Admission->scholarship,
        'type'   =>$Admission->type,
        'sponsor_detail' =>$Admission->sponsor_detail,
        'phone' =>$Admission->phone,
        'location' =>$Admission->location,
        'contact_person'=>$Admission->contact_person,
        'citizenship'=>$Admission->citizenship,
        'home_country'=>$Admission->home_country,
        'sub_country'  =>$Admission->sub_country,
        'residence' =>$Admission->residence,
        'emergency_phone'=>$Admission->emergency_phone,
        'student_phone'=>$Admission->student_phone,
        'email'        =>$Admission->email,
        'image'        =>$image,
        'birth_certificate' =>$birth_certificate,
        'Admission_no' =>time(),
         'year' =>date("Y"),
         'admitted_by' =>'admin'
         ]);
       
        $Admission->save();
        
        $admission_id= $Admission->admission_id;
       
        $name=$Admission->first_name.' '. $Admission-> middle_name .' '. $Admission->last_name;
        if($parent_id)
        {
         
           $parent1=First_parent::where("parent1_id",$parent_id)->first();
           if(!$parent1)
           {
            return response()->json(apiResponseHandler([], "Parent is invalid", 400), 400);
           }
           
          $ParentStudents=ParentStudents::create([
          'p_id'=>$parent_id,
          'admission_id'=>$admission_id
          ]);
           $ParentStudents->save();
           $parent1=First_parent::where("parent1_id",$parent_id)->first();
           $parent2=Second_parent::where("parent1_id",$parent_id)->first();
           $Emergency_contact =Emergency_contact::where("parent",$parent_id)->first();
           $userData=User::where("parent",$parent_id);
           $user=1;
        }
        else
        {
        $validator =  Validator::make($adminValidaton->all(),[
          'email' => ['required','unique:users']
        ]); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        
        $user=0;
          
        $parent1=First_parent::create([
        'admission_id'  =>$admission_id,
        'title_f'           =>$dummy,
        'relation_f'        =>$dummy,
        'first_name_f'      =>$dummy,
        'middle_name_f'      =>$dummy,
        'last_name_f'       =>$dummy,
        'phone_f'           =>$dummy,
        'email_f'           =>$dummy,
        'id_passport_f'     =>$dummy,
        'occupation_f'      =>$dummy,
        'address_f'         =>$dummy,
        'postal_code_f'     =>$dummy,
        'passport_photo_f'  =>$dummy,
        'national_id_f'       =>$dummy,
         ]);
        
        $parent1->save();
        $first_parent=$parent1->parent1_id;
        $ParentStudents=ParentStudents::create([
          'p_id'=>$parent1->parent1_id,
          'admission_id'=>$admission_id
          ]);
        $ParentStudents->save();
        $parent2=Second_parent::create([
        'admission_id'  =>$admission_id,
        'title_s'           =>$dummy,
        'relation_s'        =>$dummy,
        'first_name_s'      =>$dummy,
        'middle_name_s'      =>$dummy,
        'last_name_s'       =>$dummy,
        'phone_s'           =>$dummy,
        'email_s'           =>$dummy,
        'id_passport_s'     =>$dummy,
        'occupation_s'      =>$dummy,
        'address_s'         =>$dummy,
        'postal_code_s'     =>$dummy,
        'passport_photo_s'  =>$dummy,
        'national_id_s'       =>$dummy,
        'parent1_id' =>$parent1->parent1_id
         ]);
        $parent2->save();
        $Emergency_contact=Emergency_contact::create([
        'admission_id' =>$admission_id,
        'first_name_e'          =>$dummy,
        'middle_name_e'          =>$dummy,
        'last_name_e'          =>$dummy,
        'relation_e'      =>$dummy,
        'phone_e'         =>$dummy,
        'email_e'        =>$dummy,
        'parent'=>$parent1->parent1_id,
        'id_no_e'        =>$dummy,
        'address_e'        =>$dummy,
        'info_provided_by'=>$dummy,
         ]);
        $Emergency_contact->save();
        
        //move user registration
         $fname = $Admission;
         $email=$Admission->email;
         //$password = randomFunctionNumber(8);
         $password="12345678";
         $objUser = User::create([  'user_role'=>4,
                                    'first_name' => ($fname)? $fname->first_name : null,
                                    'middle_name' => ($fname)? $fname->middle_name : null,
                                    'last_name' => ($fname)? $fname->last_name : null,
                                    'email' => $email,
                                    'admission_id' => $Admission->admission_id,
                                    'parent'=>$parent1->parent1_id,
                                    'password' => Hash::make($password),
                                ]);

        $objUser->save();
        $userData=$objUser;
        // email functions
        // send email with the template
    $email_data=[
      "url"=>config("app.url"),
      "email"=>$email,
      "password"=>$password,
      "name"=>$fname->first_name." ".$fname->middle_name." ".$fname->last_name
    ];
    Mail::send('email.welcome', $email_data, function ($message) use ($email_data) {
        $message->to($email_data['email'], $email_data['name'])
            ->subject('Welcome to RICO ERP')
            ->from('no-reply@arulphpdeveloper.com', 'RICO ERP');
    });
        
      }
      if($parent1 && $parent2 && $Emergency_contact)
      {
        
                 return response()->json([
                 'message'  => 'Admission saved successfully',
                 'data'=>[
                  'user'=>$user,
                  "userData"=>$userData,
                   'admission_id'=>$admission_id,"parent_id"=>$parent1->parent1_id,"parent_two_id"=>$parent2->parent2_id,"emergencycontact"=>$Emergency_contact->emergency_id]
                  ]);
      } else 
      {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
      }
    }
public function show(request $request)
    { 
             $Admission = Admission::find($request->admission_id);
             if(!empty($Admission)){
                    return response()->json([
                    'data'  => $Admission      
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
     
      $Admission = Admission::all();
        return response()->json(['status' => 'Success', 'data' => $Admission]);
    }


public function update(Request $request)

   {
     $valiDationArray=[
      'boarding' => ['required'],
      'first_name' => ['required'],
      'last_name' => ['required'],
      'dob' => ['required'],
      'gender' => ['required'],
      'student_status' => ['required'],
      'disabled' => ['required'],
      'scholarship' => ['required'],
      'citizenship'=> ['required'],
      'home_country'=> ['required'],
     
      'residence'=> ['required'],
      'emergency_phone' => ['required', 'numeric','digits:10'],
     ];
     if($request->image)
     {
       $valiDationArray["image"]='required|file';
     }
     if($request->birth_certificate)
     {
       $valiDationArray["birth_certificate"]='required|file';
     }
     $validator =  Validator::make($request->all(),$valiDationArray); 
      if ($validator->fails()) {
          return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
      }
      $Admission = Admission::find($request->admission_id);

      if($request->file('image')){
      $image = $request->file('image');
      $imgName = time() . '.' . pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
      Storage::disk('public')->put('/students-photo/' . $imgName, file_get_contents($image));
      $image=config('app.url').'/students-photo/' . $imgName;
      $Admission->image=$image;
      }
      if($request->file('birth_certificate')){
        $birth_certificate= $request->file('birth_certificate');
        $cerName = time() . '.' . pathinfo($birth_certificate->getClientOriginalName(), PATHINFO_EXTENSION);
        Storage::disk('public')->put('students-certificate/' . $cerName, file_get_contents($birth_certificate));
        $birth_certificate=config('app.url').'/students-certificate/' . $cerName;
        $Admission->birth_certificate=$birth_certificate;
        }

        $Admission->boarding= $request->boarding;
       $Admission->first_name= $request->first_name;
       $Admission->middle_name= $request->middle_name;
       $Admission->last_name= $request->last_name;
       $Admission->dob= $request->dob;
       $Admission->gender= $request->gender;
       $Admission->student_status= $request->student_status;
       $Admission->disabled= $request->disabled;
       $Admission->blood_group= $request->blood_group;
       $Admission->religion= $request->religion;
       $Admission->allergies= $request->allergies;
       $Admission->former_Scl= $request->former_Scl;
       $Admission->entry_mark= $request->entry_mark;
       $Admission->doctor_name= $request->doctor_name;
       $Admission->doctor_phone= $request->doctor_phone;
       $Admission->preferred_hospital= $request->preferred_hospital;
       $Admission->scholarship= $request->scholarship;
       $Admission->type= $request->type;
       $Admission->sponsor_detail= $request->sponsor_detail;
       $Admission->phone= $request->phone;
       $Admission->location= $request->location;
       $Admission->contact_person= $request->contact_person;
       $Admission->citizenship= $request->citizenship;
       $Admission->home_country= $request->home_country;
       $Admission->sub_country= $request->sub_country;
       $Admission->residence= $request->residence;
       $Admission->emergency_phone= $request->emergency_phone;
       $Admission->student_phone= $request->student_phone;
       $Admission->email= $request->email;
     
         $Admission->save();
        $name=$request->first_name.' '. $request-> middle_name .' '. $request-> last_name;
        $settings=Settings::where('group_name','=','student')->where('key_value',$request->admission_id)->first();
        $settings->key_name= $name;
        $settings->save();
      $dummy=NULL;
      $id=$request->admission_id;
      DB::enableQueryLog();
        $parent1 = First_parent::where('admission_id',$id)->first();
      $parent1->admission_id=$id;
           $parent1->save();
       
        $parent2 = Second_parent::where("admission_id",$id)->first();
       $parent2->admission_id=$id;
        $parent2->save();
      
      $emergencycontact = Emergency_contact::where("admission_id",$id)->first();
        $emergencycontact->admission_id=$id; 
        if($emergencycontact->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'=>['admission_id'=>$id,"parent_id"=>$parent1->parent1_id,"parent_two_id"=>$parent2->parent2_id,"emergencycontact"=>$emergencycontact->emergency_id]
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Admission = Admission::find($request->admission_id);
        if(!empty($Admission))

                {
                  if($Admission->delete()){
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

    
    public function admit(request $request)
  {
   
        $count=Admission::count();
        $admission_id=date("Ymd").$count;
        $Online_registration = Online_registration::find($request->online_reg_id);
        $Online_registration->status=1;
        $Online_registration->save();
        if(!empty($Online_registration))

       {
         $admission=Admission::create([
           'admission_id'=>$admission_id,
           'first_name'=>$Online_registration->first_name,
           'middle_name'       =>$Online_registration->middle_name,
           'last_name'         =>$Online_registration->last_name,
           'dob'               =>$Online_registration->dob,
           'gender'            =>$Online_registration->gender,
           'disabled'          =>$Online_registration->disability_if_any,
           'religion'          =>$Online_registration->religion,
           'former_Scl'        =>$Online_registration->former_school,
           'citizenship'       =>$Online_registration->nationality,
           'image'             =>$Online_registration->image,
         ]);
       
         $admission->save();
         $parent1=First_parent::create([
        'admission_id'  =>$admission_id,
        'relation_f'        =>$Online_registration->relation_f,
        'first_name_f'      =>$Online_registration->first_parent,
        'phone_f'           =>$Online_registration->phone_f,
        'email_f'           =>$Online_registration->email_f,
        'occupation_f'      =>$Online_registration->first_parent_occupation,
        'address_f'         =>$Online_registration->address_f,
         ]);
         $parent1->save();
         $parent1_id=$parent1->parent1_id;
         $parent2=Second_parent::create([
        'admission_id'  =>$admission_id,
        'relation_s'        =>$Online_registration->relation_s,
        'first_name_s'      =>$Online_registration->second_parent,
        'phone_s'           =>$Online_registration->phone_s,
        'email_s'           =>$Online_registration->email_s,
        'occupation_s'      =>$Online_registration->second_parent_occupation,
        'address_s'         =>$Online_registration->address_s,
        'parent1_id'=>$parent1_id
         ]);
         
        if($parent2->save())
             {
                  return response()->json([
                 'message'  => 'Admission saved successfully',
                 'id'       =>$admission_id,
                'Admission'  => $admission,
                 'First_parent'  => $parent1,
                 'Second_parent' => $parent2, 
                  ]);
              }
              else 
              {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
              }
               
      }
     
  }
  public function birthday(request $email)
 { 
    $i = 0;
   // $emails = Admission::whereMonth('dob', '=', date('m'))->whereDay('dob', '=', date('d'))->select('email')->first(); 
   $users = Admission::whereMonth('dob', '=', date('m'))->whereDay('dob', '=', date('d'))->select( DB::raw("CONCAT(admission.first_name,' ',admission.middle_name,' ',admission.last_name) as full_name"),
   DB::raw("DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, dob)),'%y') AS age"),'admission.image','admission.gender','admission.class','admission.dob','admission.admission_no','email')->first(); 
  $birthday='today is your birthday';
   if(!empty($users))
        {
                return response()->json([
                 
                 'list student' => $users, 
                  ]);
        }
      else{
        return response()->json([
                 'message'  => 'no data found',
                
                  ]);
      }
   
}
  public function allstudent()

  {   

    $students = DB::table('admission')
    ->leftjoin('add_stream','admission.class','=','add_stream.id')
    ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
    ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
    ->leftjoin('setings','admission.gender','=','setings.s_d')
            ->leftjoin('first_parent', 'admission.admission_id', '=', 'first_parent.admission_id')
            ->leftjoin('second_parent', 'admission.admission_id', '=', 'second_parent.admission_id')
            ->leftjoin('emergency_contact', 'admission.admission_id', '=', 'emergency_contact.admission_id')
            ->select( DB::raw("CONCAT (admission.first_name,' ',COALESCE(admission.middle_name,''),' ',admission.last_name)As full_name"),
            DB::raw("DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, dob)),'%y') AS age"), 
            DB::raw("CONCAT(first_parent.first_name_f,' ',COALESCE(first_parent.middle_name_f,''),' ',first_parent.last_name_f) as parent"),
            'admission.image','setings.key_name as gender','std_class.name as class','class_stream.name as stream','admission.admission_id','admission.admission_no',
            'first_parent.phone_f')->
            get();
             return response()->json(['status' => 'Success', 'data' => $students]);
  }

  public function studentProfile(request $request)

  {
$id=$request->admission_id;
    $student= Admission::leftjoin('setings as blood','admission.blood_group','=','blood.s_d')-> 
    leftjoin('setings as dis','admission.disabled','=','dis.s_d')->
    leftjoin('setings as ge','admission.gender','=','ge.s_d')->
    leftjoin('setings as st','admission.student_status','=','st.s_d')->
    leftjoin('setings as ci','admission.citizenship','=','ci.s_d')->
    leftjoin('setings as bo','admission.boarding','=','bo.s_d')->
    leftjoin('setings as re','admission.religion','=','re.s_d')-> 
    leftjoin('setings as sc','admission.scholarship','=','sc.s_d')->
    leftjoin('counties','admission.home_country','=','counties.id')->
    leftjoin('sub_county','admission.sub_country','=','sub_county.id')-> 
    leftjoin('add_stream','admission.class','=','add_stream.id')
    ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')->
    leftjoin('std_class','add_stream.class','=','std_class.class_id')-> 
    leftjoin('student_house','admission.house','=','student_house.house_id')

    ->where('admission_id',$id)->select(db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as student_name"),'admission_no as adm_no','admission_id as upi_no','ge.key_name as gender','dob','dis.key_name as disable','blood.key_name as blood_group','st.key_name as status',
    'phone','ci.key_name as citizenship','counties.name as county','sub_county.sub_county','residence',
    'std_class.name as class','class_stream.name as stream','emergency_phone','student_house.name as house','bo.key_name as scholar','re.key_name as religion',
    'former_Scl','entry_mark','allergies','doctor_name','sc.key_name as scholarship','admitted_by','date as admitted_on','image')->first();
    $parent1= First_parent::leftjoin('setings as re1','first_parent.relation_f','=','re1.s_d')->where('first_parent.admission_id',$id)->select('re1.key_name as relation_f','phone_f',
    'email_f','occupation_f','id_passport_f','address_f','postal_code_f',db::raw("concat(first_name_f,' ',
    COALESCE(middle_name_f,''),' ',last_name_f)as first_parent"))->first();
    $parent2=Second_parent::leftjoin('setings as re2','second_parent.relation_s','=','re2.s_d')
    ->select('re2.key_name as relation_s','phone_s','email_s',
    'id_passport_s','occupation_s','address_s','postal_code_s',db::raw("concat(first_name_s,' ',
    COALESCE(middle_name_s,''),' ',last_name_s)as second_parent"))->where('second_parent.admission_id',$id)->first();
    $emergencycontact=Emergency_contact::leftjoin('setings as re3','emergency_contact.relation_e','=','re3.s_d')
    ->select('re3.key_name as relation_e','phone_e','email_e',
    'id_no_e','address_e','info_provided_by')->where('emergency_contact.admission_id',$id)->first();
    
    $leadershipPosition=NewPlacement::leftjoin('position','new_placement.position','=','position.id')
    ->leftjoin('admission','new_placement.student','=','admission.admission_id')
    ->leftjoin('std_class','new_placement.rep_of','=','std_class.class_id')
    ->select('new_placement.date','date_upto','new_placement.description','position.name as position',
    'std_class.name as rep_of',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as student"))->where('student',$id)->first();

    $discipline=Discipline::leftjoin('staff','discipline.reported_by','=','staff.employee_id')
    ->select('discipline.date',db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),' ',staff.last_name)as reported_by"),'others','notify_parent',
    'description','action_taken','comment','action_taken_on','created_by')->where('culprit',$id)->first();

    $transport=Transport::leftjoin('add_route','transport.route','=','add_route.id')
    ->leftjoin('admission','transport.stud_name','=','admission.admission_id')
    ->leftjoin('setings','admission.year','=','setings.s_d')
    ->leftjoin('add_stream','admission.class','=','add_stream.id')
    ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
    ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
    ->select('admission_no','setings.key_name as year','std_class.name as class','class_stream.name','add_route.name as route',
    db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as student"))
    ->where('stud_name',$id)->first();

    $assign=AssignBed::leftjoin('setings as te','assign_bed.term','=','te.s_d')
    ->leftjoin('setings as ye','assign_bed.year','=','ye.s_d')
    ->leftjoin('hostel_bed','assign_bed.bed','=','hostel_bed.id')
    ->leftjoin('admission','assign_bed.student','=','admission.admission_id')
    ->leftjoin('hostel_rooms','hostel_bed.hostel_room', '=', 'hostel_rooms.id')
    ->leftjoin('hostel','hostel_rooms.hostel','=','hostel.id')
    ->leftjoin('staff','hostel.janitor','=','staff.employee_id')
    ->select('assign_bed.date',db::raw("CONCAT(admission.first_name,' ',COALESCE(admission.middle_name,''),' ',admission.last_name)as student"),
    'te.key_name as term','ye.key_name as year','hostel_bed.bed_no as bed','assign_bed.assigned_by',
    'hostel_rooms.room_name as room','hostel.title as hostel',db::raw("CONCAT(staff.first_name,' ',staff.middle_name,' ',staff.last_name)as janitor"))
    ->where('assign_bed.student',$id)->first();
   
    $PaymentHist=Fee_payment::leftjoin('bank_account','fee_payment.bank','=','bank_account.id')
     ->leftjoin('bank_name','bank_account.bank_name','=','bank_name.id')
     ->leftjoin('admission','fee_payment.student','=','admission.admission_id')
     ->leftjoin('payment_method','fee_payment.payment_method','=','payment_method.id')
     ->select('fee_payment.date','amount','transaction_no','tuition_fee','payment_method.name as payment_method',
     'bank_name.name as bank_name',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as student"))
    ->where('student',$id)->first();
            if(!empty($student))
      {
      return response()->json(['status' => 'Success', 'student' => $student,
'leadershipPosition' => $leadershipPosition,
'parent1' => $parent1,
'parent2' => $parent2,
'emergency contact' => $emergencycontact,
'roomBeds' => $assign,
'discipline' => $discipline,
'transport' => $transport,
'PaymentHist' => $PaymentHist]);
      }else
      {
        return response()->json(['status' => 'No data found']);
      }
  }
  
  public function Alumnistudent(request $request)

  {
    $inactive=Admission::leftjoin('add_stream','admission.class','=','add_stream.id')
    ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
    ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
    ->select(DB::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name) as full_name"),
    'std_class.name as class','class_stream.name as stream','dob','gender','admission_no','date','completion_yr','admission_id')
    ->where('completion_yr','>',0)->get();
       if(!empty($inactive))
      {
      return response()->json(['status' => 'Success', 'data' => $inactive]);
      }else
      {
        return response()->json(['status' => 'No data found']);
      }
  }
  public function myStaff(request $request)
  {
    
  }
  
}
