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
use App\Models\Contracts;
use App\Models\Settings;

class ContractsController extends Controller
{
    public function store(Request $Contracts)
    {
    	 $validator =  Validator::make($Contracts->all(), [
    	 	'name' => ['required','string'],
    	 	
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Contracts=Contracts::create([
        	'name'=>$Contracts->name,
        	'description'=>$Contracts->description,
        ]);
        $Contracts->save();
         $id=$Contracts->contract_id;
         $settings=Settings::create([
            'group_name'=>'contracts',
            'key_name'=>$Contracts->name,
            'key_value'=>$id,
            ]);
            $settings->save();
        if($Contracts->save()){
                  return response()->json([
                 'message'  => 'Contracts saved successfully',
                 'data'  => $Contracts 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Contracts = Contracts::find($request->contract_id);
             if(!empty($Contracts)){
                    return response()->json([
                    'data'  => $Contracts      
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
        $Contracts = Contracts::all();
        return response()->json(['status' => 'Success', 'data' => $Contracts]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
   	 	'name' => ['required','string'],
   	 	   
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Contracts = Contracts::find($request->contract_id);
       
       $Contracts->name= $request->name;
       $Contracts->description= $request->description;
       $settings=Settings::where('group_name','=','contracts')->where('key_value',$request->contract_id)->first();
       $settings->key_name= $request->name;
       $settings->save();
        if($Contracts->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Contracts
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Contracts = Contracts::find($request->contract_id);
        $settings=Settings::where('group_name','=','contracts')->where('key_value',$request->contract_id)->first();
       $settings->group_name=NULL;
       $settings->key_value=NULL;
       $settings->key_name=NULL;
       $settings->save();
        if(!empty($Contracts))

                {
                  if($Contracts->delete()){
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
