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
use App\Models\Bank_account;
use App\Models\Settings;
use App\Models\Bank_name;

class BankAccountController extends Controller
{
    public function store(Request $Bank_account)
    {
      $validator =  Validator::make($Bank_account->all(), [
            'bank_name' => ['required'],
            'account_name' => ['required'],
            'account_no'    => ['required'],
            'branch'  => ['required'],
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Bank_account=Bank_account::create([

        'bank_name'  =>$Bank_account->bank_name,
        'account_name'  =>$Bank_account->account_name,
        'account_no'    =>$Bank_account->account_no,
        'branch'        =>$Bank_account->branch,
        'description'   =>$Bank_account->description,
         ]);
        $bankName=Settings::where('s_d',$Bank_account->bank_name)
        ->select('key_name')->first();
        $settings=Settings::create([
            'group_name'=>'bank_account',
            'key_name'=>$bankName->name.' '.$Bank_account->account_no,
            'key_value'=>$Bank_account->id,
            ]);
            $settings->save();
        if($Bank_account->save()){
                  return response()->json([
                 'message'  => 'Bank_account saved successfully',
                 'data'  => $Bank_account 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Bank_account = Bank_account::find($request->id);
             if(!empty($Bank_account)){
                    return response()->json([
                    'data'  => $Bank_account      
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
        $Bank_account = Bank_account::leftjoin('setings','bank_account.bank_name','=','setings.s_d')
        ->select('setings.key_name as bank_name','account_name','account_no','branch','description','bank_account.id')->get();
        return response()->json(['status' => 'Success', 'data' => $Bank_account]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'bank_name' => ['required'],
            'account_name' => ['required'],
            'account_no'    => ['required'],
            'branch'  => ['required'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }

    $Bank_account = Bank_account::find($request->id);
       $Bank_account->bank_name= $request->bank_name;
       $Bank_account->account_name= $request->account_name;
       $Bank_account->account_no= $request->account_no;
       $Bank_account->branch= $request->branch;
       $Bank_account->description= $request->description;
       $bankName=Settings::where('key_value',$request->bank_name)
        ->select('key_name')->first();
$settings=Settings::where('group_name','=','bank_account')->where('key_value',$request->id)->first();
        $settings->key_name= $bankName->key_name.' '.$request->account_no;
        $settings->save();  
        if($Bank_account->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Bank_account
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Bank_account = Bank_account::find($request->id);
        $settings=Settings::where('group_name','=','bank_account')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->key_name=NULL;
        $settings->save();  
        if(!empty($Bank_account))

                {
                  if($Bank_account->delete()){
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
