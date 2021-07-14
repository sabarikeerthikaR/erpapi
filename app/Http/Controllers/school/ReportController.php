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
use App\Models\Admission;
use App\Models\AddExcActivities;
use App\Models\Fee_pledge;
use App\Models\Fee_extras;
use App\Models\Fee_arrears;
use App\Models\Fee_waivers;
use App\Models\Exam;
use App\Models\Grading_system;
use App\Models\Process_salary;
use App\Models\Book_for_fund;
use App\Models\ExpenseDetails;
use App\Models\FeeExtrass;

class ReportController extends Controller
{
    public function StudentHistoryreport(request $request)
    {

    }
    public function AdmissionReport(request $request)
    {
        $admission=Admission::where('admission.class',$request->class)
                                           ->where('admission.year',$request->year)
                                           ->leftjoin('add_stream','admission.class','=','add_stream.id')
                                           ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
                                           ->leftjoin('std_class','add_stream.class','=',
                                           'std_class.class_id')
                                           ->leftjoin('student_house','admission.house','=',
                                           'student_house.house_id')
                                           ->leftjoin('first_parent','admission.parent','=',
                                           'first_parent.parent1_id')
                                           ->select(db::raw("CONCAT(admission.first_name,' ',COALESCE(middle_name,''),' ',last_name)as student_name"),
                                           db::raw("CONCAT(std_class.name,' ',class_stream.name)as class"),'admission_no','dob','student_house.name as house',
                                           db::raw("CONCAT(first_parent.first_name_f,' ',COALESCE(first_parent.middle_name_f,''),' ',first_parent.last_name_f)as parent"),'phone_f')->get();
                                           if(!empty($admission)){
                                            return response()->json([
                                                 'message'  => 'success',
                                                 'data'  => $admission,
                                                
                                            ]);
                                        }else {
                                            return response()->json([
                                                 'message'  => 'failed'
                                                 ]);
                                        }
        
    }
    public function ActivitiesReport(request $request)
    {
        $activities=AddExcActivities::leftjoin('admission','extra_curricular_activity.stud_name','=','admission.admission_id')
                    ->leftjoin('add_stream','admission.class','=','add_stream.id')
                    ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
                    ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
                    ->where('activity',$request->activity)  
                    ->where('admission.class',$request->class)
                    ->where('year',$request->year)
                    ->select(db::raw("concat(first_name,' ',COALESCE(middle_name,''),' ',last_name)as name"),
                             db::raw("CONCAT(std_class.name,' ',class_stream.name)as class"))
                             ->get();
                              if(!empty($activities)){
                                            return response()->json([
                                                 'message'  => 'success',
                                                 'data'  => $activities,
                                                
                                            ]);
                                        }else {
                                            return response()->json([
                                                 'message'  => 'failed'
                                                 ]);
                                        }        
        
    }
    public function feePaymentSummery(request $request)
    {
        
    }
    public function feeStatus(request $request)
    {
        
    }
    public function feeArrears(request $request)
    {
        
    }
    public function feeExtrassReport(request $request)
    {
         $list[]=FeeExtrass::leftjoin('admission','fee_extrass.student_id','=','admission.admission_id')
                         ->leftjoin('fee_extras','fee_extrass.select_fee','=','fee_extras.id')
                        // ->where('admission.class',$request->class)
                         ->select('fee_extras.title as fee_extras',
                            db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as name"),
                            'SUM(fee_extrass.amount)as amount,
                            (SUM(fee_extrass.amount) - SUM(fee_extras.amount)) as balance')
                         ->groupBy('select_fee')
                         ->get();
                         if(!empty($list)){
                                            return response()->json([
                                                 'message'  => 'success',
                                                 'data'  => $list,
                                                
                                            ]);
                                        }else {
                                            return response()->json([
                                                 'message'  => 'failed'
                                                 ]);
                                        }   
        
        
    }
    public function feeExtrassList(request $request)
    {
        $list=FeeExtrass::leftjoin('admission','fee_extrass.student_id','=','admission.admission_id')
                         ->leftjoin('fee_extras','fee_extrass.select_fee','=','fee_extras.id')
                         ->where('admission.class',$request->class)
                         ->select('fee_extras.title as fee_extras',db::raw("SUM(fee_extrass.amount)as amount"),
                            db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as name"))
                         ->groupBy('select_fee')
                         ->get();
                         if(!empty($list)){
                                            return response()->json([
                                                 'message'  => 'success',
                                                 'data'  => $list,
                                                
                                            ]);
                                        }else {
                                            return response()->json([
                                                 'message'  => 'failed'
                                                 ]);
                                        }   
        
    }
    public function feePaymentReport(request $request)
    {
        
    }
    public function ExamReport(request $request)
    {
        
    }
    public function jointExamReport(request $request)
    {
        
    }
    public function smsExamsReport(request $request)
    {
        
    }
    public function GradeAnalysis(request $request)
    {
        
    }
    public function expenseSummeryReport(request $request)
    {
        $detailExpense=ExpenseDetails::leftjoin('expense_category','expensedetails.category','=','expense_category.id')
        ->leftjoin('users','expensedetails.created_by','=','users.id')
        ->select('expensedetails.date',db::raw("sum(amount)as amont"),db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as recorded_by"),'expense_category.name as category')
       //->where('date', ExpenseDetails::max('date'))
        ->groupBy('category')
       ->get();
        if(!empty($detailExpense)){
            return response()->json([
                 'message'  => 'success',
                 'data'  => $detailExpense,
                
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
        
    }
    public function DetailExpensesReport(request $request)
    {
        $detailExpense=ExpenseDetails::leftjoin('expense_category','expensedetails.category','=','expense_category.id')
         ->leftjoin('expense_item','expensedetails.title','=','expense_item.id')
         ->leftjoin('staff','expensedetails.person_responsible','=','staff.employee_id')
         ->select('expensedetails.date','amount','expensedetails.description',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as person_responsible"),'expense_category.name as category','expense_item.name as title')->get();
         if(!empty($detailExpense)){
            return response()->json([
                 'message'  => 'success',
                 'data'  => $detailExpense,
                
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
        
    }
    public function wagsReport(request $request)
    {
        $wags=Process_salary::leftjoin('add_emp_sal','process_salary.employee','=','add_emp_sal.employee')
                                        ->leftjoin('users','process_salary.created_by','=','users.id')
                                        ->select(db::raw("CONCAT(sal_month-year)as month_year"),db::raw("COUNT(process_salary.employee)as num_of_employee"),'processing_date',
                                        db::raw("COUNT(deductions )as total_deductions"),
                                        db::raw("COUNT(allowances ) as total_advance"),
                                        db::raw("COUNT(NHIF_Amount)as nhif"),
                                        db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as paid_by")
                                        )->groupBy('sal_month')
                                        ->get();
                                        if(!empty($wags)){
                                            return response()->json([
                                                 'message'  => 'success',
                                                 'data'  => $wags,
                                                
                                            ]);
                                        }else {
                                            return response()->json([
                                                 'message'  => 'failed'
                                                 ]);
                                        }
        
    }
    public function SchoolAssets(request $request)
    {
        
    }
    public function BookFundReport(request $request)
    {
        $bookfund=Book_for_fund::leftjoin('books_category','book_for_fund.category','books_category.book_category_id')
        ->leftjoin('give_out_book_fund','book_for_fund.book_for_fund_id','=','give_out_book_fund.give_out_id')
        ->select('title','pages','author','quantity','books_category.name as category',db::raw("COUNT(book)as give_out"),
        db::raw("COUNT(give_out_book_fund.book)-quantity as remaining"))->groupBy('book_for_fund_id')->get();
        if(!empty($bookfund)){
            return response()->json([
                 'message'  => 'success',
                 'data'  => $bookfund,
                
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
        
    }

}
