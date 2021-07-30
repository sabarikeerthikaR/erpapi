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
use App\Models\Sales_item_stock;
use App\Models\Sales_item;

class SalesItemStockController extends Controller
{
    public function Unitprice(request $request)
    {
        $Sales_item = Sales_item::where('id',$request->id)->select('unit_price')->first();
        if(!empty($Sales_item)){
               return response()->json([
               'data'  => $Sales_item      
               ]);
           }else
           {
             return response()->json([
            'message'  => 'No data found in this id'  
             ]);
            }

    }
    public function store(Request $Sales_item_stock)
    {
      $valiDationArray = [
            'purchase_date' => ['required'],
            'item' => ['required'],
            'quantity'    => ['required'],
            'unit_price' => ['required'],
            'buying_price' => ['required'],
            'person_responsible'    => ['required'],
          ]; 
         if($Sales_item_stock->receipt)
        {
          $valiDationArray["receipt"]='required|file';
        }
        $validator =  Validator::make($Sales_item_stock->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $receipt='';
        if($Sales_item_stock->file('receipt')){
        $receipt = $Sales_item_stock->file('receipt');
        $imgName = time() . '.' . pathinfo($receipt->getClientOriginalName(), PATHINFO_EXTENSION);
        Storage::disk('public_uploads')->put('/salesItem-stock/' . $imgName, file_get_contents($receipt));
        $receipt=config('app.url').'/public/uploads/salesItem-stock/' . $imgName;
        }
        $Sales_item_stock=Sales_item_stock::create([

        'purchase_date'  =>$Sales_item_stock->purchase_date,
        'item'  =>$Sales_item_stock->item,
        'quantity'    =>$Sales_item_stock->quantity,
         'unit_price'  =>$Sales_item_stock->unit_price,
        'buying_price'  =>$Sales_item_stock->buying_price,
        'person_responsible'    =>$Sales_item_stock->person_responsible,
         'receipt'  =>$receipt,
        'description'    =>$Sales_item_stock->description,
         
        
         ]);
        if($Sales_item_stock->save()){
                  return response()->json([
                 'message'  => 'Sales_item_stock saved successfully',
                 'data'  => $Sales_item_stock 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Sales_item_stock = Sales_item_stock::find($request->id);
             if(!empty($Sales_item_stock)){
                    return response()->json([
                    'data'  => $Sales_item_stock      
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
        $Sales_item_stock = Sales_item_stock::leftjoin('staff','sales_item_stock.person_responsible','=','staff.employee_id')
        ->leftjoin('sales_item','sales_item_stock.item','=','sales_item.id')
        ->select('purchase_date','quantity','sales_item.unit_price','buying_price','receipt','sales_item_stock.description',
                 'sales_item.item_name as item',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)person_responsible")
                 ,'sales_item_stock.id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Sales_item_stock]);
    }


public function update(Request $request)

   {
    $valiDationArray = [
         'purchase_date' => ['required'],
            'item' => ['required'],
            'quantity'    => ['required'],
            'unit_price' => ['required'],
            'buying_price' => ['required'],
            'person_responsible'    => ['required'],
        ]; 
         if($request->receipt)
        {
          $valiDationArray["receipt"]='required|file';
        }
        $validator =  Validator::make($request->all(),$valiDationArray); 
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
         }

    $Sales_item_stock = Sales_item_stock::find($request->id);

          if($request->file('receipt')){
              $receipt = $request->file('receipt');
              $imgName = time() . '.' . pathinfo($receipt->getClientOriginalName(), PATHINFO_EXTENSION);
              Storage::disk('public_uploads')->put('/Sales_item_stock-upload-document/' . $imgName, file_get_contents($receipt));
              $receipt=config('app.url').'/public/uploads/Sales_item_stock-upload-document/' . $imgName;
              $Sales_item_stock->receipt=$receipt;
              }

       $Sales_item_stock->purchase_date= $request->purchase_date;
       $Sales_item_stock->item= $request->item;
       $Sales_item_stock->quantity= $request->quantity;
       $Sales_item_stock->unit_price= $request->unit_price;
       $Sales_item_stock->buying_price= $request->buying_price;
       $Sales_item_stock->person_responsible = $request->description;
       $Sales_item_stock->description= $request->description;

        if($Sales_item_stock->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Sales_item_stock
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Sales_item_stock = Sales_item_stock::find($request->id);
        if(!empty($Sales_item_stock))

                {
                  if($Sales_item_stock->delete()){
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
