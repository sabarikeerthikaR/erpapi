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

class SalesItemStockController extends Controller
{
    public function store(Request $Sales_item_stock)
    {
      $validator =  Validator::make($Sales_item_stock->all(), [
            'purchase_date' => ['required'],
            'item' => ['required', 'string'],
            'quantity'    => ['required', 'numeric'],
            'unit_price' => ['required', 'numeric'],
            'buying_price' => ['required', 'numeric'],
            'person_responsible'    => ['required', 'string'],
            'receipt' => ['required', 'string'],
            'description' => ['required', 'string'],
  
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Sales_item_stock=Sales_item_stock::create([

        'purchase_date'  =>$Sales_item_stock->purchase_date,
        'item'  =>$Sales_item_stock->item,
        'quantity'    =>$Sales_item_stock->quantity,
         'unit_price'  =>$Sales_item_stock->unit_price,
        'buying_price'  =>$Sales_item_stock->buying_price,
        'person_responsible'    =>$Sales_item_stock->person_responsible,
         'receipt'  =>$Sales_item_stock->receipt,
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
        $Sales_item_stock = Sales_item_stock::all();
        return response()->json(['status' => 'Success', 'data' => $Sales_item_stock]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
         'purchase_date' => ['required'],
            'item' => ['required', 'string'],
            'quantity'    => ['required', 'numeric'],
            'unit_price' => ['required', 'numeric'],
            'buying_price' => ['required', 'numeric'],
            'person_responsible'    => ['required', 'string'],
            'receipt' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Sales_item_stock = Sales_item_stock::find($request->id);
       $Sales_item_stock->purchase_date= $request->purchase_date;
       $Sales_item_stock->item= $request->item;
       $Sales_item_stock->quantity= $request->quantity;
       $Sales_item_stock->unit_price= $request->unit_price;
       $Sales_item_stock->buying_price= $request->buying_price;
       $Sales_item_stock->person_responsible = $request->description;
       $Sales_item_stock->receipt= $request->receipt;
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
