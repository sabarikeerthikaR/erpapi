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

class BankAccountController extends Controller
{
    public function store(Request $Bank_account)
    {
      $validator =  Validator::make($Bank_account->all(), [
            'bank_name' => ['required', 'string'],
            'account_name' => ['required', 'string'],
            'account_no'    => ['required', 'numeric'],
            'branch'  => ['required', 'string'],
            'description'   => ['required','string'],
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
        $Bank_account = Bank_account::all();
        return response()->json(['status' => 'Success', 'data' => $Bank_account]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'bank_name' => ['required', 'string'],
            'account_name' => ['required', 'string'],
            'account_no'    => ['required', 'numeric'],
            'branch'  => ['required', 'string'],
            'description'   => ['required','string'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Bank_account = Bank_account::find($request->id);
       $Bank_account->bank_name= $request->title;
       $Bank_account->account_name= $request->account_name;
       $Bank_account->account_no= $request->account_no;
       $Bank_account->branch= $request->branch;
       $Bank_account->description= $request->description;
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
