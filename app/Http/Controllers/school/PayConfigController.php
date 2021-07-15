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
use App\Models\PayeConfig;
use App\Models\Std_class;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class PayConfigController extends Controller
{
    public function store(Request $request)
    {
  
        $config=PayeConfig::create([
         'Range_From'  =>$request->Range_From,
         'Range_To'  =>$request->Range_To,
        'Tax_Percentage'  =>$request->Tax_Percentage,
        'Taxable_Amount'  =>$request->Taxable_Amount,
        
         ]);
        if($config->save()){
                  return response()->json([
                 'message'  => 'PayeConfig saved successfully',
                 'data'  => $PayeConfig 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $PayeConfig = PayeConfig::find($request->id);
             if(!empty($PayeConfig)){
                    return response()->json([
                    'data'  => $PayeConfig      
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
        $PayeConfig = PayeConfig::all();
        return response()->json(['status' => 'Success', 'data' => $PayeConfig]);
    }


public function update(Request $request)

   {

    $PayeConfig = PayeConfig::find($request->id);
  
        $PayeConfig->Range_From= $request->Range_From;
        $PayeConfig->Range_To= $request->Range_To;
        $PayeConfig->Tax_Percentage= $request->Tax_Percentage;
        $PayeConfig->Taxable_Amount= $request->Taxable_Amount;
        if($PayeConfig->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $PayeConfig
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $PayeConfig = PayeConfig::find($request->PayeConfig_id);
        if(!empty($PayeConfig))

                {
                  if($PayeConfig->delete()){
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
