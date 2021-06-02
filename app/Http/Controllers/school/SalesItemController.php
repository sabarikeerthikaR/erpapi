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

class SalesItemController extends Controller
{
     public function store(Request $Sales_item)
    {
      $validator =  Validator::make($Sales_item->all(), [
            'item_name' => ['required', 'string'],
            'category' => ['required', 'string'],
            'description'    => ['required', 'string'],
            
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Sales_item=Sales_item::create([

        'item_name'  =>$Sales_item->item_name,
        'category'  =>$Sales_item->category,
        'description'    =>$Sales_item->description,
        
         ]);
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
        $Sales_item = Sales_item::all();
        return response()->json(['status' => 'Success', 'data' => $Sales_item]);
    }


public function upcategory(Request $request)

   {
    $validator =  Validator::make($request->all(), [
          'item_name' => ['required', 'string'],
            'category' => ['required', 'string'],
            'description'    => ['required', 'string'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Sales_item = Sales_item::find($request->id);
       $Sales_item->item_name= $request->item_name;
       $Sales_item->category= $request->category;
       $Sales_item->description= $request->description;
      
       
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
