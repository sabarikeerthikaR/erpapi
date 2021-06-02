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
use App\Models\Do_invoice;

class DoInvoiceController extends Controller
{
    public function store(Request $Do_invoice)
    {
      $validator =  Validator::make($Do_invoice->all(), [
           'term' => ['required', 'string'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Do_invoice=Do_invoice::create([

        'term'  =>$Do_invoice->term ,
      
         ]);
        if($Do_invoice->save()){
                  return response()->json([
                 'message'  => 'Do_invoice saved successfully',
                 'data'  => $Do_invoice 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Do_invoice = Do_invoice::find($request->id);
             if(!empty($Do_invoice)){
                    return response()->json([
                    'data'  => $Do_invoice      
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
        $Do_invoice = Do_invoice::all();
        return response()->json(['status' => 'Success', 'data' => $Do_invoice]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
      'term' => ['required', 'string'],
           
          
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Do_invoice = Do_invoice::find($request->id);
        $Do_invoice->term = $request->term ;
       
       
        if($Do_invoice->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Do_invoice
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Do_invoice = Do_invoice::find($request->id);
        if(!empty($Do_invoice))

                {
                  if($Do_invoice->delete()){
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
