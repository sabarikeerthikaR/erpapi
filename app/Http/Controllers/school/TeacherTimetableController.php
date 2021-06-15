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
use App\Models\TeacherTimetable;

class TeacherTimetableController extends Controller
{
    public function store(Request $TeacherTimetable)
    {
        $data=$TeacherTimetable->data;
        $errors=[];
        foreach($data as $g)
        {
          if ($TeacherTimetable->staff=='') 
          {
           return response()->json(apiResponseHandler([],'The staff field is required',400), 400);
          }
        
        $TeacherTimetable = new TeacherTimetable(array(
          'start_time'   =>$g['start_time'],
          'end_time'=>$g['end_time'],
          'day'=>$g['day'],  
          'subject'=>$g['subject'],
          'class'=>$g['class'],
          'staff'=>$TeacherTimetable->staff,
         ));
          if(!$TeacherTimetable->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'TeacherTimetable saved successfully',
              'data'=>$data,
              'staff'=>$TeacherTimetable->staff
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
    	       $TeacherTimetable = TeacherTimetable::find($request->id);
             if(!empty($TeacherTimetable)){
                    return response()->json([
                    'data'  => $TeacherTimetable      
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
        $TeacherTimetable = TeacherTimetable::all();
        return response()->json(['knec_code' => 'Success', 'data' => $TeacherTimetable]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
        'start_time' => ['required'],
        'end_time' => ['required'],
           
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $TeacherTimetable = TeacherTimetable::find($request->id);
        $TeacherTimetable->start_time= $request->start_time;
       $TeacherTimetable->end_time= $request->end_time;
       $TeacherTimetable->subject= $request->subject;
       $TeacherTimetable->day= $request->day;
       $TeacherTimetable->class= $request->class;
       $TeacherTimetable->staff= $request->staff;

        if($TeacherTimetable->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $TeacherTimetable
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $TeacherTimetable = TeacherTimetable::find($request->id);
        if(!empty($TeacherTimetable))

                {
                  if($TeacherTimetable->delete()){
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
    public function myclass(request $request)
    {
      $class=TeacherTimetable::join('subjects','teacher_timetable.subject','=','subjects.subject_id')
      ->join('add_stream','teacher_timetable.class','=','add_stream.id')
      ->join('std_class','add_stream.class','=','std_class.class_id')
      ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
      ->where('teacher_timetable.staff',$request->teacher)
      ->select('std_class.name as class','class_stream.name as stream','subjects.name as subject','add_stream.id')
      ->groupBy('add_stream.id')
      ->get();
      return response()->json([
        'message'=>'success',
        'data'=>$class
      ]);
    }
    public function myTimetable(request $request)
    {
        $timetable=TeacherTimetable::join('add_stream','teacher_timetable.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->join('subjects','teacher_timetable.subject','=','subjects.subject_id')
        ->where('teacher_timetable.staff',$request->staff)
        ->where('teacher_timetable.day',$request->day)
        ->select('std_class.name as class','class_stream.name as stream','subjects.name as subject','start_time','end_time',
        'teacher_timetable.id')
        ->get();
        return response()->json([
            'message'=>'success',
            'data'=>$timetable
        ]);
    }
}
