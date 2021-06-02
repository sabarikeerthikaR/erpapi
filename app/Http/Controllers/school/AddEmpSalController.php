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
use App\Models\Add_emp_sal;

class AddEmpSalController extends Controller
{
    public function store(Request $Add_emp_sal)
    {
      $validator =  Validator::make($Add_emp_sal->all(), [
            'employee' => ['required', 'string'],
            'salary_method' => ['required', 'string'],
            'basic_salary'    => ['required', 'numeric'],
            'NHIF_Amount'  => ['required', 'numeric'],
             'NSSF_Amount' => ['required', 'numeric'],
            'deductions' => ['required', 'string'],
            'allowances'    => ['required', 'string'],
            'staff_with_student_deduction'  => ['required','string'],
             'bank_name' => ['required', 'string'],
            'acc_no' => ['required', 'numeric'],
            'NHIF_Number' => ['required', 'string'],
            'NSSF_Number' => ['required', 'string'],

            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Add_emp_sal=Add_emp_sal::create([

        'employee'  =>$Add_emp_sal->employee,
        'salary_method'  =>$Add_emp_sal->salary_method,
        'basic_salary'    =>$Add_emp_sal->basic_salary,
        'NHIF_Amount' =>$Add_emp_sal->NHIF_Amount,
        'NSSF_Amount'  =>$Add_emp_sal->NSSF_Amount,
        'deductions'   =>$Add_emp_sal->deductions,
        'allowances' =>$Add_emp_sal->allowances,
       'staff_with_student_deduction'  =>$Add_emp_sal->staff_with_student_deduction,
        'bank_name'    =>$Add_emp_sal->bank_name,
        'acc_no'   =>$Add_emp_sal->acc_no,
        'NHIF_Number'  =>$Add_emp_sal->NHIF_Number,
        'NSSF_Number'    =>$Add_emp_sal->NSSF_Number,

       
       
         ]);
        if($Add_emp_sal->save()){
                  return response()->json([
                 'message'  => 'Add_emp_sal saved successfully',
                 'data'  => $Add_emp_sal 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Add_emp_sal = Add_emp_sal::find($request->id);
             if(!empty($Add_emp_sal)){
                    return response()->json([
                    'data'  => $Add_emp_sal      
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
        $Add_emp_sal = Add_emp_sal::all();
        return response()->json(['status' => 'Success', 'data' => $Add_emp_sal]);
    }


public function upsalary_method(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'employee' => ['required', 'string'],
            'salary_method' => ['required', 'string'],
            'basic_salary'    => ['required', 'numeric'],
            'NHIF_Amount'  => ['required', 'numeric'],
             'NSSF_Amount' => ['required', 'numeric'],
            'deductions' => ['required', 'string'],
            'allowances'    => ['required', 'string'],
            'staff_with_student_deduction'  => ['required','string'],
             'bank_name' => ['required', 'string'],
            'acc_no' => ['required', 'numeric'],
            'NHIF_Number' => ['required', 'string'],
            'NSSF_Number' => ['required', 'string'],

        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Add_emp_sal = Add_emp_sal::find($request->id);
       $Add_emp_sal->employee= $request->employee;
       $Add_emp_sal->salary_method= $request->salary_method;
       $Add_emp_sal->basic_salary= $request->basic_salary;
       $Add_emp_sal->NHIF_Amount= $request->NHIF_Amount;
       $Add_emp_sal->NSSF_Amount= $request->NSSF_Amount;
       $Add_emp_sal->deductions= $request->deductions;
       $Add_emp_sal->allowances= $request->allowances;
       $Add_emp_sal->staff_with_student_deduction= $request->staff_with_student_deduction;
       $Add_emp_sal->bank_name= $request->bank_name;
       $Add_emp_sal->acc_no= $request->acc_no;
        $Add_emp_sal->NHIF_Number= $request->NHIF_Number;
       $Add_emp_sal->NSSF_Number= $request->NSSF_Number;
      
        if($Add_emp_sal->save()){
            return response()->json([
                 'message'  => 'upsalary_methodd successfully',
                 'data'  => $Add_emp_sal
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Add_emp_sal = Add_emp_sal::find($request->id);
        if(!empty($Add_emp_sal))

                {
                  if($Add_emp_sal->delete()){
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
    public function payrollConfig(request $request)
    {

    }
    public function payrollConfigedit(request $request)
    {
        
    }
    public function payrollConfigdelete(request $request)
    {
        
    }
    public function payrollConfigselect(request $request)
    {
        
    }
}
