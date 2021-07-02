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
use App\Models\ExpenseItem;
use App\Models\Settings;

class ExpenseItemController extends Controller
{
   public function store(Request $ExpenseItem)
    {
      $validator =  Validator::make($ExpenseItem->all(), [
            'name' => ['required'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $ExpenseItem=ExpenseItem::create([

        'name'  =>$ExpenseItem->name,
      
         ]);
        $settings=Settings::create([
            'group_name'=>'expense_item',
            'key_name'=>$ExpenseItem->name,
            'key_value'=>$ExpenseItem->id,
            ]);
            $settings->save();
        if($ExpenseItem->save()){
                  return response()->json([
                 'message'  => 'ExpenseItem saved successfully',
                 'data'  => $ExpenseItem 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $ExpenseItem = ExpenseItem::find($request->item_id);
             if(!empty($ExpenseItem)){
                    return response()->json([
                    'data'  => $ExpenseItem      
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
        $ExpenseItem = ExpenseItem::all();
        return response()->json(['status' => 'Success', 'data' => $ExpenseItem]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
            'name' => ['required'],
            
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $ExpenseItem = ExpenseItem::find($request->item_id);
        $ExpenseItem->name= $request->name;
    
                $settings=Settings::where('group_name','=','expense_item')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
        if($ExpenseItem->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $ExpenseItem
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $ExpenseItem = ExpenseItem::find($request->item_id);
        $settings=Settings::where('group_name','=','expense_item')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($ExpenseItem))

                {
                  if($ExpenseItem->delete()){
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
