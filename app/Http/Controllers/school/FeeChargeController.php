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
use App\Models\Fee_charge;

class FeeChargeController extends Controller
{
   public function store(Request $Fee_charge)
    {
      $validator =  Validator::make($Fee_charge->all(), [
            'name'       => ['required', 'string'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Fee_charge=Fee_charge::create([
        'name'  =>$Fee_charge->name,
       
       
         ]);
        if($Fee_charge->save()){
                  return response()->json([
                 'message'  => 'Fee_charge saved successfully',
                 'data'  => $Fee_charge 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Fee_charge = Fee_charge::find($request->id);
             if(!empty($Fee_charge)){
                    return response()->json([
                    'data'  => $Fee_charge      
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
        $Fee_charge = Fee_charge::all();
        return response()->json(['status' => 'Success', 'data' => $Fee_charge]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'name'       => ['required', 'string'],
          
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Fee_charge = Fee_charge::find($request->id);
       $Fee_charge->name= $request->name;
       

        if($Fee_charge->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Fee_charge
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Fee_charge = Fee_charge::find($request->id );
        if(!empty($Fee_charge))

                {
                  if($Fee_charge->delete()){
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
