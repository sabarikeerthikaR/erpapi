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
use App\Models\Petty_cash;
use Illuminate\Support\Facades\Auth;

class PettyCashController extends Controller
{
    public function store(Request $Petty_cash)
    {
      $validator =  Validator::make($Petty_cash->all(), [
            'petty_date' => ['required'],
            'ammount'    => ['required'],
            'person_responsible'  => ['required'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Petty_cash=Petty_cash::create([

        'petty_date'  =>$Petty_cash->petty_date,
        'description'  =>$Petty_cash->description,
        'ammount'    =>$Petty_cash->ammount,
        'person_responsible'        =>$Petty_cash->person_responsible,
        'created_by'        =>auth::user()->id,
       
         ]);
        if($Petty_cash->save()){
                  return response()->json([
                 'message'  => 'Petty_cash saved successfully',
                 'data'  => $Petty_cash 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Petty_cash = Petty_cash::find($request->id);
             if(!empty($Petty_cash)){
                    return response()->json([
                    'data'  => $Petty_cash      
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
        $Petty_cash = Petty_cash::leftjoin('staff','petty_cash.person_responsible','=','staff.employee_id')
        ->leftjoin('users','petty_cash.created_by','=','users.id')
        ->select(db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),' ',staff.last_name)as person_responsible"),
        db::raw("CONCAT(users.first_name,' ',COALESCE(users.middle_name,''),' ',users.last_name)as created_by"),
                  'petty_date','description','ammount','petty_cash.id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Petty_cash]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
         'petty_date' => ['required'],
            'ammount'    => ['required'],
            'person_responsible'  => ['required'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Petty_cash = Petty_cash::find($request->id);
       $Petty_cash->petty_date= $request->petty_date;
       $Petty_cash->description= $request->description;
       $Petty_cash->ammount= $request->ammount;
       $Petty_cash->person_responsible= $request->person_responsible;
      
        if($Petty_cash->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Petty_cash
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Petty_cash = Petty_cash::find($request->id);
        if(!empty($Petty_cash))

                {
                  if($Petty_cash->delete()){
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
