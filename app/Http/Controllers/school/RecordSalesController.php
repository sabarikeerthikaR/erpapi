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
use App\Models\RecordSales;

class RecordSalesController extends Controller
{
    public function store(Request $RecordSales)
    {
        
        $sales=$RecordSales->sales;
        $errors=[];
        foreach($sales as $g)
        {
           if ($RecordSales->date=='') 
          {
           return response()->json(apiResponseHandler([],'The date field is required',400), 400);
          }
          if ($RecordSales->student=='') 
          {
           return response()->json(apiResponseHandler([],'The student field is required',400), 400);
          }
         
        
        $RecordSales = new RecordSales(array(
          'date'=>$RecordSales->date,
          'student'=>$RecordSales->student,    
          'item'=>$g['item'],
          'quantity'=>$g['quantity'],
          'unit_price'=>$g['unit_price'],
          'total'=>$g['total'],
          'transaction_no'=>$g['transaction_no'],
          'pay_method'=>$g['pay_method'],
         ));
          if(!$RecordSales->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'RecordSales saved successfully',
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

    $RecordSales = RecordSales::find($request->id);
             if(!empty($RecordSales)){
                    return response()->json([
                    'data'  => $RecordSales      
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
        $RecordSales = RecordSales::all();
        return response()->json(['status' => 'Success', 'data' => $RecordSales]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
         'date' => ['required', 'string'],
            'student' => ['required', 'string'],
                                                           
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Hostel = Hostel::find($request->id);
        $Hostel->date = $request->date ;
        $Hostel->student = $request->student ;
        $Hostel->item = $request->item ;
        $Hostel->quantity = $request->quantity;
         $Hostel->unit_price = $request->unit_price;
          $Hostel->total = $request->total;
           $Hostel->transaction_no = $request->transaction_no;
            $Hostel->pay_method = $request->pay_method;
        
        if($Hostel->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Hostel
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $RecordSales = RecordSales::find($request->id);
        if(!empty($RecordSales))

                {
                  if($RecordSales->delete()){
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
