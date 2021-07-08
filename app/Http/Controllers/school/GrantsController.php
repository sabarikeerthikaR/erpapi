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
      $valiDationArray = [
            'grant_type' => ['required'],
            'date' => ['required'],
            'amount'    => ['required'],
            'payment_method'  => ['required'],
             'bank_deposited' => ['required'],
            'purpose' => ['required'],
            'school_representative'    => ['required'],
             'contact_person' => ['required'],
            'contact_details' => ['required'],
            
          ]; 
         if($Grants->add_file)
        {
          $valiDationArray["add_file"]='required|file';
        }
        $validator =  Validator::make($Grants->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $add_file='';
         if($Grants->file('add_file')){
         $add_file = $Grants->file('add_file');
         $imgName = time() . '.' . pathinfo($add_file->getClientOriginalName(), PATHINFO_EXTENSION);
         Storage::disk('public_uploads')->put('/grants-file/' . $imgName, file_get_contents($add_file));
         $add_file=config('app.url').'/public/uploads/grants-file/' . $imgName;
         }
        $Grants=Grants::create([

        'grant_type'  =>$Grants->grant_type,
        'date'  =>$Grants->date,
        'amount'    =>$Grants->amount,
        'payment_method' =>$Grants->payment_method,
        'bank_deposited'  =>$Grants->bank_deposited,
        'purpose'   =>$Grants->purpose,
        'school_representative' =>$Grants->school_representative,
       'add_file'  =>$add_file,
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
        $Grants = Grants::leftjoin('setings as grant_type','grants.grant_type','=','grant_type.s_d')
        ->leftjoin('setings as payment_method','grants.payment_method','=','payment_method.s_d')
        ->leftjoin('bank_account','grants.bank_deposited','=','bank_account.id')
         ->leftjoin('setings','bank_account.bank_name','=','setings.s_d')
        ->select('date','amount','purpose','school_representative','add_file','contact_person','contact_details',
                 'grant_type.key_name as grant_type','payment_method.key_name as payment_method',
                 db::raw("CONCAT(setings.key_name,' ',bank_account.account_no)as bank_deposited"),'grants.id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Grants]);
    }


public function update(Request $request)

   {
    $valiDationArray = [
          'grant_type' => ['required'],
            'date' => ['required'],
            'amount'    => ['required'],
            'payment_method'  => ['required'],
             'bank_deposited' => ['required'],
            'purpose' => ['required'],
            'school_representative'    => ['required'],
             'contact_person' => ['required'],
            'contact_details' => ['required'],
            
        ]; 
         if($request->add_file)
        {
          $valiDationArray["add_file"]='required|file';
        }
        $validator =  Validator::make($request->all(),$valiDationArray); 
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
         }
       $Grants = Grants::find($request->id);
          if($request->file('add_file')){
              $add_file = $request->file('add_file');
              $imgName = time() . '.' . pathinfo($add_file->getClientOriginalName(), PATHINFO_EXTENSION);
              Storage::disk('public_uploads')->put('/grants-file/' . $imgName, file_get_contents($add_file));
              $add_file=config('app.url').'/public/uploads/grants-file/' . $imgName;
              $request->add_file=$add_file;
              }
       $Grants->grant_type= $request->grant_type;
       $Grants->date= $request->date;
       $Grants->amount= $request->amount;
       $Grants->payment_method= $request->payment_method;
       $Grants->bank_deposited= $request->bank_deposited;
       $Grants->purpose= $request->purpose;
       $Grants->school_representative= $request->school_representative;
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
