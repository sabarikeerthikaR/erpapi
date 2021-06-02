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
use App\Models\Group_staff;

class Group_staffController extends Controller
{
    public function store(Request $Group_staff)
    {
    	 $validator =  Validator::make($Group_staff->all(), [
    	 	'name' => ['required','string'],
        
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Group_staff=Group_staff::create([
        	'name'=>$Group_staff->name,
        ]);
        if($Group_staff->save()){
                  return response()->json([
                 'message'  => 'Group_staff saved successfully',
                 'data'  => $Group_staff 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Group_staff = Group_staff::find($request->employee_type);
             if(!empty($Group_staff)){
                    return response()->json([
                    'data'  => $Group_staff      
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
        $Group_staff = Group_staff::all();
        return response()->json(['status' => 'Success', 'data' => $Group_staff]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
   	 	'name' => ['required','string'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Group_staff = Group_staff::find($request->employee_type);
       
       $Group_staff->name= $request->name;
       
        if($Group_staff->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Group_staff
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Group_staff = Group_staff::find($request->employee_type);
        if(!empty($Group_staff))

                {
                  if($Group_staff->delete()){
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