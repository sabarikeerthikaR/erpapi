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
use App\Models\Discipline;
use App\Models\Admission;
use App\Models\Staff;
use App\Models\ClassRooms;

class DisciplineController extends Controller
{
    public function store(Request $Discipline)
    {
      $validator =  Validator::make($Discipline->all(), [
            'date' => ['required'],
            'culprit' => ['required'],
            'description' => ['required'],
            
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Discipline=Discipline::create([

        'date'  =>$Discipline->date ,
        'culprit'  =>$Discipline->culprit ,
        'description'  =>$Discipline->description ,
        'reported_by'  =>$Discipline->reported_by_mem ,
        'others'  =>$Discipline->others ,
         'notify_parent'  =>$Discipline->notify_parent ,
         'created_by'  =>'admin' ,
          
         ]); 
        if($Discipline->save()){
                  return response()->json([
                 'message'  => 'Discipline saved successfully',
                 'data'  => $Discipline 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Discipline = Discipline::find($request->id);
             if(!empty($Discipline)){
                    return response()->json([
                    'data'  => $Discipline      
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
        $Discipline = Discipline::join('admission','discipline.culprit','=','admission.admission_id')
        ->select('discipline.date',
                 DB::raw("CONCAT(admission.first_name,' ',COALESCE(admission.middle_name,''),' ',admission.last_name)as student"),
        'description as reason','discipline.status','discipline.id',
        )->get();
        return response()->json(['status' => 'Success', 'data' => $Discipline]);
    }


public function update(Request $request)

   {
    
    $Discipline = Discipline::find($request->id);
        $Discipline->date = $request->date ;
        $Discipline->culprit = $request->culprit ;
        $Discipline->reported_by = $request->reported_by_mem ;
        $Discipline->others = $request->others;
        $Discipline->description = $request->description ;
        $Discipline->action_taken = $request->action_taken ;
        $Discipline->comment = $request->comment ;
        $Discipline->status ='Action taken';
        if($Discipline->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Discipline
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Discipline = Discipline::find($request->id);
        if(!empty($Discipline))

                {
                  if($Discipline->delete()){
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

    public function action(Request $request)

   {
       $date=date("d-m-Y");
    
    $Discipline = Discipline::find($request->id);
        $Discipline->date = $Discipline->date ;
        $Discipline->culprit = $Discipline->culprit ;
        $Discipline->reported_by = $Discipline->reported_by ;
        $Discipline->others = $Discipline->others;
        $Discipline->description = $Discipline->description ;
        $Discipline->action_taken = $request->action_taken ;
        $Discipline->comment = $request->comment ;
        $Discipline->action_taken_on=$date;
        if($Discipline->save()){
            return response()->json([
                 'message'  => 'action added successfully',
                 'data'  => $Discipline
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
    public function profile(request $request)
    {
        $id=$request->id;
        $profile=Discipline::leftjoin('admission','discipline.culprit','=','admission.admission_id')
        ->leftjoin('staff','discipline.reported_by','=','staff.employee_id')
        ->leftjoin('add_stream','admission.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('setings','admission.gender','=','setings.s_d')
        ->select('admission.admission_no as ADM_No','admission.date as ADM_Date',
        'setings.key_name as gender','admission.email','std_class.name as class','class_stream.name as stream',
        'discipline.date as date_reported','discipline.description as reason',
        db::raw("CONCAT(staff.first_name,' ',staff.middle_name,' ',staff.last_name) as reported_by"),
        db::raw("CONCAT(admission.first_name,' ',admission.middle_name,' ',admission.last_name) as student"),
    'discipline.others as reported_by','discipline.created_by','discipline.action_taken','discipline.comment',
    'discipline.action_taken_on')->where('discipline.id',$id)->first();
      
        if(!empty($profile))
        {
            return response()->json([
                'message'=>'success',
                'data'=>$profile,
            ]);
        }else{
            return response()->json([
                'message'=>'data not found'
            ]);
        }
    }
}
