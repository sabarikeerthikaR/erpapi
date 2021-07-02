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
use App\Models\Item_stock;
use Illuminate\Support\Facades\Auth;
use App\Models\Add_item;
use App\Models\Staff;

class ItemStockController extends Controller
{
    public function store(Request $Item_stock)
    {
      $valiDationArray =  [
            'date' => ['required'],
            'item_name' => ['required'],
            'quantity'    => ['required'],
            'unit_price'  => ['required'],
            'person_responsible'       => ['required'],
          ]; 
           if($Item_stock->receipt)
        {
          $valiDationArray["receipt"]='required|file';
        }
        $validator =  Validator::make($Item_stock->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $receipt='';
         if($Item_stock->file('receipt')){
         $receipt = $Item_stock->file('receipt');
         $imgName = time() . '.' . pathinfo($receipt->getClientOriginalName(), PATHINFO_EXTENSION);
         Storage::disk('public_uploads')->put('/receipt/' . $imgName, file_get_contents($receipt));
         $receipt=config('app.url').'/public/uploads/receipt/' . $imgName;
         }
       
        $Item_stock=Item_stock::create([

        'date'  =>$Item_stock->date,
        'item_name'=>$Item_stock->item_name,
        'quantity'=>$Item_stock->quantity,
        'unit_price'=>$Item_stock->unit_price,
        'total'   =>$Item_stock->quantity*$Item_stock->unit_price,
        'person_responsible'=>$Item_stock->person_responsible,
        'receipt'        =>$receipt,
        'description'        =>$Item_stock->description,
        'added_by'        =>Auth::user()->id,
         ]);
        if($Item_stock->save()){
                  return response()->json([
                 'message'  => 'Item_stock saved successfully',
                 'data'  => $Item_stock 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Item_stock = Item_stock::find($request->item_stock_id);
             if(!empty($Item_stock)){
                    return response()->json([
                    'data'  => $Item_stock      
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
        $Item_stock = Item_stock::join('staff','item_stock.person_responsible','=','staff.employee_id')->leftjoin('users','item_stock.added_by','=','users.id')->join('add_item','item_stock.item_name','=','add_item.item_id')
        ->select('item_stock_id','item_stock.date','add_item.name as item_name','quantity','unit_price','total','receipt',db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),' ',staff.last_name)as person_responsible"),db::raw("CONCAT(users.first_name,' ',COALESCE(users.middle_name,''),' ',users.last_name)as added_by"))->get();
        return response()->json(['status' => 'Success', 'data' => $Item_stock]);
    }


public function update(Request $request)

   {
    $valiDationArray =   [
        'date' => ['required'],
        'item_name' => ['required'],
        'quantity'    => ['required'],
        'unit_price'  => ['required'],
        'person_responsible'       => ['required'],
        ]; 
         if($request->receipt)
        {
          $valiDationArray["receipt"]='required|file';
        }
        $validator =  Validator::make($request->all(),$valiDationArray); 
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
         }
       $Item_stock = Item_stock::find($request->item_stock_id);
          if($request->file('receipt')){
              $receipt = $request->file('receipt');
              $imgName = time() . '.' . pathinfo($receipt->getClientOriginalName(), PATHINFO_EXTENSION);
              Storage::disk('public_uploads')->put('/receipt/' . $imgName, file_get_contents($receipt));
              $receipt=config('app.url').'/public/uploads/receipt/' . $imgName;
              $Item_stock->receipt=$receipt;
              }
   
        $Item_stock->date= $request->date;
        $Item_stock->item_name= $request->item_name;
                $Item_stock->quantity= $request->quantity;
        $Item_stock->unit_price= $request->unit_price;
        $Item_stock->total=  $request->quantity*$request->unit_price;
        $Item_stock->person_responsible= $request->person_responsible;
        $Item_stock->description= $request->description;
       
        if($Item_stock->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Item_stock
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Item_stock = Item_stock::find($request->item_stock_id);
        if(!empty($Item_stock))

                {
                  if($Item_stock->delete()){
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
