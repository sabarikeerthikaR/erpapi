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
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function store(Request $PaymentMethod)
    {
      $validator =  Validator::make($PaymentMethod->all(), [
            'name' => ['required', 'string'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $PaymentMethod=PaymentMethod::create([

        'name'  =>$PaymentMethod->name,
       
         ]);
        if($PaymentMethod->save()){
                  return response()->json([
                 'message'  => 'PaymentMethod saved successfully',
                 'data'  => $PaymentMethod 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $PaymentMethod = PaymentMethod::find($request->id);
             if(!empty($PaymentMethod)){
                    return response()->json([
                    'data'  => $PaymentMethod      
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
        $PaymentMethod = PaymentMethod::all();
        return response()->json(['status' => 'Success', 'data' => $PaymentMethod]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'name' => ['required', 'string'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $PaymentMethod = PaymentMethod::find($request->id);
       $PaymentMethod->name= $request->name;
       
        if($PaymentMethod->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $PaymentMethod
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $PaymentMethod = PaymentMethod::find($request->id);
        if(!empty($PaymentMethod))

                {
                  if($PaymentMethod->delete()){
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
