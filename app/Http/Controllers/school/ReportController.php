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

        $student=Admission::where('admission.class',$request->class)
        ->leftjoin('add_stream','admission.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->select(DB::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as name"),
        db::raw("concat(std_class.name,' ',class_stream.name)as class"),'admission_id')->get();
        if(!empty($student)){
            return response()->json([
                 'message'  => 'success',
                 'data'  => $student,
                
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }

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
                                           db::raw("CONCAT(first_parent.first_name_f,' ',COALESCE(first_parent.middle_name_f,''),' ',first_parent.last_name_f)as parent"),'phone_f', 'admission.admission_id')->get();
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
                             db::raw("CONCAT(std_class.name,' ',class_stream.name)as class"),'extra_curricular_activity.id')
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
        $feepayment=Admission::leftjoin('fee_payment','admission.admission_id','=', 'fee_payment.student')
                            ->leftjoin('fee_waivers','fee_payment.student','=','fee_waivers.student')
                            ->leftjoin('add_stream','admission.class','=','add_stream.id')
                            ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
                            ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
                            ->leftjoin('terms','fee_payment.term','=','terms.term_id')
                            ->leftjoin('fee_structure','terms.term_id','=','fee_structure.id')
                            ->where('terms.term_id',$request->term)
                            ->whereYear('from_year',$request->year)
                            ->select(
                          db::raw("CONCAT(std_class.name,' ',class_stream.name)as class"),
                          db::raw("COUNT(admission_id)as studentc"),
                            db::raw("SUM('fee_structure.fee_amount*studentc')as invoiced_amount"),db::raw("SUM('fee_waivers.amount')as waiver"),
                            db::raw("SUM(fee_payment.amount)as received"),
                            db::raw("SUM('studentc * fee_amount - fee_payment.amount')as balance"),'fee_payment.id')
                            ->groupBy('admission.class')
                            ->get();

                            if(!empty($feepayment)){
                                return response()->json([
                                     'message'  => 'success',
                                     'data'  => $feepayment,
                                    
                                ]);
                            }else {
                                return response()->json([
                                     'message'  => 'failed'
                                     ]);
                            }  
    }
    public function feeStatus(request $request)
    {
        $feestatus=Admission::leftjoin('fee_payment','admission.admission_id','=', 'fee_payment.student')
        ->leftjoin('add_stream','admission.class','=','add_stream.id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('terms','fee_payment.term','=','terms.term_id')
        ->leftjoin('fee_structure','terms.term_id','=','fee_structure.id')
        ->select(db::raw("concat(first_name,' ',COALesce(middle_name,''),' ',last_name)as name"),db::raw("concat(std_class.name,' ',class_stream.name)as class"),'admission_no',db::raw("sum(fee_structure.fee_amount)as invoiced_amount"),db::raw("sum(fee_payment.amount)as paid"),db::raw("SUM(fee_structure.fee_amount - fee_payment.amount)as balance"),'fee_payment.id')
        ->groupBy('fee_payment.student')
        ->get();
        if(!empty($feestatus)){
            return response()->json([
                 'message'  => 'success',
                 'data'  => $feestatus,
                
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }  
    }
    public function feeArrears(request $request)
    {
        $feeArrears=Admission::leftjoin('fee_arrears','admission.admission_id','=','fee_arrears.student')
                                          ->leftjoin('fee_payment','admission.admission_id','=','fee_payment.student')
                                          ->leftjoin('fee_extrass','admission.admission_id','=','fee_extrass.student_id')
                                          ->leftjoin('fee_extras','fee_extrass.select_fee','=',
                                          'fee_extras.id')
                                          ->leftjoin('fee_structure','fee_payment')
                                          ->get();
        
    }
    public function feeExtrassReport(request $request)
    {
         $list=FeeExtrass::leftjoin('admission','fee_extrass.student_id','=','admission.admission_id')
                         ->leftjoin('fee_extras','fee_extrass.select_fee','=','fee_extras.id')
                         ->leftjoin('terms','fee_extrass.term','=','terms.term_id')
                         ->where('admission.class',$request->class)
                         ->where('fee_extrass.select_fee',$request->fee)
                         ->whereYear('terms.from_year',$request->year)
                         ->select('fee_extras.title as fee_extras','fee_extrass.id',
                            db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as name"),
                            db::raw("SUM(fee_extrass.amount)as amount"),
                            db::raw("SUM(fee_extrass.amount -fee_extras.amount) as balance"))
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
                         ->select('fee_extrass.id','fee_extras.title as fee_extras',db::raw("SUM(fee_extrass.amount)as amount"),
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
        ->whereYear('expensedetails.date', $request->year)
        ->select('expensedetails.date',db::raw("sum(amount)as amont"),db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as recorded_by"),'expense_category.name as category','expensedetails.id')
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
        ->whereYear('expensedetails.date', $request->year)
        ->where('expensedetails.category', $request->category)
         ->leftjoin('expense_item','expensedetails.title','=','expense_item.id')
         ->leftjoin('staff','expensedetails.person_responsible','=','staff.employee_id')
         ->select('expensedetails.id','expensedetails.date','amount','expensedetails.description',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as person_responsible"),'expense_category.name as category','expense_item.name as title')->get();
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
                                        ->leftjoin('month','process_salary.sal_month','=','month.id')
                                        ->leftjoin('setings','process_salary.year','=','setings.s_d')
                                        ->leftjoin('advance_salary','process_salary.employee','=','advance_salary.employee')
                                        ->select('process_salary.id',db::raw("CONCAT(month.name,' - ',setings.key_name)as month_year"),db::raw("COUNT(process_salary.employee)as num_of_employee"),'processing_date',
                                        db::raw("sum(deductions )as total_deductions"),
                                        db::raw("sum(NHIF_Amount)as nhif"),
                                        db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as paid_by"),db::raw("sum(basic_salary)as process_salary"),db::raw("sum(advance_salary.amount)as total_advance"), db::raw("sum((basic_salary) - (deductions)) as total_paid"),
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
        ->select('book_for_fund.book_for_fund_id','title','pages','author','quantity','books_category.name as category',db::raw("COUNT(book)as give_out"),
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
