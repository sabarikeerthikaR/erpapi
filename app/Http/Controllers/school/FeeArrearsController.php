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
use App\Models\Fee_arrears;

class FeeArrearsController extends Controller
{
    public function store(Request $Fee_arrears)
    {
      $validator =  Validator::make($Fee_arrears->all(), [
   
            'student'       => ['required', 'string'],
            'amount'       => ['required', 'numeric'],
            'term'       => ['required','string'],
             'year'       => ['required', 'string'],

           

          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Fee_arrears=Fee_arrears::create([
       
        'student'  =>$Fee_arrears->student,
        'amount'  =>$Fee_arrears->amount,
        'term'  =>$Fee_arrears->term,
         'year'  =>$Fee_arrears->year,
       
       
         ]);
        if($Fee_arrears->save()){
                  return response()->json([
                 'message'  => 'Fee_arrears saved successfully',
                 'data'  => $Fee_arrears 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Fee_arrears = Fee_arrears::find($request->id);
             if(!empty($Fee_arrears)){
                    return response()->json([
                    'data'  => $Fee_arrears      
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
        $Fee_arrears = Fee_arrears::all();
        return response()->json(['status' => 'Success', 'data' => $Fee_arrears]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'student'       => ['required', 'string'],
            'amount'       => ['required', 'numeric'],
            'term'       => ['required','string'],
             'year'       => ['required', 'string'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Fee_arrears = Fee_arrears::find($request->id);
      
        $Fee_arrears->student= $request->student;
         $Fee_arrears->amount= $request->amount;
        $Fee_arrears->term= $request->term;
         $Fee_arrears->year= $request->year;
      
       

        if($Fee_arrears->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Fee_arrears
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Fee_arrears = Fee_arrears::find($request->id );
        if(!empty($Fee_arrears))

                {
                  if($Fee_arrears->delete()){
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
