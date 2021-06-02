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
use App\Models\Year;

class YearController extends Controller
{
     public function store(Request $Year)
    {
      $validator =  Validator::make($Year->all(), [
           'Year' => ['required', 'string'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Year=Year::create([

        'Year'  =>$Year->Year ,
      
         ]);
        if($Year->save()){
                  return response()->json([
                 'message'  => 'Year saved successfully',
                 'data'  => $Year 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Year = Year::find($request->id);
             if(!empty($Year)){
                    return response()->json([
                    'data'  => $Year      
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
        $Year = Year::all();
        return response()->json(['status' => 'Success', 'data' => $Year]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
      'Year' => ['required', 'string'],
           
          
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Year = Year::find($request->id);
        $Year->Year = $request->Year ;
       
       
        if($Year->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Year
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Year = Year::find($request->id);
        if(!empty($Year))

                {
                  if($Year->delete()){
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
