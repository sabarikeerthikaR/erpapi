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
use Illuminate\Support\Facades\Auth;


class ProcessSalaryController extends Controller
{
    public function store(Request $Process_salary)
    {

        $salary=$Process_salary->salary;
        $errors=[];
        foreach($salary as $g)
        {
          if ($Process_salary->processing_date=='') 
          {
           return response()->json(apiResponseHandler([],'The processing_date field is required',400), 400);
          }
         
        
        $Process_salary = new Process_salary(array(
          'sal_month'  =>$Process_salary->sal_month,
            'year'  =>$Process_salary->year,
            'processing_date'    =>$Process_salary->processing_date,
          'comment'  =>$Process_salary->comment,
          'employee'=>$g['key_value'],
          'created_by'=>auth::user()->id
         ));
          if(!$Process_salary->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'Process_salary saved successfully',
              'data'  => $Process_salary,
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
        $Process_salary = Process_salary::leftjoin('month','process_salary.sal_month','=','month.id')
                                         ->leftjoin('users','process_salary.created_by','=','users.id')
                                         ->select('processing_date','month.name as month',
                                                  db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as created_by"),
                                                  db::raw('count(employee) as employee'))
                                         ->groupBy('processing_date')
                                         ->get();
        return response()->json(['status' => 'Success', 'data' => $Process_salary]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'employee' => ['required'],
            'processing_date'    => ['required'],
           
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Process_salary = Process_salary::find($request->id);
       $Process_salary->sal_month= $request->sal_month;
       $Process_salary->year= $request->year;
       $Process_salary->processing_date= $request->processing_date;
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
        $Process_salary = Process_salary::where('processing_date',$request->processing_date)->get();
        foreach($Process_salary as $r)
        {
            $r->delete();
        }
        if(!empty($Process_salary))

                {
                  return response()->json([
                  'message'  => 'successfully deleted'
                   ]);
           }
           else
           {
           return response()->json([
                 'message'  => 'No data found in this id'  
                 ]);
            }
    }
    public function processSalView(request $request)
    {
        $getData=Process_salary::where('processing_date',$request->processing_date)
                  ->leftjoin('add_emp_sal','process_salary.employee','=','add_emp_sal.id')
                  ->leftjoin('staff','process_salary.employee','=','staff.employee_id')
                  ->leftjoin('setings','add_emp_sal.bank_name','=','setings.s_d')
                  ->select('processing_date','basic_salary','deductions','allowances',
               'setings.key_name as bank',
                db::raw("CONCAT(first_name,' ',COALESCE('middle_name'),' ',last_name)as employee")
                          ,'employee_id')->groupBy('employee')
                             ->get();
                              return response()->json(['status' => 'Success', 'data' => $getData]);
    }
     public function SalEmployeeView(request $request)
    {
        $date=  \Carbon\Carbon::parse(date('y-m-d'))->isoFormat('MMM Do YYYY');
    $receipt='#'.randomFunctionNumber(6);
        $Process_salarySingle=Process_salary::where('process_salary.employee',$request->employee_id)
                       ->leftjoin('staff','process_salary.employee','=','staff.employee_id')
                       ->select(db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as name"),
                         'employee_id', DB::raw("DATE_FORMAT(staff.created_at, '%Y-%m-%d') as date"))->first();
        $Process_salaryget=Process_salary::where('process_salary.employee',$request->employee_id)
        ->leftjoin('add_emp_sal','process_salary.employee','=','add_emp_sal.id')
         ->select('processing_date','basic_salary','deductions','allowances',
                    DB::raw('basic_salary + (allowances - deductions)as paid') )
                      ->groupBy('process_salary.id')
                       ->get();
       $total=Process_salary::where('process_salary.employee',$request->employee_id)
                   ->leftjoin('add_emp_sal','process_salary.employee','=','add_emp_sal.id')
                       ->select(DB::raw('SUM(basic_salary - deductions + allowances) as total'))
                       ->groupBy('process_salary.employee')
                       ->first();

         return response()->json(['status' => 'Success', 'data' => ['name'=>$Process_salarySingle->name,
                                                                     'emp_date'=>$Process_salarySingle->date,
                                                                     'employee_id'=>$Process_salarySingle->employee_id,
                                                                    'date' => $date,
                                                                   'receipt' => $receipt,
                                                                   ],
                                                          
                                                          'Process_salary'=>$Process_salaryget,
                                                          'total'=>$total->total
                                                          ]);
        

    }
}
