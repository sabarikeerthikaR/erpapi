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
use App\Models\PaymentOptions;

class PaymentOptionsController extends Controller
{
    public function store(Request $PaymentOptions)
    {
    	 $validator =  Validator::make($PaymentOptions->all(), [

            'account' => ['required', 'string'],
            'business_no' => ['required', 'string'],
            
           
            
        ]); 
           if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $PaymentOptions=PaymentOptions::create([
      
       
        'account'          =>$PaymentOptions->account,
        'business_no'         =>$PaymentOptions->business_no,
        'descripton'         =>$PaymentOptions->descripton,
       
        
         ]);
        if($PaymentOptions->save()){
                  return response()->json([
                 'message'  => 'PaymentOptions saved successfully',
                 'data'  => $PaymentOptions 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $PaymentOptions = PaymentOptions::find($request->id);
             if(!empty($PaymentOptions)){
                    return response()->json([
                    'data'  => $PaymentOptions      
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
        $PaymentOptions = PaymentOptions::all();
        return response()->json(['knec_code' => 'Success', 'data' => $PaymentOptions]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
   	 	
           'account' => ['required', 'string'],
            'business_no' => ['required', 'string'],

        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $PaymentOptions = PaymentOptions::find($request->id);
       
    
       $PaymentOptions->account= $request->account;
       $PaymentOptions->business_no= $request->business_no;
       $PaymentOptions->descripton= $request->descripton;
      
      
       
        if($PaymentOptions->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $PaymentOptions
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $PaymentOptions = PaymentOptions::find($request->id);
        if(!empty($PaymentOptions))

                {
                  if($PaymentOptions->delete()){
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
