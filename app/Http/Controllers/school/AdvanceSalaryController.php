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
use App\Models\Advance_salary;

class AdvanceSalaryController extends Controller
{
    public function store(Request $Advance_salary)
    {
       $salary=$Advance_salary->salary;
        $errors=[];
        foreach($salary as $g)
        {
          if ($g['date']=='') 
          {
           return response()->json(apiResponseHandler([],'The date field is required',400), 400);
          }
          if ($g['employee']=='') 
          {
           return response()->json(apiResponseHandler([],'The employee field is required',400), 400);
          }
           if ($g['amount']=='') 
          {
           return response()->json(apiResponseHandler([],'The amount field is required',400), 400);
          }
           if ($g['comment']=='') 
          {
           return response()->json(apiResponseHandler([],'The comment field is required',400), 400);
          }
           
        $advanceSalary = new Advance_salary(array(
          'date'   =>$g['date'],
          'employee'=>$g['employee'],
          'amount'=>$g['amount'],  
          'comment'   =>$g['comment'],
         ));
          if(!$advanceSalary->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'advanceSalary saved successfully',
                  ]);
              }
              else 
              {
                  return response()->json([
                   'message'  => 'failed',
                   'errors'=>$errors
                 ]);
               }
   }
   public function index()
    {
        $Advance_salary = Advance_salary::all();
        return response()->json(['status' => 'Success', 'data' => $Advance_salary]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'date' => ['required'],
            'employee' => ['required', 'string'],
            'amount'    => ['required', 'string'],
            'comment'  => ['required', 'string'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Advance_salary = Advance_salary::find($request->id);
       $Advance_salary->date= $request->date;
       $Advance_salary->employee= $request->employee;
       $Advance_salary->amount= $request->amount;
       $Advance_salary->comment= $request->comment;
      
       
        if($Advance_salary->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Advance_salary
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Advance_salary = Advance_salary::find($request->id);
        if(!empty($Advance_salary))

                {
                  if($Advance_salary->delete()){
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
