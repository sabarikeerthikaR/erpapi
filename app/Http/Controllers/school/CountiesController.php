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
use App\Models\Counties;
use App\Models\Settings;

class CountiesController extends Controller
{
    public function store(Request $Counties)
    {
    	 $validator =  Validator::make($Counties->all(), [

            'name' => ['required', 'string'],
           
        ]); 
           if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Counties=Counties::create([
        'name'          =>$Counties->name,
        'description'         =>$Counties->description,
         ]);
         $Counties->save();
         $id=$Counties->id;
         $settings=Settings::create([
            'group_name'=>'county',
            'key_name'=>$Counties->name,
            'key_value'=>$id,
            ]);
            $settings->save();
        if($Counties->save()){
                  return response()->json([
                 'message'  => 'Counties saved successfully',
                 'data'  => $Counties 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Counties = Counties::find($request->id);
             if(!empty($Counties)){
                    return response()->json([
                    'data'  => $Counties      
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
        $Counties = Counties::all();
        return response()->json(['knec_code' => 'Success', 'data' => $Counties]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
   	 	
           'name' => ['required', 'string'],
        
        ]); 
       
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Counties = Counties::find($request->id);
       
    
       $Counties->name= $request->name;
       $Counties->description= $request->description;
       $settings=Settings::where('group_name','=','county')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
      
       
        if($Counties->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Counties
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Counties = Counties::find($request->id);
        $settings=Settings::where('group_name','=','county')->where('key_value',$request->id)->first();
        $settings->key_name=NULL;
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($Counties))

                {
                  if($Counties->delete()){
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
