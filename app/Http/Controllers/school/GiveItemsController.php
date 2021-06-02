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
use App\Models\Give_items;
use App\Models\Add_item;
use App\Models\Staff;

class GiveItemsController extends Controller
{
    public function store(Request $Give_items)
    {
      $validator =  Validator::make($Give_items->all(), [
            'date' => ['required'],
            'item' => ['required'],
            'quantity'    => ['required'],
            'given_to'  => ['required'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Give_items=Give_items::create([

        'date'  =>$Give_items->date,
        'item'          =>$Give_items->item,
        'quantity'         =>$Give_items->quantity,
        'given_to'        =>$Give_items->given_to,
        'comment'        =>$Give_items->comment,
        
         ]);
        if($Give_items->save()){
                  return response()->json([
                 'message'  => 'Give_items saved successfully',
                 'data'  => $Give_items 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Give_items = Give_items::find($request->give_item_id);
             if(!empty($Give_items)){
                    return response()->json([
                    'data'  => $Give_items      
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
        $Give_items = Give_items::join('add_item','give_items.item','=','add_item.item_id')->join('staff','give_items.given_to','=','staff.employee_id')->select('give_item_id','give_items.date','quantity','give_items.comment','add_item.name as item',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as given_to"))->get();
        return response()->json(['status' => 'Success', 'data' => $Give_items]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'date' => ['required'],
        'item' => ['required'],
        'quantity'    => ['required'],
        'given_to'  => ['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Give_items = Give_items::find($request->give_item_id);
        $Give_items->date= $request->date;
        $Give_items->item= $request->item;
                $Give_items->quantity= $request->quantity;
        $Give_items->given_to= $request->given_to;
        $Give_items->comment= $request->comment;
        if($Give_items->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Give_items
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Give_items = Give_items::find($request->give_item_id);
        if(!empty($Give_items))

                {
                  if($Give_items->delete()){
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
