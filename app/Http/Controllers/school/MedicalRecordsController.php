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
use App\Models\MedicalRecords;

class MedicalRecordsController extends Controller
{
     public function store(Request $MedicalRecords)
    {
    	 $validator =  Validator::make($MedicalRecords->all(), [
            'student' => ['required'],
            'sickness' => ['required'],
            'action_taken' => ['required'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $MedicalRecords=MedicalRecords::create([
        	'date'    =>$MedicalRecords->date,
        'student'    =>$MedicalRecords->student,
        'sickness'          =>$MedicalRecords->sickness,
        'notify_parent'         =>$MedicalRecords->notify_parent,
        'action_taken'        =>$MedicalRecords->action_taken,
        'comment'        =>$MedicalRecords->comment,  
        'comment'        =>$MedicalRecords->comment,  
        'action_taken_by'        =>Auth::user()->id,  
         ]);
        if($MedicalRecords->save()){
                  return response()->json([
                 'message'  => 'MedicalRecords saved successfully',
                 'data'  => $MedicalRecords 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $MedicalRecords = MedicalRecords::find($request->id);
             if(!empty($MedicalRecords)){
                    return response()->json([
                    'data'  => $MedicalRecords      
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
        $MedicalRecords = MedicalRecords::join('admission','medical_records.student','=','admission.admission_id')
        ->join('users','medical_records.action_taken_by','=','users.id')
        ->select(db::raw("CONCAT(admission.first_name,' ',COALESCE(admission.middle_name,''),' ',admission.last_name) as student"),
    'sickness','notify_parent',db::raw("CONCAT(users.first_name,' ',COALESCE(users.middle_name,''),' ',users.last_name) as action_taken_by"),
    'comment','action_taken','medical_records.id','medical_records.date')->get();
        return response()->json(['gender' => 'Success', 'data' => $MedicalRecords]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
           'student' => ['required'],
            'sickness' => ['required'],
            'action_taken' => ['required'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $MedicalRecords = MedicalRecords::find($request->id);
       
       $MedicalRecords->date= $request->date;
       $MedicalRecords->student= $request->student;
       $MedicalRecords->sickness= $request->sickness;
       $MedicalRecords->notify_parent= $request->notify_parent;
       $MedicalRecords->action_taken= $request->action_taken;
       $MedicalRecords->comment= $request->comment;
      
        if($MedicalRecords->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $MedicalRecords
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $MedicalRecords = MedicalRecords::find($request->id);
        if(!empty($MedicalRecords))

                {
                  if($MedicalRecords->delete()){
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
