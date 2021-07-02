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
use App\Models\ExpenseCategory;
use App\Models\Settings;

class ExpenseCategoryController extends Controller
{
    public function store(Request $ExpenseCategory)
    {
      $validator =  Validator::make($ExpenseCategory->all(), [
            'name' => ['required'],
        
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $ExpenseCategory=ExpenseCategory::create([

        'name'  =>$ExpenseCategory->name,
        
         ]);
         $settings=Settings::create([
            'group_name'=>'expense_category',
            'key_name'=>$ExpenseCategory->name,
            'key_value'=>$ExpenseCategory->id,
            ]);
            $settings->save();
        if($ExpenseCategory->save()){
                  return response()->json([
                 'message'  => 'ExpenseCategory saved successfully',
                 'data'  => $ExpenseCategory 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $ExpenseCategory = ExpenseCategory::find($request->item_id);
             if(!empty($ExpenseCategory)){
                    return response()->json([
                    'data'  => $ExpenseCategory      
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
        $ExpenseCategory = ExpenseCategory::all();
        return response()->json(['status' => 'Success', 'data' => $ExpenseCategory]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
            'name' => ['required'],
        
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $ExpenseCategory = ExpenseCategory::find($request->item_id);
        $ExpenseCategory->name= $request->name;
    
                $settings=Settings::where('group_name','=','expense_category')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
        if($ExpenseCategory->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $ExpenseCategory
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $ExpenseCategory = ExpenseCategory::find($request->item_id);
         $settings=Settings::where('group_name','=','expense_category')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($ExpenseCategory))

                {
                  if($ExpenseCategory->delete()){
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
