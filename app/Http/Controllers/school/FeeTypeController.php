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
use App\Models\Fee_type;

class FeeTypeController extends Controller
{
   public function store(Request $Fee_type)
    {
      $validator =  Validator::make($Fee_type->all(), [
           'name' => ['required', 'string'],
         
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Fee_type=Fee_type::create([

        'name'  =>$Fee_type->name ,
      
         ]);
        if($Fee_type->save()){
                  return response()->json([
                 'message'  => 'Fee_type saved successfully',
                 'data'  => $Fee_type 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Fee_type = Fee_type::find($request->id);
             if(!empty($Fee_type)){
                    return response()->json([
                    'data'  => $Fee_type      
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
        $Fee_type = Fee_type::all();
        return response()->json(['status' => 'Success', 'data' => $Fee_type]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
       'name' => ['required', 'string'],
            
          
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Fee_type = Fee_type::find($request->id);
        $Fee_type->name = $request->name ;
       
        if($Fee_type->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Fee_type
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Fee_type = Fee_type::find($request->id);
        if(!empty($Fee_type))

                {
                  if($Fee_type->delete()){
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
