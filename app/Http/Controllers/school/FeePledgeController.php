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
use App\Models\Fee_pledge;

class FeePledgeController extends Controller
{
   public function store(Request $Fee_pledge)
    {
      $validator =  Validator::make($Fee_pledge->all(), [
            'student'       => ['required'],
            'pledge_date'=> ['required'],
            'amount'       => ['required'],
            'status'       => ['required'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Fee_pledge=Fee_pledge::create([
        'student'  =>$Fee_pledge->student,
        'pledge_date'  =>$Fee_pledge->pledge_date,
        'amount'  =>$Fee_pledge->amount,
        'status'  =>$Fee_pledge->status,
        'remark'  =>$Fee_pledge->remark,
       
         ]);
        if($Fee_pledge->save()){
                  return response()->json([
                 'message'  => 'Fee_pledge saved successfully',
                 'data'  => $Fee_pledge 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Fee_pledge = Fee_pledge::find($request->id);
             if(!empty($Fee_pledge)){
                    return response()->json([
                    'data'  => $Fee_pledge      
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
        $Fee_pledge = Fee_pledge::leftjoin('admission','fee_pledge.student','=','admission.admission_id')
        ->leftjoin('setings','fee_pledge.status','=','setings.s_d')
        ->select('pledge_date','amount','setings.key_name as status','remark',
                 db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),'id')->get();
        return response()->json(['status' => 'Success', 'data' => $Fee_pledge]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
             'student'       => ['required'],
            'pledge_date'=> ['required'],
            'amount'       => ['required'],
            'status'       => ['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Fee_pledge = Fee_pledge::find($request->id);
       $Fee_pledge->student= $request->student;
         $Fee_pledge->pledge_date= $request->pledge_date;
         $Fee_pledge->amount= $request->amount;
        $Fee_pledge->status= $request->status;
        $Fee_pledge->remark= $request->remark;

        if($Fee_pledge->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Fee_pledge
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Fee_pledge = Fee_pledge::find($request->id );
        if(!empty($Fee_pledge))

                {
                  if($Fee_pledge->delete()){
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
