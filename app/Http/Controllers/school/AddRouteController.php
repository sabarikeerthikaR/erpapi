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
use App\Models\AddRoute;
use App\Models\Settings;

class AddRouteController extends Controller
{
   public function store(Request $AddRoute)
    {
      $validator =  Validator::make($AddRoute->all(), 
          [ 
            'name'    => ['required', 'string'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $AddRoute=AddRoute::create([
        
        'name'  =>$AddRoute->name, 
         ]);
         $settings=Settings::create([
            'group_name'=>'route',
            'key_name'=>$AddRoute->name,
            'key_value'=>$AddRoute->id,
            ]);
            $settings->save();
        if($AddRoute->save()){
                  return response()->json([
                 'message'  => 'AddRoute saved successfully',
                 'data'  => $AddRoute 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $AddRoute = AddRoute::find($request->id);
             if(!empty($AddRoute)){
                    return response()->json([
                    'data'  => $AddRoute      
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
        $AddRoute = AddRoute::all();
        return response()->json(['status' => 'Success', 'data' => $AddRoute]);
    }


public function update(Request $request)

   {
    
    $AddRoute = AddRoute::find($request->id);
        $AddRoute->name= $request->name;
        $settings=Settings::where('group_name','=','route')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
        if($AddRoute->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $AddRoute
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $AddRoute = AddRoute::find($request->id);
        $settings=Settings::where('group_name','=','route')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->key_name=NULL;
        $settings->save();
        if(!empty($AddRoute))

                {
                  if($AddRoute->delete()){
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
