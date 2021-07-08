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
use App\Models\Settings;

class AllowancesController extends Controller
{
    public function store(Request $Allowances)
    {
      $validator =  Validator::make($Allowances->all(), [
          
            'amount' => ['required'],
            
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Allowances=Allowances::create([

        'name'  =>$Allowances->name,
        'amount'  =>$Allowances->amount,
        
         ]);
         $settings=Settings::create([
            'group_name'=>'allowances',
            'key_name'=>$Allowances->name,
            'key_value'=>$Allowances->id,
            ]);
            $settings->save();
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
         'amount' => ['required'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Allowances = Allowances::find($request->id);
       $Allowances->name= $request->name;
       $Allowances->amount= $request->amount;
        $settings=Settings::where('group_name','=','allowances')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
      
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
        $settings=Settings::where('group_name','=','allowances')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
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
