<?php

namespace App\Http\Controllers\school;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Exceptions;
use Whoops\Handler;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Controller;
use App\Models\First_parent;
use App\Models\Second_parent;
use App\Models\Emergency_contact;
use App\Models\User;
use App\Models\Admission;


class First_parentController extends Controller
{
   public function store(Request $parent)
    {
      $valiDationArray =  Validator::make($parent->all(), [
            
            'first_name_f'  => ['required', 'string'],
            'last_name_f'   => ['required', 'string'],
            'phone_f'       => ['required','numeric','digits:10'],
            'phone_e'  => ['required', 'numeric', 'digits:10'],
            'email'  => ['required', 'email', 'unique:users'],
          ]); 
          if($parent->passport_photo_f)
      {
        $valiDationArray["passport_photo_f"]='required|file';
      }
      if($parent->national_id_f)
      {
        $valiDationArray["national_id_f"]='required|file';
      }
      if($parent->passport_photo_s)
      {
        $valiDationArray["passport_photo_s"]='required|file';
      }
      if($parent->national_id_s)
      {
        $valiDationArray["national_id_s"]='required|file';
      }
      $validator =  Validator::make($parent->all(),$valiDationArray); 
       if ($validator->fails()) {
           return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
       }
       $p=$parent->all();
       $id=$p['p_id'];
       DB::enableQueryLog();
        $parent1 = First_parent::where('parent1_id ',$id)->first(); 
        
        $parent2 = Second_parent::where("parent1_id",$id)->first();
     
        if($parent->file('passport_photo_f')){
            $passport_photo_f = $parent->file('passport_photo_f');
            $imgName = time() . '.' . pathinfo($passport_photo_f->getClientOriginalName(), PATHINFO_EXTENSION);
            Storage::disk('public_uploads')->put('/parent-photo/' . $imgName, file_get_contents($passport_photo_f));
            $passport_photo_f=config('app.url').'/public/uploads/parent-photo/' . $imgName;
            $parent1->passport_photo_f=$passport_photo_f;
            }
            if($parent->file('national_id_f')){
              $national_id_f= $parent->file('national_id_f');
              $cerName = time() . '.' . pathinfo($national_id_f->getClientOriginalName(), PATHINFO_EXTENSION);
              Storage::disk('public_uploads')->put('/parent-national-id/' . $cerName, file_get_contents($national_id_f));
              $national_id_f=config('app.url').'/public/uploads/parent-national-id/' . $cerName;
              $parent1->national_id_f=$national_id_f;
              }
              if($parent->file('passport_photo_s')){
                $passport_photo_s = $parent->file('passport_photo_s');
                $imgName = time() . '.' . pathinfo($passport_photo_s->getClientOriginalName(), PATHINFO_EXTENSION);
                Storage::disk('public_uploads')->put('/parent-photo/' . $imgName, file_get_contents($passport_photo_s));
                $passport_photo_s=config('app.url').'/public/uploads/parent-photo/' . $imgName;
                $parent2->passport_photo_s=$passport_photo_s;
                }
                if($parent->file('national_id_s')){
                  $national_id_s= $parent->file('national_id_s');
                  $cerName = time() . '.' . pathinfo($national_id_s->getClientOriginalName(), PATHINFO_EXTENSION);
                  Storage::disk('public_uploads')->put('/parent-national-id/' . $cerName, file_get_contents($national_id_s));
                  $national_id_s=config('app.url').'/public/uploads/parent-national-id/' . $cerName;
                  $parent2->national_id_s=$national_id_s;
                  }
       
        $p=$parent->all();
        $id=$p['admission_id'];
        DB::enableQueryLog();
         $parent1 = First_parent::where('admission_id',$id)->first(); 
         $parent1->title_f=$parent->title_f;
         $parent1->relation_f=$parent->relation_f;
         $parent1->first_name_f=$parent->first_name_f;
         $parent1->middle_name_f=$parent->middle_name_f;
         $parent1->last_name_f=$parent->last_name_f;
         $parent1->phone_f=$parent->phone_f;
         $parent1->email_f=$parent->email;
         $parent1->id_passport_f=$parent->id_passport_f;
         $parent1->occupation_f=$parent->occupation_f;
         $parent1->address_f=$parent->address_f;
         $parent1->postal_code_f=$parent->postal_code_f;
         $parent1->passport_photo_f=$parent->passport_photo_f;
         $parent1->national_id_f=$parent->national_id_f;
         $parent1->save();

        $parent2->admission_id=$id;
        $parent2->title_s=$parent->title_s;
        $parent2->relation_s=$parent->relation_s;
        $parent2->first_name_s=$parent->first_name_s;
        $parent2->middle_name_s=$parent->middle_name_s;
        $parent2->last_name_s=$parent->last_name_s;
        $parent2->phone_s=$parent->phone_s;
        $parent2->email_s=$parent->email_s;
        $parent2->id_passport_s=$parent->id_passport_s;
        $parent2->occupation_s=$parent->occupation_s;
        $parent2->address_s=$parent->address_s;
        $parent2->postal_code_s=$parent->postal_code_s;
        // $parent2->passport_photo_s=$passport_photo_s;
        // $parent2->national_id_s=$national_id_s;
         $parent2->save();

     $emergencycontact = Emergency_contact::where("parent",$id)->first();
         $emergencycontact->admission_id=$id;
         $emergencycontact->first_name_e=$parent->first_name_e;
          $emergencycontact->middle_name_e=$parent->middle_name_e;
          $emergencycontact->last_name_e=$parent->last_name_e;
         $emergencycontact->relation_e=$parent->relation_e;
         $emergencycontact->phone_e=$parent->phone_e;
         $emergencycontact->email_e=$parent->email_e;
         $emergencycontact->id_no_e=$parent->id_no;
         $emergencycontact->address_e=$parent->address_e;
         $emergencycontact->info_provided_by=$parent->info_provided_by;
         
       
        if($emergencycontact->save()){
                  return response()->json([
                 'message'  => 'parent_details saved successfully',
                 'First_parent'  => $parent1,
                 'Second_parent' => $parent2, 
                 'Emergency_contact'=>$emergencycontact,
                
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }



    }
public function show(request $request)
    { 
             $id=$request->admission_id;
             $parent = admission::
             join('first_parent','admission.admission_id','=','first_parent.admission_id')
             ->join('second_parent','admission.admission_id','=','second_parent.admission_id')
             ->join('emergency_contact','admission.admission_id','=','emergency_contact.admission_id')
             ->where('admission.admission_id',$id)->first();
             if(!empty($parent)){
                    return response()->json([
                        'admission_id'=>$id,
                    'First_parent'  => $parent ,        
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
        $First_parent = First_parent::
        join('second_parent','first_parent.admission_id','=','second_parent.admission_id')
        ->select(DB::raw("CONCAT(first_parent.first_name_f,' ',first_parent.middle_name_f,' ',first_parent.last_name_f) as parent"),
        'passport_photo_f','phone_f','occupation_f','address_f','email_f',DB::raw("CONCAT(second_parent.first_name_s,' ',second_parent.middle_name_s,' ',second_parent.last_name_s) as second_parent"),
        'status','second_parent.admission_id')->get();
        return response()->json(['status' => 'Success', 'data' => $First_parent]);
    }


public function update(Request $parent)

   {
    $valiDationArray =  Validator::make($parent->all(), [
            
        'first_name_f'  => ['required', 'string'],
        'last_name_f'   => ['required', 'string'],
        'phone_f'       => ['required','numeric','digits:10'],
        'phone_e'  => ['required', 'numeric', 'digits:10'],
        'email'  => ['required', 'email', 'unique:users,email,'.$parent->parent1_id],
      ]); 


      if($parent->passport_photo_f)
      {
        $valiDationArray["passport_photo_f"]='required|file';
      }
      if($parent->national_id_f)
      {
        $valiDationArray["national_id_f"]='required|file';
      }
      if($parent->passport_photo_s)
      {
        $valiDationArray["passport_photo_s"]='required|file';
      }
      if($parent->national_id_s)
      {
        $valiDationArray["national_id_s"]='required|file';
      }
      $validator =  Validator::make($parent->all(),$valiDationArray); 
       if ($validator->fails()) {
           return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
       }
       $p=$parent->all();
        $id=$p['p_id'];
        DB::enableQueryLog();
        $parent1 = First_parent::where('parent1_id',$id)->first();

        $parent2 = Second_parent::where("parent1_id",$id)->first();
 
        if($parent->file('passport_photo_f')){
            $passport_photo_f = $parent->file('passport_photo_f');
            $imgName = time() . '.' . pathinfo($passport_photo_f->getClientOriginalName(), PATHINFO_EXTENSION);
            Storage::disk('public_uploads')->put('/parent-photo/' . $imgName, file_get_contents($passport_photo_f));
            $passport_photo_f=config('app.url').'/public/uploads/parent-photo/' . $imgName;
            $parent1->passport_photo_f=$passport_photo_f;
            }
            if($parent->file('national_id_f')){
              $national_id_f= $parent->file('national_id_f');
              $cerName = time() . '.' . pathinfo($national_id_f->getClientOriginalName(), PATHINFO_EXTENSION);
              Storage::disk('public_uploads')->put('/parent-national-id/' . $cerName, file_get_contents($national_id_f));
              $national_id_f=config('app.url').'/public/uploadsparent-national-id/' . $cerName;
              $parent1->national_id_f=$national_id_f;
              }
              if($parent->file('passport_photo_s')){
                $passport_photo_s = $parent->file('passport_photo_s');
                $imgName = time() . '.' . pathinfo($passport_photo_s->getClientOriginalName(), PATHINFO_EXTENSION);
                Storage::disk('public_uploads')->put('/parent-photo/' . $imgName, file_get_contents($passport_photo_s));
                $passport_photo_s=config('app.url').'/public/uploads/parent-photo/' . $imgName;
                $parent2->passport_photo_s=$passport_photo_s;
                }
                if($parent->file('national_id_s')){
                  $national_id_s= $parent->file('national_id_s');
                  $cerName = time() . '.' . pathinfo($national_id_s->getClientOriginalName(), PATHINFO_EXTENSION);
                  Storage::disk('public_uploads')->put('/parent-national-id/' . $cerName, file_get_contents($national_id_s));
                  $national_id_s=config('app.url').'/public/uploads/parent-national-id/' . $cerName;
                  $parent2->national_id_s=$national_id_s;
                  }
       
        $parent1->title_f=$parent->title_f;
        $parent1->relation_f=$parent->relation_f;
        $parent1->first_name_f=$parent->first_name_f;
        $parent1->middle_name_f=$parent->middle_name_f;
        $parent1->last_name_f=$parent->last_name_f;
        $parent1->phone_f=$parent->phone_f;
        $parent1->email_f=$parent->email;
        $parent1->id_passport_f=$parent->id_passport_f;
        $parent1->occupation_f=$parent->occupation_f;
        $parent1->address_f=$parent->address_f;
        $parent1->postal_code_f=$parent->postal_code_f;
        // $parent1->passport_photo_f=$passport_photo_f;
        // $parent1->national_id_f=$national_id_f;
         $parent1->save();
        
 

        $parent2->admission_id=$id;
        $parent2->title_s=$parent->title_s;
        $parent2->relation_s=$parent->relation_s;
        $parent2->first_name_s=$parent->first_name_s;
        $parent2->middle_name_s=$parent->middle_name_s;
        $parent2->last_name_s=$parent->last_name_s;
        $parent2->phone_s=$parent->phone_s;
        $parent2->email_s=$parent->email_s;
        $parent2->id_passport_s=$parent->id_passport_s;
        $parent2->occupation_s=$parent->occupation_s;
        $parent2->address_s=$parent->address_s;
        $parent2->postal_code_s=$parent->postal_code_s;
        $parent2->passport_photo_s=$passport_photo_s;
        $parent2->national_id_s=$national_id_s;
         $parent2->save();

     $emergencycontact = Emergency_contact::where("parent",$id)->first();
         $emergencycontact->admission_id=$id;
         $emergencycontact->first_name_e=$parent->first_name_e;
          $emergencycontact->middle_name_e=$parent->middle_name_e;
          $emergencycontact->last_name_e=$parent->last_name_e;
         $emergencycontact->relation_e=$parent->relation_e;
         $emergencycontact->phone_e=$parent->phone_e;
         $emergencycontact->email_e=$parent->email_e;
         $emergencycontact->id_no_e=$parent->id_no;
         $emergencycontact->address_e=$parent->address_e;
         $emergencycontact->info_provided_by=$parent->info_provided_by;

        if($emergencycontact->save()){
                 return response()->json([
                 'message'  => 'parent_details saved successfully', 
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
        $First_parent = First_parent::find($request->parent1_id );
        if(!empty($First_parent))

                {
                  if($First_parent->delete()){
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
    public function profile(request $request)
    {
        $id=$request->admission_id;
        $student=Admission::select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),'class','admission_id','admission_no')->where('admission_id',$id)->first();
        $fparent=First_parent::select(DB::raw("CONCAT(first_name_f,' ',middle_name_f,' ',last_name_f) as full_name"),'relation_f','phone_f','email_f','occupation_f','address_f','id_passport_f')->where('admission_id',$id)->first();
        $sparent=Second_parent::select(DB::raw("CONCAT(first_name_s,' ',middle_name_s,' ',last_name_s) as full_name"),'relation_s','phone_s','email_s','occupation_s','address_s','id_passport_s')->where('admission_id',$id)->first();
       $econtact=Emergency_contact::select(DB::raw("CONCAT(first_name_e,' ',middle_name_e,' ',last_name_e) as full_name"),'id_no_e','relation_e','phone_e','email_e','address_e','info_provided_by')->where('admission_id',$id)->first();
       if(!empty($student))
       {
       return response()->json(['message'=>'sucess', 
                                  'student'=>$student,
                                  'first_parent'=>$fparent,
                                  'second_parent'=>$sparent,
                                  'emergency_contact'=>$econtact,
                                                       ]);
       }else{
        return response()->json(['message'=>'no data found', 
       
                             ]);

       }
    }
}