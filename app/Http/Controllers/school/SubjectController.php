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
use App\Models\Subject;
use App\Models\AddSubToTeacher;
use App\Models\Admission;
use App\Models\Staff;
use App\Models\Std_class;
use App\Models\Class_stream;
use App\Models\AddStream;
use App\Models\StudentClass;

class SubjectController extends Controller
{
   public function store(Request $request)
    {
          
        $data=$request->data;
        $errors=[];
        foreach($data as $g)
        {
          if ($request->name=='') 
          {
           return response()->json(apiResponseHandler([],'The name field is required',400), 400);
          }
          if ($request->short_name=='') 
          {
           return response()->json(apiResponseHandler([],'The short_name field is required',400), 400);
          }
          if ($request->priority=='') 
          {
           return response()->json(apiResponseHandler([],'The priority field is required',400), 400);
          }
          if ($request->type=='') 
          {
           return response()->json(apiResponseHandler([],'The type field is required',400), 400);
          }
          if ($request->sub_units=='') 
          {
           return response()->json(apiResponseHandler([],'The sub_units field is required',400), 400);
          }
        
        $Subject = new Subject(array(
          'name'=>$request->name,
          'code'=>$request->code,
          'short_name'=>$request->short_name,
          'priority'=>$request->priority,
          'type'=>$request->type,
          'sub_units'=>$request->sub_units,
          'term'   =>$g['term'],
          'class'=>$g['class'],
         ));
          if(!$Subject->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'Subject saved successfully',
              'data'=>$data,
              'name'=>$request->name,
              'code'=>$request->code,
              'short_name'=>$request->short_name,
              'priority'=>$request->priority,
              'type'=>$request->type,
              'sub_units'=>$request->sub_units,
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
     $Subject = Subject::find($request->Subject_id);
             if(!empty($Subject)){
                    return response()->json([
                    'data'  => $Subject      
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
        $Subject = Subject::all();
        return response()->json(['status' => 'Success', 'data' => $Subject]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
         
            'name' => ['required'],
            'short_name' => ['required'],
            'priority' => ['required'],
            'type' => ['required'],
            'sub_units' => ['required'],
          
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Subject = Subject::find($request->Subject_id);
        $Subject->title = $request->title ;
        $Subject->term = $request->term ;
        $Subject->name = $request->name ;
        $Subject->code = $request->code ;
        $Subject->short_name = $request->short_name ;
        $Subject->priority = $request->priority ;
         $Subject->type = $request->type ;
          $Subject->sub_units = $request->sub_units ;
           $Subject->class = $request->class ;
       
        if($Subject->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Subject
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Subject = Subject::find($request->Subject_id);
        if(!empty($Subject))

                {
                  if($Subject->delete()){
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
    public function listteacher()
    {
      $teacher=AddStream::
      rightjoin('std_class','add_stream.class','=','std_class.class_id')
      ->rightjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
      ->select('std_class.name as class','class_stream.name as stream','add_stream.id')
      ->groupBy('add_stream.id')->get();
      return response()->json([
      'message'=>'success',
      'data'=>$teacher
      ]);
    }
    public function addSubToTeacher(request $request)
    {
      $addsub=AddSubToTeacher::create([
      'subject'=>$request->subject,
      'teacher'=>$request->teacher,
      'class'  =>$request->id
          ]);
      if($addsub->save()){
        return response()->json([
       'message'  => 'sub saved successfully',
       'data'  => $addsub 
        ]);
    }else {
        return response()->json([
       'message'  => 'failed'
       ]);
}

    }
   
    public function listStudent(request $request)
    {
      $student=StudentClass::join('admission','student_class.student','=','admission.admission_id')
      ->where('student_class.class',$request->class_id)->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),'admission_id')
     ->groupBy('admission_id')
      ->get();
      return response()->json([
        'message'=>'success',
        'data'=>$student
      ]);
    }

}
