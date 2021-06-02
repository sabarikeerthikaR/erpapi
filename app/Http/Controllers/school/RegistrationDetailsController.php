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
use App\Models\RegistrationDetails;

class RegistrationDetailsController extends Controller
{
    public function store(Request $RegistrationDetails)
    {
    	 $validator =  Validator::make($RegistrationDetails->all(), [
    	 	'reg_no' => ['required','string'],
            'reg_date' => ['required'],
            'inst_category' => ['required', 'string'],
            'inst_cluster' => ['required', 'string'],
            'county' => ['required', 'string'],
            'sub_county' => ['required','string'],
            'ward' => ['required', 'string'],
            'inst_type' => ['required', 'string'],
            'edu_systm' => ['required', 'string'],
            'edu_level' => ['required', 'string'],
            'knec_code' => ['required', 'string'],
            'inst_accommodation' => ['required', 'string'],
            'scholars_gender' => ['required', 'string'],
            'locality' => ['required', 'string'],
            'kra_pin' => ['required', 'string'],

        ]); 
           if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $RegistrationDetails=RegistrationDetails::create([
        'reg_no'=>$RegistrationDetails->reg_no,
        'reg_date'    =>$RegistrationDetails->reg_date,
        'inst_category'          =>$RegistrationDetails->inst_category,
        'inst_cluster'         =>$RegistrationDetails->inst_cluster,
        'county'        =>$RegistrationDetails->county,
        'sub_county'        =>$RegistrationDetails->sub_county,
        'ward'        =>$RegistrationDetails->ward,
        'inst_type'        =>$RegistrationDetails->inst_type,
        'edu_systm'        =>$RegistrationDetails->edu_systm,
        'edu_level'        =>$RegistrationDetails->edu_level,
        'knec_code'        =>$RegistrationDetails->knec_code,
        'inst_accommodation'=>$RegistrationDetails->inst_accommodation,
        'scholars_gender' =>$RegistrationDetails->scholars_gender,
        'locality'        =>$RegistrationDetails->locality,
        'kra_pin'        =>$RegistrationDetails->kra_pin,
       
         ]);
        if($RegistrationDetails->save()){
                  return response()->json([
                 'message'  => 'RegistrationDetails saved successfully',
                 'data'  => $RegistrationDetails 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $RegistrationDetails = RegistrationDetails::find($request->id);
             if(!empty($RegistrationDetails)){
                    return response()->json([
                    'data'  => $RegistrationDetails      
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
        $RegistrationDetails = RegistrationDetails::all();
        return response()->json(['knec_code' => 'Success', 'data' => $RegistrationDetails]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
   	 'reg_no' => ['required','string'],
            'reg_date' => ['required'],
            'inst_category' => ['required', 'string'],
            'inst_cluster' => ['required', 'string'],
            'county' => ['required', 'string'],
            'sub_county' => ['required','string'],
            'ward' => ['required', 'string'],
            'inst_type' => ['required', 'string'],
            'edu_systm' => ['required', 'string'],
            'edu_level' => ['required', 'string'],
            'knec_code' => ['required', 'string'],
            'inst_accommodation' => ['required', 'string'],
            'scholars_gender' => ['required', 'string'],
            'locality' => ['required', 'string'],
            'kra_pin' => ['required', 'string'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $RegistrationDetails = RegistrationDetails::find($request->id);
       
       $RegistrationDetails->reg_date= $request->reg_date;
       $RegistrationDetails->inst_category= $request->inst_category;
       $RegistrationDetails->inst_cluster= $request->inst_cluster;
       $RegistrationDetails->county= $request->county;
       $RegistrationDetails->sub_county= $request->sub_county;
       $RegistrationDetails->ward= $request->ward;
       $RegistrationDetails->inst_type= $request->inst_type;
       $RegistrationDetails->edu_systm= $request->edu_systm;
       $RegistrationDetails->edu_level= $request->edu_level;
       $RegistrationDetails->knec_code= $request->knec_code;
       $RegistrationDetails->inst_accommodation= $request->inst_accommodation;
       $RegistrationDetails->scholars_gender= $request->scholars_gender;
       $RegistrationDetails->locality= $request->locality;
       $RegistrationDetails->kra_pin= $request->kra_pin;
      
        if($RegistrationDetails->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $RegistrationDetails
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $RegistrationDetails = RegistrationDetails::find($request->id);
        if(!empty($RegistrationDetails))

                {
                  if($RegistrationDetails->delete()){
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
