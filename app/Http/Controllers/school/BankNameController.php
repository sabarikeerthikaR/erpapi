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
use App\Models\Bank_name;
use App\Models\Settings;

class BankNameController extends Controller
{
   public function store(Request $Bank_name)
    {
      $validator =  Validator::make($Bank_name->all(), [
            'name' => ['required', 'string'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Bank_name=Bank_name::create([

        'name'  =>$Bank_name->name,
       
         ]);
        $settings=Settings::create([
            'group_name'=>'bank_name',
            'key_name'=>$Bank_name->bank_name,
            'key_value'=>$Bank_name->id,
            ]);
            $settings->save();
        if($Bank_name->save()){
                  return response()->json([
                 'message'  => 'Bank_name saved successfully',
                 'data'  => $Bank_name 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Bank_name = Bank_name::find($request->id);
             if(!empty($Bank_name)){
                    return response()->json([
                    'data'  => $Bank_name      
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
        $Bank_name = Bank_name::all();
        return response()->json(['status' => 'Success', 'data' => $Bank_name]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'name' => ['required', 'string'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Bank_name = Bank_name::find($request->id);
       $Bank_name->name= $request->name;
       $settings=Settings::where('group_name','=','bank_name')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();  
       
        if($Bank_name->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Bank_name
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Bank_name = Bank_name::find($request->id);
        $settings=Settings::where('group_name','=','bank_name')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->key_name=NULL;
        $settings->save();  
        if(!empty($Bank_name))

                {
                  if($Bank_name->delete()){
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
