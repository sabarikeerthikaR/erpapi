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
use App\Models\SalaryMethod;

class SalaryMethodController extends Controller
{
    public function store(Request $SalaryMethod)
    {
      $validator =  Validator::make($SalaryMethod->all(), [
            'name' => ['required', 'string'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $SalaryMethod=SalaryMethod::create([

        'name'  =>$SalaryMethod->name,
       
         ]);
        if($SalaryMethod->save()){
                  return response()->json([
                 'message'  => 'SalaryMethod saved successfully',
                 'data'  => $SalaryMethod 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $SalaryMethod = SalaryMethod::find($request->id);
             if(!empty($SalaryMethod)){
                    return response()->json([
                    'data'  => $SalaryMethod      
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
        $SalaryMethod = SalaryMethod::all();
        return response()->json(['status' => 'Success', 'data' => $SalaryMethod]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'name' => ['required', 'string'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $SalaryMethod = SalaryMethod::find($request->id);
       $SalaryMethod->name= $request->name;
       
        if($SalaryMethod->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $SalaryMethod
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $SalaryMethod = SalaryMethod::find($request->id);
        if(!empty($SalaryMethod))

                {
                  if($SalaryMethod->delete()){
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
