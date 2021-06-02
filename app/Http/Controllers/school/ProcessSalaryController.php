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
use App\Models\Process_salary;

class ProcessSalaryController extends Controller
{
    public function store(Request $Process_salary)
    {
      $validator =  Validator::make($Process_salary->all(), [
            'sal_month' => ['required', 'string'],
            'year' => ['required', 'string'],
            'processing_date'    => ['required'],
            'process_type'  => ['required', 'string'],
             'comment' => ['required', 'string'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Process_salary=Process_salary::create([

        'sal_month'  =>$Process_salary->sal_month,
        'year'  =>$Process_salary->year,
        'processing_date'    =>$Process_salary->processing_date,
        'process_type' =>$Process_salary->process_type,
        'employee' =>$Process_salary->employee,
        'comment'  =>$Process_salary->comment,

         ]);
        if($Process_salary->save()){
                  return response()->json([
                 'message'  => 'Process_salary saved successfully',
                 'data'  => $Process_salary 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Process_salary = Process_salary::find($request->id);
             if(!empty($Process_salary)){
                    return response()->json([
                    'data'  => $Process_salary      
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
        $Process_salary = Process_salary::all();
        return response()->json(['status' => 'Success', 'data' => $Process_salary]);
    }


public function upyear(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'sal_month' => ['required', 'string'],
            'year' => ['required', 'string'],
            'processing_date'    => ['required'],
            'process_type'  => ['required', 'string'],
             'comment' => ['required', 'string'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Process_salary = Process_salary::find($request->id);
       $Process_salary->sal_month= $request->sal_month;
       $Process_salary->year= $request->year;
       $Process_salary->processing_date= $request->processing_date;
       $Process_salary->process_type= $request->process_type;
       $Process_salary->employee= $request->employee;
       $Process_salary->comment= $request->comment;
       
        if($Process_salary->save()){
            return response()->json([
                 'message'  => 'upyeard successfully',
                 'data'  => $Process_salary
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Process_salary = Process_salary::find($request->id);
        if(!empty($Process_salary))

                {
                  if($Process_salary->delete()){
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
