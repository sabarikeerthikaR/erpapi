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
use App\Models\Class_stream;
use App\Models\Settings;

class ClassStreamController extends Controller
{
     public function store(Request $Class_stream)
    {
     
        $Class_stream=Class_stream::create([
        
        'name'  =>$Class_stream->name,
         ]);
         $Class_stream->save();
         $id=$Class_stream->stream_id;
         $settings=Settings::create([
         'group_name'=>'stream',
         'key_name'=>$Class_stream->name,
         'key_value'=>$id,
         ]);
         $settings->save();
        if($Class_stream->save()){
                  return response()->json([
                 'message'  => 'Class_stream saved successfully',
                 'data'  => $Class_stream 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Class_stream = Class_stream::find($request->stream_id);
             if(!empty($Class_stream)){
                    return response()->json([
                    'data'  => $Class_stream      
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
        $Class_stream = Class_stream::all();
        return response()->json(['status' => 'Success', 'data' => $Class_stream]);
    }


public function update(Request $request)

   {
    
    $Class_stream = Class_stream::find($request->stream_id);
        $Class_stream->name= $request->name;
        $settings=Settings::where('group_name','=','stream')->where('key_value',$request->stream_id)->first();
             $settings->key_name= $request->name;
             $settings->save();
        if($Class_stream->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Class_stream
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Class_stream = Class_stream::find($request->stream_id);
        if(!empty($Class_stream))

                {
                  if($Class_stream->delete()){
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
