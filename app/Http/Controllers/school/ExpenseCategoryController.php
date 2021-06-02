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

class ExpenseCategoryController extends Controller
{
    public function store(Request $ExpenseCategory)
    {
      $validator =  Validator::make($ExpenseCategory->all(), [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $ExpenseCategory=ExpenseCategory::create([

        'name'  =>$ExpenseCategory->name,
        'description'          =>$ExpenseCategory->description,
       
         ]);
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
           'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $ExpenseCategory = ExpenseCategory::find($request->item_id);
        $ExpenseCategory->name= $request->title;
        $ExpenseCategory->description= $request->description;
                
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
