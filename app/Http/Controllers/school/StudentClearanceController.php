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
use App\Models\StudentClearance;

class StudentClearanceController extends Controller
{
    public function store(Request $StudentClearance)
    {
      $validator =  Validator::make($StudentClearance->all(), [
            'student' => ['required'],
            'department' => ['required'],
            'date'  => ['required'],
            'cleard'   => ['required'],
            'charge'  => ['required'],
            'confirmed_by'  => ['required']
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $StudentClearance=StudentClearance::create([

        'student'  =>$StudentClearance->student,
        'department' =>$StudentClearance->department,
        'date'        =>$StudentClearance->date,
        'cleard'        =>$StudentClearance->cleard,
        'charge'        =>$StudentClearance->charge,
        'confirmed_by'        =>$StudentClearance->confirmed_by,
        'pending_items'        =>$StudentClearance->pending_items,	
         ]);
        if($StudentClearance->save()){
                  return response()->json([
                 'message'  => 'StudentClearance saved successfully',
                 'data'  => $StudentClearance 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $StudentClearance = StudentClearance::find($request->id);
             if(!empty($StudentClearance)){
                    return response()->json([
                    'data'  => $StudentClearance      
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
        $StudentClearance = StudentClearance::leftjoin('admission','student_clearance.student','=','admission.admission_id')
        ->leftjoin('setings as clear','student_clearance.cleard','=','clear.s_d')
        ->leftjoin('staff as staf','student_clearance.confirmed_by','=','staf.employee_id')
        ->leftjoin('department','student_clearance.department','=','department.department_id')
        ->select('student_clearance.date',
            'charge',
            db::raw("CONCAT(admission.first_name,' ',COALESCE(admission.middle_name,''),' ',admission.last_name)as student")
    ,'department.name as department',
    'clear.key_name as clear',
    'admission_id',
    'student_clearance.id',
    db::raw("CONCAT(staf.first_name,' ',COALESCE(staf.middle_name,''),' ',staf.last_name)as confirmed_by")
    ,'pending_items')
    ->get();
        return response()->json(['status' => 'Success', 'data' => $StudentClearance]);
  }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
            'student' => ['required'],
            'department' => ['required'],
            'date'  => ['required'],
            'cleard'   => ['required'],
            'charge' => ['required'],
            'confirmed_by' => ['required'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $StudentClearance = StudentClearance::find($request->id);
        $StudentClearance->student= $request->student;
        $StudentClearance->department= $request->department;
        $StudentClearance->date= $request->date;
        $StudentClearance->cleard= $request->cleard;
        $StudentClearance->charge= $request->charge;
        $StudentClearance->confirmed_by= $request->confirmed_by;
        $StudentClearance->pending_items= $request->pending_items;
        if($StudentClearance->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $StudentClearance
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $StudentClearance = StudentClearance::find($request->id);
        if(!empty($StudentClearance))

                {
                  if($StudentClearance->delete()){
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
