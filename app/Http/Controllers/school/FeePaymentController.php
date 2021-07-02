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
use App\Models\Fee_payment;
use App\Models\Terms;
use App\Models\Admission;
use App\Models\Fee_structure;
use App\Models\InstitutionDetails;
use Illuminate\Support\Facades\Auth;

class FeePaymentController extends Controller
{
    public function store(Request $Fee_payment)
    {
        
        $fee=$Fee_payment->fee;
        $errors=[];
        foreach($fee as $g)
        {
          if ($Fee_payment->student=='') 
          {
           return response()->json(apiResponseHandler([],'The student field is required',400), 400);
          }
          $class=Admission::where('admission_id',$Fee_payment->student)->select('class')->first();
          $feeAmount=Fee_structure::where('class',$class->class)->where('term',$g['term'])
          ->select('fee_amount')->first();
        $Fee_payment = new Fee_payment(array(
          'date'   =>$g['date'],
          'amount'=>$g['amount'],
          'payment_method'=>$g['payment_method'],  
          'transaction_no'  =>$g['transaction_no'],
          'bank'   =>$g['bank'],
          'description'   =>$g['description'],
          'tuition_fee'=>$feeAmount->fee_amount,
          'term'=>$g['term'],
          'student'=>$Fee_payment->student,
          'created_by'=>auth::user()->id
         ));
          if(!$Fee_payment->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'Fee_payment saved successfully',
              'data'=>$fee,
              'student'=>$Fee_payment->student
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

    $Fee_payment = Fee_payment::find($request->id);
             if(!empty($Fee_payment)){
                    return response()->json([
                    'data'  => $Fee_payment      
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
        $Fee_payment = Fee_payment::leftjoin('terms','fee_payment.term','=','terms.term_id')
        ->leftjoin('setings as fee','fee_payment.payment_method','=','fee.s_d')
        ->leftjoin('bank_account','fee_payment.bank','=','bank_account.id')
        ->leftjoin('admission','fee_payment.student','=','admission.admission_id')
         ->leftjoin('add_stream','admission.class','=','add_stream.id')
         ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
         ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('setings as bank','fee_payment.bank','=','bank.s_d')
        ->select(DB::raw('SUM(amount) as paid'),DB::raw('SUM(tuition_fee) as payable'),db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
            db::raw('SUM(tuition_fee) - SUM(amount) as balance'),'admission.admission_no as adm_no','admission_id','class_stream.name as stream','std_class.name as class')
        ->groupBy('student')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Fee_payment]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           
            'student'=> ['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Fee_payment = Fee_payment::find($request->id);
        $Fee_payment->date = $request->date ;
        $Fee_payment->amount = $request->amount ;
         $Fee_payment->payment_method = $request->payment_method ;
        $Fee_payment->transaction_no = $request->transaction_no ;
         $Fee_payment->bank = $request->bank ;
         $Fee_payment->tuition_fee = $request->tuition_fee ;
         $Fee_payment->student = $request->student ;
         $Fee_payment->term = $request->term ;
        if($Fee_payment->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Fee_payment
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Fee_payment = Fee_payment::where('student',$request->admission_id)->get();
        foreach($Fee_payment as $g)
        {
            $g->delete();
        }
        if(!empty($Fee_payment))

                {
                
                  return response()->json([
                  'message'  => 'successfully deleted'
                   ]);
              
           }else
           {
           return response()->json([
                 'message'  => 'No data found in this id'  
                 ]);
            }
    }
    public function feePaymentStatusview(request $request)
    {
        $Fee_payment = Fee_payment::where('student',$request->admission_id)
        ->leftjoin('terms','fee_payment.term','=','terms.term_id')
        ->leftjoin('setings as fee','fee_payment.payment_method','=','fee.s_d')
        ->leftjoin('bank_account','fee_payment.bank','=','bank_account.id')
        ->leftjoin('admission','fee_payment.student','=','admission.admission_id')
         ->leftjoin('add_stream','admission.class','=','add_stream.id')
         ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
         ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('bank_name','fee_payment.bank','=','bank_name.id')
        ->select('terms.name as term','fee.key_name as payment_method','fee_payment.date','amount as paid','transaction_no','tuition_fee as payable',
            db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),'fee_payment.id',
            db::raw('tuition_fee - amount as balance'),'admission.admission_no','class_stream.name as stream','std_class.name as class',
            db::raw("CONCAT(bank_name.name,' ',bank_account.account_no)as bank"),'fee_payment.description')
        ->get();
         $otherdata = Fee_payment::where('student',$request->admission_id)
        ->leftjoin('admission','fee_payment.student','=','admission.admission_id')
        ->select(
            db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),'fee_payment.id',
            'admission.admission_id')
        ->first();
        $balance=Fee_payment::where('student',$request->admission_id)
        ->select(db::raw('sum(tuition_fee-amount) as balance'))
        ->groupBy('student')
        ->first();
        return response()->json(['status' => 'Success', 'data' => $Fee_payment,
                                                          'balance'=>$balance->balance,
                                                          'other'=>$otherdata]);

    }
 
    public function feePaymentlist(request $request)
    {
         $Fee_payment = Fee_payment::leftjoin('terms','fee_payment.term','=','terms.term_id')
        ->leftjoin('setings as fee','fee_payment.payment_method','=','fee.s_d')
        ->leftjoin('bank_account','fee_payment.bank','=','bank_account.id')
        ->leftjoin('admission','fee_payment.student','=','admission.admission_id')
         ->leftjoin('add_stream','admission.class','=','add_stream.id')
         ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
         ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('bank_name','fee_payment.bank','=','bank_name.id')
        ->select(DB::raw('SUM(amount) as amount'),db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
            db::raw("CONCAT(bank_name.name,' ',bank_account.account_no)as bank"),
            'fee_payment.date','fee_payment.description','fee.key_name as type','admission.admission_id',
            'fee_payment.id')
        ->groupBy('student')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Fee_payment]);

    }
    public function feePaymentlistview(request $request)
    {
        $Fee_payment = Fee_payment::where('student',$request->admission_id)
        ->leftjoin('terms','fee_payment.term','=','terms.term_id')
        ->leftjoin('setings as fee','fee_payment.payment_method','=','fee.s_d')
        ->leftjoin('bank_account','fee_payment.bank','=','bank_account.id')
        ->leftjoin('admission','fee_payment.student','=','admission.admission_id')
         ->leftjoin('add_stream','admission.class','=','add_stream.id')
         ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
         ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('bank_name','fee_payment.bank','=','bank_name.id')
        ->select('fee.key_name as payment_method','fee_payment.date','amount','transaction_no','fee_payment.id',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),'admission.admission_id','class_stream.name as stream','std_class.name as class','fee_payment.description')
        ->groupBy('fee_payment.id')
        ->get();
        $count=randomFunctionNumber(4);
        $receipt=$count;
        $date=date("Y-m-d");
        $student = Fee_payment::where('student',$request->admission_id)
        ->leftjoin('admission','fee_payment.student','=','admission.admission_id')
         ->leftjoin('add_stream','admission.class','=','add_stream.id')
         ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
         ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),'admission.admission_id','class_stream.name as stream','std_class.name as class')
        ->groupBy('fee_payment.id')
        ->first();
         $otherdata =InstitutionDetails::
         leftjoin('institution_setup','institution_details.id','=','institution_setup.institution_id')
         ->leftjoin('counties','institution_details.County','=','counties.id')
        ->select('School_Name','Registration_Number','Registration_Date','counties.name as county','institution_setup.Telephone','CellNumber')->first();
        $balance=Fee_payment::where('student',$request->admission_id)
        ->select(db::raw('sum(tuition_fee-amount) as balance'))
        ->groupBy('student')
        ->first();
        return response()->json(['status' => 'Success', 'data' => $Fee_payment,
                                                         'other_data'=>$otherdata,
                                                          'balance'=>$balance->balance,
                                                      'student'=>$student,
                                                  'date'=>$date,
                                              'receipt_no'=>$receipt]);

    }
    public function feePaymentlistedit(request $request)
    {
   
        if($request->amount)
        {
            $feedata = Fee_payment::where('id',$request->id)->select('amount')->first();
            $payment=$feedata->amount - $request->amount;
            $feedata->tuition_fee=$payment;
        }
 
    $feedata = Fee_payment::where('id',$request->id)->first();
      
      $feedata->date=$request->date;
      $feedata->amount=$request->amount;
      $feedata->payment_method=$request->payment_method;
      $feedata->transaction_no=$request->transaction_no;
      $feedata->bank=$request->bank;
      $feedata->description=$request->description;
      $feedata->term=$request->term;

     
          if($feedata->save())
          {
          return response()->json([
          'message'  => 'feedata saved successfully',
          'data'=>$feedata,
         
              ]);
          }
          else 
          {
              return response()->json([
               'message'  => 'failed',
             ]);
           }
  }

  public function OnlinePayment(request $request)
  {
    
  }

    
    
}
