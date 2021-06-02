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
use App\Models\Add_item;
use App\Models\Item_category;
use App\Models\Stock_takings;
use App\Models\Item_stock;

class InventoryTrendsController extends Controller
{
   public function inventTrend()
   {
     $item=Item_stock::join('stock_takings','item_stock.item_name','=','stock_takings.product_name')
     ->join('add_item','item_stock.item_name','=','add_item.item_id')
     ->select('item_stock.date','add_item.name',db::raw('quantity-closing_stock as issued_items'),
     'quantity as total_stock','closing_stock as stock_in_hand',
     'total as total_cost',db::raw('reorder_level-closing_stock as reorder_status'),
     db::raw('quantity-(quantity-closing_stock) as remaining'),'item_id')
     ->groupBy('item_name')->get();
     $cost=Item_stock::select(db::raw('SUM(total)as total'))->get();
     return response()->json([
         'message'=>'success',
         'data'=>$item,
         'total assets cost'=>$cost
     ]);
   }
   public function inventryProfile(request $request)
   {
     $name=Item_stock::join('add_item','item_stock.item_name','=','add_item.item_id')
     ->where('item_id',$request->item_id)
     ->select('name')->first();
     $additm=Item_stock::where('item_name',$request->item_id)->select('date','quantity','unit_price','total','added_by')->first();
     $stockTaking=Stock_takings::join('item_stock','stock_takings.product_name','=','item_stock.item_name')
     ->where('product_name',$request->item_id)
     ->select('date as stock_date','closing_stock','taken_on','taken_by')->first();
     $totaladd=Item_stock::where('item_name',$request->item_id)->select('quantity')->first();
     $totalRemove=Item_stock::join('stock_takings','item_stock.item_name','=','stock_takings.product_name')
     ->where('item_name',$request->item_id)
     ->select(db::raw('quantity-closing_stock as removed_stock'))->first();
     $remainingStock=Item_stock::join('stock_takings','item_stock.item_name','=','stock_takings.product_name')
     ->where('item_name',$request->item_id)
     ->select(db::raw('quantity-(quantity-closing_stock) as remaining'))->first();
     return response()->json([
       'message'=>'success',
       'trend_for'=>$name,
       'stock addition'=>$additm,
       'stock takings'=>$stockTaking,
       'total added'=>$totaladd,
       'total removed'=>$totalRemove,
       'remaining'=>$remainingStock
     ]);
   }
}
