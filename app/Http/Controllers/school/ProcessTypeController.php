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
use App\Models\ProcessType;

class ProcessTypeController extends Controller
{
    public function store(Request $ProcessType)
    {
      $validator =  Validator::make($ProcessType->all(), [
            'name' => ['required', 'string'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $ProcessType=ProcessType::create([

        'name'  =>$ProcessType->name,
       
         ]);
        if($ProcessType->save()){
                  return response()->json([
                 'message'  => 'ProcessType saved successfully',
                 'data'  => $ProcessType 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $ProcessType = ProcessType::find($request->id);
             if(!empty($ProcessType)){
                    return response()->json([
                    'data'  => $ProcessType      
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
        $ProcessType = ProcessType::all();
        return response()->json(['status' => 'Success', 'data' => $ProcessType]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'name' => ['required', 'string'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $ProcessType = ProcessType::find($request->id);
       $ProcessType->name= $request->name;
       
        if($ProcessType->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $ProcessType
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $ProcessType = ProcessType::find($request->id);
        if(!empty($ProcessType))

                {
                  if($ProcessType->delete()){
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
