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
use App\Models\Sales_item;
use App\Models\Settings;

class SalesItemController extends Controller
{
     public function store(Request $Sales_item)
    {
      $validator =  Validator::make($Sales_item->all(), [
            'item_name' => ['required'],
            'category' => ['required'],
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Sales_item=Sales_item::create([

        'item_name'  =>$Sales_item->item_name,
        'category'  =>$Sales_item->category,
        'description'    =>$Sales_item->description,
        
         ]);
         $settings=Settings::create([
            'group_name'=>'sales_item',
            'key_name'=>$Sales_item->item_name,
            'key_value'=>$Sales_item->id,
            ]);
            $settings->save();
        if($Sales_item->save()){
                  return response()->json([
                 'message'  => 'Sales_item saved successfully',
                 'data'  => $Sales_item 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Sales_item = Sales_item::find($request->id);
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
   public function index()
    {
        $Sales_item = Sales_item::leftjoin('sales_item_category','sales_item.category','=','sales_item_category.id')
        ->select('item_name','sales_item.id','sales_item_category.name as category','sales_item.description')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Sales_item]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'item_name' => ['required'],
            'category' => ['required'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Sales_item = Sales_item::find($request->id);
       $Sales_item->item_name= $request->item_name;
       $Sales_item->category= $request->category;
       $Sales_item->description= $request->description;
       $settings=Settings::where('group_name','=','sales_item')->where('key_value',$request->id)->first();
        $settings->key_name= $request->item_name;
        $settings->save();
       
        if($Sales_item->save()){
            return response()->json([
                 'message'  => 'upcategoryd successfully',
                 'data'  => $Sales_item
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Sales_item = Sales_item::find($request->id);
         $settings=Settings::where('group_name','=','sales_item')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($Sales_item))

                {
                  if($Sales_item->delete()){
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
