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
use App\Models\Terms;

class TermsController extends Controller
{
    public function store(Request $Terms)
    {
      $validator =  Validator::make($Terms->all(), [
            'name' => ['required', 'string'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Terms=Terms::create([

        'name'  =>$Terms->name ,
        
         ]);
        if($Terms->save()){
                  return response()->json([
                 'message'  => 'Terms saved successfully',
                 'data'  => $Terms 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Terms = Terms::find($request->term_id);
             if(!empty($Terms)){
                    return response()->json([
                    'data'  => $Terms      
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
        $Terms = Terms::all();
        return response()->json(['status' => 'Success', 'data' => $Terms]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
          'name' => ['required', 'string'],
          
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Terms = Terms::find($request->term_id);
        $Terms->name = $request->name ;
       
        if($Terms->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Terms
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Terms = Terms::find($request->term_id);
        if(!empty($Terms))

                {
                  if($Terms->delete()){
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
