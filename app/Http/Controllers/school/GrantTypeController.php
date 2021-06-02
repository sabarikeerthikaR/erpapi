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
use App\Models\GrantType;

class GrantTypeController extends Controller
{
    public function store(Request $GrantType)
    {
      $validator =  Validator::make($GrantType->all(), [
            'name' => ['required', 'string'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $GrantType=GrantType::create([

        'name'  =>$GrantType->name,
       
         ]);
        if($GrantType->save()){
                  return response()->json([
                 'message'  => 'GrantType saved successfully',
                 'data'  => $GrantType 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $GrantType = GrantType::find($request->id);
             if(!empty($GrantType)){
                    return response()->json([
                    'data'  => $GrantType      
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
        $GrantType = GrantType::all();
        return response()->json(['status' => 'Success', 'data' => $GrantType]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'name' => ['required', 'string'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $GrantType = GrantType::find($request->id);
       $GrantType->name= $request->name;
       
        if($GrantType->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $GrantType
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $GrantType = GrantType::find($request->id);
        if(!empty($GrantType))

                {
                  if($GrantType->delete()){
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
