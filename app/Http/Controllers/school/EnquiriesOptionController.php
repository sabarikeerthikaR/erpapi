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
use App\Models\EnquiriesOption;
use App\Models\Settings;

class EnquiriesOptionController extends Controller
{
    public function store(Request $EnquiriesOption)
    {
      $validator =  Validator::make($EnquiriesOption->all(), [
            'name' => ['required'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $EnquiriesOption=EnquiriesOption::create([

        'name'  =>$EnquiriesOption->name,
       
         ]);
         $settings=Settings::create([
            'group_name'=>'enquiries_option',
            'key_name'=>$EnquiriesOption->name,
            'key_value'=>$EnquiriesOption->id,
            ]);
            $settings->save();
        if($EnquiriesOption->save()){
                  return response()->json([
                 'message'  => 'EnquiriesOption saved successfully',
                 'data'  => $EnquiriesOption 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $EnquiriesOption = EnquiriesOption::find($request->id);
             if(!empty($EnquiriesOption)){
                    return response()->json([
                    'data'  => $EnquiriesOption      
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
        $EnquiriesOption = EnquiriesOption::all();
        return response()->json(['status' => 'Success', 'data' => $EnquiriesOption]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'name' => ['required'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $EnquiriesOption = EnquiriesOption::find($request->id);
       $EnquiriesOption->name= $request->name;
       $settings=Settings::where('group_name','=','enquiries_option')->where('key_value',$request->id)->first();
       $settings->key_name= $request->name;
       $settings->save();
       
        if($EnquiriesOption->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $EnquiriesOption
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $EnquiriesOption = EnquiriesOption::find($request->id);
        $settings=Settings::where('group_name','=','enquiries_option')->where('key_value',$request->id)->first();
        $settings->key_name=NULL;
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        
        if(!empty($EnquiriesOption))

                {
                  if($EnquiriesOption->delete()){
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
