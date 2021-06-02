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
use App\Models\AddActivities;
use App\Models\Settings;

class AddActivitiesController extends Controller
{
    public function store(Request $AddActivities)
    {
      $validator =  Validator::make($AddActivities->all(), [
            'name' => ['required', 'string'],
            
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $AddActivities=AddActivities::create([

        'name'  =>$AddActivities->name ,
        
         ]); 
         $settings=Settings::create([
            'group_name'=>'activity',
            'key_name'=>$AddActivities->name,
            'key_value'=>$AddActivities->id,
            ]);
            $settings->save();
        if($AddActivities->save()){
                  return response()->json([
                 'message'  => 'AddActivities saved successfully',
                 'data'  => $AddActivities 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $AddActivities = AddActivities::find($request->id);
             if(!empty($AddActivities)){
                    return response()->json([
                    'data'  => $AddActivities      
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
        $AddActivities = AddActivities::all();
        return response()->json(['status' => 'Success', 'data' => $AddActivities]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
         'name' => ['required', 'string'],
           
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $AddActivities = AddActivities::find($request->id);
        $AddActivities->name = $request->name ;
        $settings=Settings::where('group_name','=','activity')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
        if($AddActivities->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $AddActivities
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $AddActivities = AddActivities::find($request->id);
        $settings=Settings::where('group_name','=','activity')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
       
        if(!empty($AddActivities))

                {
                  if($AddActivities->delete()){
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
