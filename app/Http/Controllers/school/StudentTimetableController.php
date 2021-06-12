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
use App\Models\StudentTimetable;

class StudentTimetableController extends Controller
{
     public function store(Request $StudentTimetable)
    {
        $data=$StudentTimetable->data;
        $errors=[];
        foreach($data as $g)
        {
          if ($StudentTimetable->class=='') 
          {
           return response()->json(apiResponseHandler([],'The class field is required',400), 400);
          }
        
        $StudentTimetable = new StudentTimetable(array(
          'start_time'   =>$g['start_time'],
          'end_time'=>$g['end_time'],
          'day'=>$g['day'],  
          'subject'=>$g['subject'],
          'class'=>$StudentTimetable->class,
         ));
          if(!$StudentTimetable->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'StudentTimetable saved successfully',
              'data'=>$data,
              'class'=>$StudentTimetable->class
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
    	       $StudentTimetable = StudentTimetable::find($request->id);
             if(!empty($StudentTimetable)){
                    return response()->json([
                    'data'  => $StudentTimetable      
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
        $StudentTimetable = StudentTimetable::all();
        return response()->json(['knec_code' => 'Success', 'data' => $StudentTimetable]);
    }

    public function studentMyTimetable(request $request)
    {
        $StudentTimetable = StudentTimetable::leftjoin('add_stream','student_timetable.class','=','add_stream.id')
        ->leftjoin('admission','student_timetable.class','=','admission.class')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->join('subjects','student_timetable.subject','=','subjects.subject_id')
        ->join('teacher_timetable','student_timetable.class','=','teacher_timetable.class')
        ->join('staff','teacher_timetable.staff','=','staff.employee_id')
        ->leftjoin('setings as day','student_timetable.day','=','day.s_d')
        ->where('student_timetable.day',$request->day)->where('admission.admission_id',$request->admission_id)
        ->select('std_class.name as class','class_stream.name as stream',
        db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),' ',staff.last_name)as staff_name"),
        'subjects.name as subject','student_timetable.start_time','student_timetable.end_time','day.key_name as day')
        ->groupBy('student_timetable.id')
        ->get();
        foreach ($StudentTimetable as $group) {
          $grouped[] =  $group->groupBy();
      }
        // ->
        
        return response()->json([
            'message'=>'success',
            'data'=>$StudentTimetable
        ]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
   	 	'start_time' => ['required','string'],
            'end_time' => ['required', 'string'],
            'monday' => ['required', 'string'],
            'tuesday' => ['required', 'string'],
            'wednesday' => ['required', 'string'],
            'thursday' => ['required','string'],
            'friday' => ['required', 'string'],
              'saturday' => ['required', 'string'],
            
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $StudentTimetable = StudentTimetable::find($request->id);
        $StudentTimetable->start_time= $request->start_time;
       $StudentTimetable->end_time= $request->end_time;
       $StudentTimetable->monday= $request->monday;
       $StudentTimetable->tuesday= $request->tuesday;
       $StudentTimetable->wednesday= $request->wednesday;
       $StudentTimetable->thursday= $request->thursday;
       $StudentTimetable->friday= $request->friday;
        $StudentTimetable->saturday= $request->saturday;
       $StudentTimetable->sunday= $request->sunday;
       
        if($StudentTimetable->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $StudentTimetable
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $StudentTimetable = StudentTimetable::find($request->id);
        if(!empty($StudentTimetable))

                {
                  if($StudentTimetable->delete()){
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
