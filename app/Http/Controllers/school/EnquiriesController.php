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
use App\Models\Enquiries;
use App\Models\AddStream;
use App\Models\Settings;
use App\Models\EnquiriesOption;

class EnquiriesController extends Controller
{
    public function store(Request $Enquiries)
    {
      $validator =  Validator::make($Enquiries->all(), [
            'date' => ['required'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'dob' => ['required'],
            'gender' => ['required'],
            'phone' => ['required', 'numeric','digits:10'],
             'class' => ['required'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Enquiries=Enquiries::create([

        'date'  =>$Enquiries->date ,
        'first_name'  =>$Enquiries->first_name ,
        'last_name'  =>$Enquiries->last_name ,
        'dob'  =>$Enquiries->dob ,
        'gender'  =>$Enquiries->gender ,
         'phone'  =>$Enquiries->phone ,
          'email'  =>$Enquiries->email ,
          'class'  =>$Enquiries->class ,
         'about_us'  =>$Enquiries->about_us ,
          'description'  =>$Enquiries->description ,

        
         ]); 
        if($Enquiries->save()){
                  return response()->json([
                 'message'  => 'Enquiries saved successfully',
                 'data'  => $Enquiries 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Enquiries = Enquiries::find($request->id);
             if(!empty($Enquiries)){
                    return response()->json([
                    'data'  => $Enquiries      
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
        $Enquiries = Enquiries::leftjoin('add_stream','enquiries.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('enquiries_option','enquiries.about_us','=','enquiries_option.id')
        ->leftjoin('setings','enquiries.gender','=','setings.s_d')
        ->select('enquiries.date',db::raw("CONCAT(first_name,' ',last_name) as name"),
        'setings.key_name as gender','dob','enquiries.status','enquiries_option.name as know_us',
        'std_class.name as class','class_stream.name as stream','phone','email',
        'enquiries.id')->get();
        return response()->json(['status' => 'Success', 'data' => $Enquiries]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'date' => ['required'],
        'first_name' => ['required'],
        'last_name' => ['required'],
        'dob' => ['required'],
        'gender' => ['required'],
        'phone' => ['required', 'numeric','digits:10'],
         'class' => ['required'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Enquiries = Enquiries::find($request->id);
        $Enquiries->date = $request->date ;
        $Enquiries->first_name = $request->first_name ;
        $Enquiries->last_name = $request->last_name ;
        $Enquiries->dob = $request->dob;
        $Enquiries->gender = $request->gender ;
        $Enquiries->phone = $request->phone ;
        $Enquiries->email = $request->email ;
         $Enquiries->class = $request->class ;
        $Enquiries->about_us = $request->about_us ;
        $Enquiries->description = $request->description ;
       
        if($Enquiries->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Enquiries
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Enquiries = Enquiries::find($request->id);
        if(!empty($Enquiries))

                {
                  if($Enquiries->delete()){
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
