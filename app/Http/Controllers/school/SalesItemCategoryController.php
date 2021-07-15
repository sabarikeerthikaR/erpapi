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
use App\Models\Sales_item_category;

class SalesItemCategoryController extends Controller
{
     public function store(Request $Sales_item_category)
    {
      $validator =  Validator::make($Sales_item_category->all(), [
            'name' => ['required'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Sales_item_category=Sales_item_category::create([

        'name'  =>$Sales_item_category->name,
        'description'  =>$Sales_item_category->description,
        

         ]);

        $settings=Settings::create([
             'key_name'=>$Sales_item_category->name,
             'group_name'=>'sales_item_category',
             'key_value'=>$Sales_item_category->id
         ]);
         $settings->save();
        if($Sales_item_category->save()){
                  return response()->json([
                 'message'  => 'Sales_item_category saved successfully',
                 'data'  => $Sales_item_category 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Sales_item_category = Sales_item_category::find($request->id);
            if(!empty($Sales_item_category)){
                    return response()->json([
                    'data'  => $Sales_item_category      
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
        $Sales_item_category = Sales_item_category::all();
        return response()->json(['status' => 'Success', 'data' => $Sales_item_category]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
          'name' => ['required'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Sales_item_category = Sales_item_category::find($request->id);
       $Sales_item_category->name= $request->name;
       $Sales_item_category->description= $request->description;
        $settings=Settings::where('group_name','=','sales_item_category')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
        if($Sales_item_category->save()){
            return response()->json([
                 'message'  => 'updescriptiond successfully',
                 'data'  => $Sales_item_category
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Sales_item_category = Sales_item_category::find($request->id);
        $settings=Settings::where('group_name','=','sales_item_category')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($Sales_item_category))

                {
                  if($Sales_item_category->delete()){
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
