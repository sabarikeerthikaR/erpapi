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
use App\Models\ClassRooms;
use App\Models\Settings;

class ClassRoomsController extends Controller
{
     public function store(Request $ClassRooms)
    {
    	 $validator =  Validator::make($ClassRooms->all(), [

            'name' => ['required', 'string'],
            
        ]); 
           if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $ClassRooms=ClassRooms::create([
      
       
        'name'          =>$ClassRooms->name,
        'capacity'         =>$ClassRooms->capacity,
        'description'         =>$ClassRooms->description,   
         ]);
         $ClassRooms->save();
         $id=$ClassRooms->id;
         $settings=Settings::create([
            'group_name'=>'class_room',
            'key_name'=>$ClassRooms->name,
            'key_value'=>$id,
            ]);
            $settings->save();
        if($ClassRooms->save()){
                  return response()->json([
                 'message'  => 'ClassRooms saved successfully',
                 'data'  => $ClassRooms 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $ClassRooms = ClassRooms::find($request->id);
             if(!empty($ClassRooms)){
                    return response()->json([
                    'data'  => $ClassRooms      
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
        $ClassRooms = ClassRooms::all();
        return response()->json(['knec_code' => 'Success', 'data' => $ClassRooms]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
   	 	
           
            'name' => ['required', 'string'],
        
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $ClassRooms = ClassRooms::find($request->id);
       
    
       $ClassRooms->name= $request->name;
       $ClassRooms->capacity= $request->capacity;
        $ClassRooms->description= $request->description;
      
        $settings=Settings::where('group_name','=','class_room')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
       
        if($ClassRooms->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $ClassRooms
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $ClassRooms = ClassRooms::find($request->id);
        if(!empty($ClassRooms))

                {
                  if($ClassRooms->delete()){
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
