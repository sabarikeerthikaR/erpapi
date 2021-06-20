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
use App\Models\Stock_takings;
use App\Models\Add_item;
use Illuminate\Support\Facades\Auth;


class StockTakingsController extends Controller
{
    public function store(Request $Stock_takings)
    {
      
        $Stock_takings=Stock_takings::create([

      
        'product_name'          =>$Stock_takings->product_name,
        'closing_stock'         =>$Stock_takings->closing_stock,
        'description'         =>$Stock_takings->description,
        'taken_on'        =>date("Y-m-d"),
        'taken_by'        =>Auth::user()->id,
       
         ]);
        if($Stock_takings->save()){
                  return response()->json([
                 'message'  => 'Stock_takings saved successfully',
                 'data'  => $Stock_takings 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Stock_takings = Stock_takings::find($request->stock_taking_id);
             if(!empty($Stock_takings)){
                    return response()->json([
                    'data'  => $Stock_takings      
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
        $Stock_takings = Stock_takings::leftjoin('add_item','stock_takings.product_name','=','add_item.item_id')
         ->leftjoin('users','stock_takings.taken_by','=','users.id')
        ->leftjoin('item_stock','stock_takings.product_name','=','item_stock.item_name')
        ->select('item_stock.date as stock_date','closing_stock','taken_on',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),'',last_name)as taken_by"),'stock_taking_id','add_item.name as item')->get();
        return response()->json(['status' => 'Success', 'data' => $Stock_takings]);
    }


public function update(Request $request)

   {
    
    $Stock_takings = Stock_takings::find($request->stock_taking_id);
       
        $Stock_takings->product_name= $request->product_name;
                $Stock_takings->closing_stock= $request->closing_stock;
        $Stock_takings->description= $request->description;
       
        if($Stock_takings->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Stock_takings
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Stock_takings = Stock_takings::find($request->stock_taking_id);
        if(!empty($Stock_takings))

                {
                  if($Stock_takings->delete()){
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
