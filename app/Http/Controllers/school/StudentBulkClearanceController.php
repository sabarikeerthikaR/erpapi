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
use App\Models\Student_bulk_clearance;
use App\Models\Admission;

class StudentBulkClearanceController extends Controller
{
    public function store(Request $bulkclr)
    {
       $bulk=$bulkclr->bulk;
        $errors=[];
        foreach($bulk as $g)
        {
          if ($bulkclr->admission_id=='') 
          {
           return response()->json(apiResponseHandler([],'The admission_id field is required',400), 400);
          }
          if ($bulkclr->student_card_returned=='') 
          {
           return response()->json(apiResponseHandler([],'The student_card_returned field is required',400), 400);
          }
        
        $bulkclr = new Student_bulk_clearance(array(
          'department_id'=>$g['department_id'],
          'date'=>$g['date'], 
           'clear'=>$g['clear'],  
            'charge'=>$g['charge'],  
             'comment'=>$g['comment'],   
              'staff_id'=>$g['staff_id'],  
          'admission_id'=>$bulkclr->admission_id,
          'student_card_returned'=>$bulkclr->student_card_returned,
          'created_by'=>'admin'
         ));
          if(!$bulkclr->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'bulkclr saved successfully',
              'admission_id'=>$bulkclr->admission_id,
          'student_card_returned'=>$bulkclr->student_card_returned,
          'created_by'=>'admin',
          'data'=>$bulk,
                  ]);
              }
              else 
              {
                  return response()->json([
                   'message'  => 'failed',
                   'errors'=>$errors
                 ]);
               }
    }
public function show(request $request)
   {
   	 $Student_bulk_clearance = Student_bulk_clearance::find($request->stu_clearance_id);
             if(!empty($Student_bulk_clearance)){
                    return response()->json([
                    'data'  => $Student_bulk_clearance      
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
        $Student_bulk_clearance = Student_bulk_clearance::leftjoin('admission','student_bulk_clearance.admission_id','=','admission.admission_id')
        ->leftjoin('setings as clear','student_bulk_clearance.clear','=','clear.s_d')
        ->leftjoin('setings as card','student_bulk_clearance.clear','=','card.s_d')
        ->leftjoin('staff as staf','student_bulk_clearance.staff_id','=','staf.employee_id')
        ->leftjoin('department','student_bulk_clearance.department_id','=','department.department_id')
        ->select('student_bulk_clearance.date','charge',db::raw("CONCAT(admission.first_name,' ',admission.middle_name,' ',admission.last_name)as student")
    ,'department.name as department','clear.key_name as clear','stu_clearance_id','student_bulk_clearance.admission_id',db::raw("CONCAT(staf.first_name,' ',staf.middle_name,' ',staf.last_name)as confirmed_by")
    ,'card.key_name as student_card_returned','comment')
    ->get();
        return response()->json(['status' => 'Success', 'data' => $Student_bulk_clearance]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'admission_id' => ['required', 'numeric'],
            'student_card_returned' => ['required', 'string'],
            'department_id'    => ['required', 'numeric'],
            'date'  => ['required'],
            'clear'   => ['required', 'string'],
            'charge' => ['required','string'],
            'comment' => ['required', 'string'],
            'staff_id'  => ['required', 'numeric']
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Student_bulk_clearance = Student_bulk_clearance::find($request->stu_clearance_id);
        $Student_bulk_clearance->admission_id= $request->admission_id;
        $Student_bulk_clearance->student_card_returned= $request->student_card_returned;
        $Student_bulk_clearance->department_id= $request->department_id;
        $Student_bulk_clearance->date= $request->date;
        $Student_bulk_clearance->clear= $request->clear;
        $Student_bulk_clearance->charge= $request->charge;
        $Student_bulk_clearance->comment= $request->comment;
        $Student_bulk_clearance->staff_id= $request->staff_id;
        if($Student_bulk_clearance->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Student_bulk_clearance
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Student_bulk_clearance = Student_bulk_clearance::find($request->stu_clearance_id);
        if(!empty($Student_bulk_clearance))

                {
                  if($Student_bulk_clearance->delete()){
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
    public function view(request $request)
    {
      $id=$request->admission_id;
      $student=Admission::select('admission_no','admission_id',Db::raw("CONCAT(first_name,'',middle_name,'',last_name)as student"),'phone','residence')->where('admission_id','=',$id)->first();
      $clearance=Student_bulk_clearance::select('department_id','date','clear','charge','comment')->where('admission_id','=',$id)->get();
      if(!empty($student))
      {
        return response()->json([
          'message'=>'success',
          'student'=>$student,
          'data'=>$clearance
        ]);
      }else{
        return response()->json([
          'message'=>'No data found'
        ]);
      }
    }
}
