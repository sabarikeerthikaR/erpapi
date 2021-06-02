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
use App\Models\Fee_extras;

class FeeExtrasController extends Controller
{
   public function store(Request $Fee_extras)
    {
      $validator =  Validator::make($Fee_extras->all(), [
           'title' => ['required', 'string'],
            'fee_type' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
             'charged' => ['required', 'string'],
              'description' => ['required', 'string'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Fee_extras=Fee_extras::create([

        'title'  =>$Fee_extras->title ,
        'fee_type'  =>$Fee_extras->fee_type ,
        'amount'  =>$Fee_extras->amount ,
        'charged'  =>$Fee_extras->charged ,
        'description'  =>$Fee_extras->description ,
         ]);
        if($Fee_extras->save()){
                  return response()->json([
                 'message'  => 'Fee_extras saved successfully',
                 'data'  => $Fee_extras 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Fee_extras = Fee_extras::find($request->id);
             if(!empty($Fee_extras)){
                    return response()->json([
                    'data'  => $Fee_extras      
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
        $Fee_extras = Fee_extras::all();
        return response()->json(['status' => 'Success', 'data' => $Fee_extras]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
      'title' => ['required', 'string'],
            'fee_type' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
             'charged' => ['required', 'string'],
              'description' => ['required', 'string'],
          
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Fee_extras = Fee_extras::find($request->id);
        $Fee_extras->title = $request->title ;
        $Fee_extras->fee_type = $request->fee_type ;
        $Fee_extras->amount = $request->amount ;
         $Fee_extras->charged = $request->charged ;
          $Fee_extras->description = $request->description ;
       
        if($Fee_extras->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Fee_extras
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Fee_extras = Fee_extras::find($request->id);
        if(!empty($Fee_extras))

                {
                  if($Fee_extras->delete()){
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
