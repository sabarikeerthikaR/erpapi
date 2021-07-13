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
use App\Models\Fee_extras;
use App\Models\FeeExtrass;
use App\Models\Fee_payment;
use App\Models\Terms;
use App\Models\Admission;
use App\Models\Fee_structure;
use App\Models\InstitutionDetails;
use App\Models\Online_payment;
use Illuminate\Support\Facades\Auth;

class OnlinePaymentController extends Controller
{

public function OnlinePaymentTermFeeList(request $request)

{
  $class=Admission::where('admission_id',$request->student)->select('class')->first();
  
$term=Fee_structure::where('class',$class->class)
  ->select('term',
           'fee_amount')   
  ->get();

   if(!empty($class))
      {
        return response()->json([
          'message'=>'success',
          'data'=>$term,
          // 'term'=>$termFee
        ]);
      }else{
        return response()->json([
          'message'=>'No data found'
        ]);
      }
}   
public function OnlinePaymentpost(request $request)

{
  $class=Admission::where('admission_id',$request->student)->select('class')->first();
  $feeAmount=Fee_structure::where('class',$class->class)->where('term',$request->term)
  ->select('fee_amount')->first();
  $number = randomFunctionNumber(16);
  $Fee_payment = new Fee_payment ([
          'date'   =>date("Y-m-d"),
          'payment_method'=>'924',  
          'transaction_no'  =>$number,
         'amount'=>$request->amount,
          //'bank'   =>$request->bank,
          'description'   =>'online_payment',
          'term'=>$request->term,
          'student'=>$request->student,
          'tuition_fee'=>$feeAmount->fee_amount,
          'created_by'=>auth::user()->id,
          'class'=>$class->class
         ]);

     $address = new Online_payment ([
          'name'   =>$request->name,
          'email'=>$request->email,  
          'transaction_id'  =>$number,
         'phone'=>$request->phone,
          'address'   =>$request->address,
          'card_number'=>$request->card_number,
         ]);
       if($Fee_payment->save()){
                  return response()->json([
                 'message'  => 'Fee_payment saved successfully',
                 'data'  => $Fee_payment 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }

}   
public function FeeStatement(request $request)
{
  $feeStatement=Fee_payment::where('student',$request->student)
                                 ->leftjoin('setings','fee_payment.payment_method','=','setings.s_d')
                                 ->leftjoin('terms','fee_payment.term','=','terms.term_id')
                         ->select('date','amount','terms.name as term','description','setings.key_name as payment_method')->get();

                         if(!empty($feeStatement))
      {
        return response()->json([
          'message'=>'success',
          'data'=>$feeStatement,
          // 'term'=>$termFee
        ]);
      }else{
        return response()->json([
          'message'=>'No data found'
        ]);
      }

}
public function feeExtrass(request $request)
{
    $feeExtrass=Fee_extras::select('title','amount','id')
                           ->get();
                           if(!empty($feeExtrass))
      {
        return response()->json([
          'message'=>'success',
          'data'=>$feeExtrass,
          // 'term'=>$termFee
        ]);
      }else{
        return response()->json([
          'message'=>'No data found'
        ]);
      }

}
public function FeeExtraspost(request $request)

{
 
$month=date('m');

$selectTerm=Terms::whereMonth('from_year','<=',$month)
                             ->whereMonth('to_year', '>=',$month)
                             ->select('term_id') 
                              ->first();



  $number = randomFunctionNumber(16);
  $Fee_payment = new FeeExtrass ([
          'payment_method'=>'924',  
          'transaction_no'  =>$number,
         'amount'=>$request->amount,
          //'bank'   =>$request->bank,
          'description'   =>'online_payment',
          'term'=>$selectTerm->term_id,
          'student_id'=>$request->student,
          'select_fee'=>$request->select_fee,
          'created_by'=>auth::user()->id
         ]);

     $address = new Online_payment ([
          'name'   =>$request->name,
          'email'=>$request->email,  
          'transaction_id'  =>$number,
         'phone'=>$request->phone,
          'address'   =>$request->address,
          'card_number'=>$request->card_number,
         ]);
       if($Fee_payment->save()){
                  return response()->json([
                 'message'  => 'Fee_payment saved successfully',
                 'data'  => $Fee_payment 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }


}   
public function FeeExtrassStatement(request $request)
{
  $feeStatement=FeeExtrass::where('student_id',$request->student)
                                 ->leftjoin('setings','fee_extrass.payment_method','=','setings.s_d')
                                 ->leftjoin('terms','fee_extrass.term','=','terms.term_id')
                         ->select(DB::raw('DATE_FORMAT(fee_extrass.created_at, "%Y-%m-%d") as date'),'amount','terms.name as term','description','setings.key_name as payment_method')->get();

                         if(!empty($feeStatement))
      {
        return response()->json([
          'message'=>'success',
          'data'=>$feeStatement,
          // 'term'=>$termFee
        ]);
      }else{
        return response()->json([
          'message'=>'No data found'
        ]);
      }

}


}
