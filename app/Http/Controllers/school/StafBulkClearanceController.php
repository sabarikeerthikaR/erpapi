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
use App\Models\Staf_bulk_clearance;

class StafBulkClearanceController extends Controller
{
    public function store(Request $Staf_bulk_clearance)
    {
      $validator =  Validator::make($Staf_bulk_clearance->all(), [
            'staff_id' => ['required'],
            'department_id' => ['required'],
            'date'  => ['required'],
            'clear'   => ['required'],
            'staff_id_2'  => ['required']
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Staf_bulk_clearance=Staf_bulk_clearance::create([

        'staff_id'  =>$Staf_bulk_clearance->staff_id,
        'department_id' =>$Staf_bulk_clearance->department_id,
        'date'        =>$Staf_bulk_clearance->date,
        'clear'        =>$Staf_bulk_clearance->clear,
        'charge'        =>$Staf_bulk_clearance->charge,
        'comment'        =>$Staf_bulk_clearance->comment,
        'staff_id_2'        =>$Staf_bulk_clearance->staff_id_2
         ]);
        if($Staf_bulk_clearance->save()){
                  return response()->json([
                 'message'  => 'Staf_bulk_clearance saved successfully',
                 'data'  => $Staf_bulk_clearance 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Staf_bulk_clearance = Staf_bulk_clearance::find($request->staf_clearance_id);
             if(!empty($Staf_bulk_clearance)){
                    return response()->json([
                    'data'  => $Staf_bulk_clearance      
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
        $Staf_bulk_clearance = Staf_bulk_clearance::leftjoin('staff','staf_bulk_clearance.staff_id','=','staff.employee_id')
        ->leftjoin('setings as clear','staf_bulk_clearance.clear','=','clear.s_d')
        ->leftjoin('staff as staf','staf_bulk_clearance.staff_id_2','=','staf.employee_id')
        ->leftjoin('department','staf_bulk_clearance.department_id','=','department.department_id')
        ->select('date','charge','comment',db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),' ',staff.last_name)as staff")
    ,'department.name as department','clear.key_name as clear','staf_clearance_id','staff_id',db::raw("CONCAT(staf.first_name,' ',COALESCE(staff.middle_name,'')e,' ',staf.last_name)as confirmed_by"))
    ->get();
        return response()->json(['status' => 'Success', 'data' => $Staf_bulk_clearance]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
            'staff_id' => ['required','numeric'],
            'department_id' => ['required', 'numeric'],
            'date'  => ['required'],
            'clear'   => ['required', 'string'],
            'charge' => ['required','string'],
            'comment' => ['required', 'string'],
            'staff_id_2'  => ['required', 'numeric']
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Staf_bulk_clearance = Staf_bulk_clearance::find($request->staf_clearance_id);
        $Staf_bulk_clearance->staff_id= $request->staff_id;
        $Staf_bulk_clearance->department_id= $request->department_id;
        $Staf_bulk_clearance->date= $request->date;
        $Staf_bulk_clearance->clear= $request->clear;
        $Staf_bulk_clearance->charge= $request->charge;
        $Staf_bulk_clearance->comment= $request->comment;
        $Staf_bulk_clearance->staff_id_2= $request->staff_id_2;
        if($Staf_bulk_clearance->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Staf_bulk_clearance
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Staf_bulk_clearance = Staf_bulk_clearance::find($request->staf_clearance_id);
        if(!empty($Staf_bulk_clearance))

                {
                  if($Staf_bulk_clearance->delete()){
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
