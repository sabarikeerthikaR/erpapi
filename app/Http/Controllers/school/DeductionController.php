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
use App\Models\Deduction;
use App\Models\Settings;

class DeductionController extends Controller
{
     public function store(Request $Deduction)
    {
      $validator =  Validator::make($Deduction->all(), [
            
            'amount' => ['required'],
   
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Deduction=Deduction::create([

        'name'  =>$Deduction->name,
        'amount'  =>$Deduction->amount,
       
       
         ]);
         $settings=Settings::create([
            'group_name'=>'deduction',
            'key_name'=>$Deduction->name,
            'key_value'=>$Deduction->id,
            ]);
            $settings->save();
        if($Deduction->save()){
                  return response()->json([
                 'message'  => 'Deduction saved successfully',
                 'data'  => $Deduction 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Deduction = Deduction::find($request->id);
             if(!empty($Deduction)){
                    return response()->json([
                    'data'  => $Deduction      
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
        $Deduction = Deduction::all();
        return response()->json(['status' => 'Success', 'data' => $Deduction]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
              
            'amount' => ['required'],
   
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Deduction = Deduction::find($request->id);
       $Deduction->name= $request->name;
       $Deduction->amount= $request->amount;
       $settings=Settings::where('group_name','=','deduction')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
        if($Deduction->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Deduction
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Deduction = Deduction::find($request->id);
         $settings=Settings::where('group_name','=','deduction')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($Deduction))

                {
                  if($Deduction->delete()){
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
