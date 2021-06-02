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
use App\Models\Fee_structure;

class FeeStructureController extends Controller
{
    public function store(Request $Fee_structure)
    {
      $validator =  Validator::make($Fee_structure->all(), [
           'term' => ['required', 'string'],
            'class' => ['required', 'string'],
            'fee_amount' => ['required', 'numeric'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Fee_structure=Fee_structure::create([

        'term'  =>$Fee_structure->term ,
        'class'  =>$Fee_structure->class ,
        'fee_amount'  =>$Fee_structure->fee_amount ,
         ]);
        if($Fee_structure->save()){
                  return response()->json([
                 'message'  => 'Fee_structure saved successfully',
                 'data'  => $Fee_structure 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Fee_structure = Fee_structure::find($request->id);
             if(!empty($Fee_structure)){
                    return response()->json([
                    'data'  => $Fee_structure      
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
        $Fee_structure = Fee_structure::all();
        return response()->json(['status' => 'Success', 'data' => $Fee_structure]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
       'term' => ['required', 'string'],
            'class' => ['required', 'string'],
            'fee_amount' => ['required', 'numeric'],
          
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Fee_structure = Fee_structure::find($request->id);
        $Fee_structure->term = $request->term ;
        $Fee_structure->class = $request->class ;
        $Fee_structure->fee_amount = $request->fee_amount ;
       
        if($Fee_structure->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Fee_structure
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Fee_structure = Fee_structure::find($request->id);
        if(!empty($Fee_structure))

                {
                  if($Fee_structure->delete()){
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
