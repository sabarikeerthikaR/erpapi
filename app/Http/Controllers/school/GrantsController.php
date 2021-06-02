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
use App\Models\Grants;

class GrantsController extends Controller
{
   public function store(Request $Grants)
    {
      $validator =  Validator::make($Grants->all(), [
            'grant_type' => ['required', 'string'],
            'date' => ['required'],
            'amount'    => ['required', 'numeric'],
            'payment_method'  => ['required', 'string'],
             'bank_deposited' => ['required', 'string'],
            'purpose' => ['required', 'string'],
            'school_representative'    => ['required', 'string'],
             'contact_person' => ['required', 'string'],
            'contact_details' => ['required', 'string'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Grants=Grants::create([

        'grant_type'  =>$Grants->grant_type,
        'date'  =>$Grants->date,
        'amount'    =>$Grants->amount,
        'payment_method' =>$Grants->payment_method,
        'bank_deposited'  =>$Grants->bank_deposited,
        'purpose'   =>$Grants->purpose,
        'school_representative' =>$Grants->school_representative,
       'add_file'  =>$Grants->add_file,
        'contact_person'    =>$Grants->contact_person,
        'contact_details'   =>$Grants->contact_details,
       
       
         ]);
        if($Grants->save()){
                  return response()->json([
                 'message'  => 'Grants saved successfully',
                 'data'  => $Grants 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Grants = Grants::find($request->id);
             if(!empty($Grants)){
                    return response()->json([
                    'data'  => $Grants      
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
        $Grants = Grants::all();
        return response()->json(['status' => 'Success', 'data' => $Grants]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
          'grant_type' => ['required', 'string'],
            'date' => ['required'],
            'amount'    => ['required', 'numeric'],
            'payment_method'  => ['required', 'string'],
             'bank_deposited' => ['required', 'string'],
            'purpose' => ['required', 'string'],
            'school_representative'    => ['required', 'string'],
             'contact_person' => ['required', 'string'],
            'contact_details' => ['required', 'string'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Grants = Grants::find($request->id);
       $Grants->grant_type= $request->grant_type;
       $Grants->date= $request->date;
       $Grants->amount= $request->amount;
       $Grants->payment_method= $request->payment_method;
       $Grants->bank_deposited= $request->bank_deposited;
       $Grants->purpose= $request->purpose;
       $Grants->school_representative= $request->school_representative;
       $Grants->add_file= $request->add_file;
       $Grants->contact_person= $request->contact_person;
       $Grants->contact_details= $request->contact_details;
      
        if($Grants->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Grants
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Grants = Grants::find($request->id);
        if(!empty($Grants))

                {
                  if($Grants->delete()){
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
