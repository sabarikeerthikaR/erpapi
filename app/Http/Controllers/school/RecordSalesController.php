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
          'total'=>$g['quantity'] * $g['unit_price'],
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
              'data'  => $RecordSales,
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
        $RecordSales = RecordSales::leftjoin('setings','record_sales.pay_method','=','setings.s_d')
        ->leftjoin('sales_item','record_sales.item','=','sales_item.id')
        ->leftjoin('admission','record_sales.student','=','admission.admission_id')
        ->select('record_sales.date','record_sales.id','quantity','unit_price','total','transaction_no',
                  'sales_item.item_name as item','setings.key_name as pay_method',
                  db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as student "),
                  'admission_id')
        ->groupBy('student')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $RecordSales]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
         'date' => ['required'],
            'student' => ['required'],
                                                           
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $RecordSales = RecordSales::find($request->id);
        $RecordSales->date = $request->date ;
        $RecordSales->student = $request->student ;
        $RecordSales->item = $request->item ;
        $RecordSales->quantity = $request->quantity;
         $RecordSales->unit_price = $request->unit_price;
          $RecordSales->total = $request->quantity * $request->unit_price ;
           $RecordSales->transaction_no = $request->transaction_no;
            $RecordSales->pay_method = $request->pay_method;
        
        if($RecordSales->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $RecordSales
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $RecordSales = RecordSales::where('student',$request->admission_id)->get();
        foreach($RecordSales as $r)
        {
            $r->delete();
        }
        if(!empty($RecordSales))

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
    public function recordSalesView(request $request)
    {
       // $date=date('y-m-d'),
    $date=  \Carbon\Carbon::parse(date('y-m-d'))->isoFormat('MMM Do YYYY');
    $receipt='#'.randomFunctionNumber(6);
        $RecordSalesSingle=RecordSales::where('student',$request->admission_id)
                       ->leftjoin('admission','record_sales.student','=','admission.admission_id')
                       ->leftjoin('add_stream','admission.class','=','add_stream.id')
                       ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
                       ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
                       ->select(db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as name"),
                        db::raw("CONCAT(std_class.name,' ',class_stream.name)as class"),
                         'admission_no','image')->first();
        $RecordSalesget=RecordSales::where('student',$request->admission_id)
                       ->leftjoin('setings','record_sales.pay_method','=','setings.s_d')
                       ->leftjoin('sales_item','record_sales.item','=','sales_item.id')
                       ->select('date','sales_item.item_name as item','quantity','unit_price','total',
                                'transaction_no','setings.key_name as pay_method')
                       ->get();
       $total=RecordSales::where('student',$request->admission_id)
                       ->select( DB::raw('SUM(total) as total'))
                       ->groupBy('student')
                       ->first();

         return response()->json(['status' => 'Success', 'data' => ['name'=>$RecordSalesSingle->name,
                                                                     'class'=>$RecordSalesSingle->class,
                                                                     'admission_no'=>$RecordSalesSingle->admission_no,
                                                                    'image'=>$RecordSalesSingle->image,
                                                                    'date' => $date,
                                                                   'receipt' => $receipt,
                                                                   ],
                                                          
                                                          'RecordSales'=>$RecordSalesget,
                                                          'total'=>$total->total
                                                          ]);
        

    }
}
