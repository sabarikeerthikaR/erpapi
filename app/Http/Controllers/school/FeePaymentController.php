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
        
        $Fee_payment = new Fee_payment(array(
          'date'   =>$g['date'],
          'amount'=>$g['amount'],
          'payment_method'=>$g['payment_method'],  
          'transaction_no'  =>$g['transaction_no'],
          'bank'   =>$g['bank'],
          'tuition_fee'=>$g['tuition_fee'],
          'student'=>$Fee_payment->student,
          'created_by'=>'admin'
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
        $Fee_payment = Fee_payment::all();
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
        $Fee_payment = Fee_payment::find($request->id);
        if(!empty($Fee_payment))

                {
                  if($Fee_payment->delete()){
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
    public function feePaymentStatusview(request $request)
    {

    }
    public function feePaymentStatusdelete(request $request)
    {

    }
    public function feePaymentlist(request $request)
    {

    }
    public function feePaymentlistedit(request $request)
    {

    }
    public function feePaymentlistdelete(request $request)
    {

    }
}
