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
use App\Models\Certificate_type;
use App\Models\Settings;

class CertificateTypeController extends Controller
{
   public function store(Request $Certificate_type)
    {
      $validator =  Validator::make($Certificate_type->all(), [
            'name' => ['required', 'string'],
             
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Certificate_type=Certificate_type::create([

        'name'  =>$Certificate_type->name ,
        
         ]);
         $id=$Certificate_type->id;
         $settings=Settings::create([
            'group_name'=>'certificate_type',
            'key_name'=>$Certificate_type->name,
            'key_value'=>$id,
            ]);
            $settings->save();
        if($Certificate_type->save()){
                  return response()->json([
                 'message'  => 'Certificate_type saved successfully',
                 'data'  => $Certificate_type 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Certificate_type = Certificate_type::find($request->id);
             if(!empty($Certificate_type)){
                    return response()->json([
                    'data'  => $Certificate_type      
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
        $Certificate_type = Certificate_type::all();
        return response()->json(['status' => 'Success', 'data' => $Certificate_type]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
            'name' => ['required', 'string'],
         
          
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Certificate_type = Certificate_type::find($request->id);
        $Certificate_type->name = $request->name ;
        $settings=Settings::where('group_name','=','certificate_type')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
      
        if($Certificate_type->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Certificate_type
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Certificate_type = Certificate_type::find($request->id);
        $settings=Settings::where('group_name','=','certificate_type')->where('key_value',$request->id)->first();
        $settings->key_name=NULL;
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($Certificate_type))

                {
                  if($Certificate_type->delete()){
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
