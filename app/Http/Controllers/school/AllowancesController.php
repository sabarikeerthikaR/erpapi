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
use App\Models\Allowances;

class AllowancesController extends Controller
{
    public function store(Request $Allowances)
    {
      $validator =  Validator::make($Allowances->all(), [
            'name' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Allowances=Allowances::create([

        'name'  =>$Allowances->name,
        'amount'  =>$Allowances->amount,
        
         ]);
        if($Allowances->save()){
                  return response()->json([
                 'message'  => 'Allowances saved successfully',
                 'data'  => $Allowances 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Allowances = Allowances::find($request->id);
             if(!empty($Allowances)){
                    return response()->json([
                    'data'  => $Allowances      
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
        $Allowances = Allowances::all();
        return response()->json(['status' => 'Success', 'data' => $Allowances]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
          'name' => ['required', 'string'],
          'amount' => ['required', 'numeric'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Allowances = Allowances::find($request->id);
       $Allowances->name= $request->name;
       $Allowances->amount= $request->amount;
       
      
        if($Allowances->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Allowances
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Allowances = Allowances::find($request->id);
        if(!empty($Allowances))

                {
                  if($Allowances->delete()){
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
