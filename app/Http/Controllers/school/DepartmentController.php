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
use App\Models\Department;
use App\Models\Settings;

class DepartmentController extends Controller
{
    public function store(Request $Department)
    {
    	 $validator =  Validator::make($Department->all(), [
    	 	'name' => ['required'],
    	 	
        
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Department=Department::create([
        	'name'=>$Department->name,
        	'description'=>$Department->description,
        ]);
        $Department->save();
         $id=$Department->id;
         $settings=Settings::create([
            'group_name'=>'staff_department',
            'key_name'=>$Department->name,
            'key_value'=>$id,
            ]);
            $settings->save();
        if($Department->save()){
                  return response()->json([
                 'message'  => 'Department saved successfully',
                 'data'  => $Department 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Department = Department::find($request->id);
             if(!empty($Department)){
                    return response()->json([
                    'data'  => $Department      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
    }
    public function Department(request $request)
    { 
             $Department = Department::select('name')->get();
             if(!empty($Department)){
                    return response()->json([
                    'data'  => $Department      
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
        $Department = Department::all();
        return response()->json(['status' => 'Success', 'data' => $Department]);
    }


public function update(Request $request)

   {
   	
    $Department = Department::find($request->id);
       
       $Department->name= $request->name;
       $Department->description= $request->description;
       $settings=Settings::where('group_name','=','staff_department')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
        if($Department->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Department
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Department = Department::find($request->id);
        if(!empty($Department))

                {
                  if($Department->delete()){
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
