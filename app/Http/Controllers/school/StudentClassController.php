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
use App\Models\Admission;
use App\Models\Staff;
use App\Models\Std_class;
use App\Models\Class_stream;
use App\Models\AddStream;


class StudentClassController extends Controller
{
  public function studentMovement(request $request)
  {
    $data=$request->data;
    $errors=[];
    foreach($data as $g)
    {
 
    $class = Admission::where('admission_id',$g['admission_id'])->first();
      
      $class->class=$g['class'];
 
      if(!$class->save())
      {
        $errors[]=$g;
      }
    } 
         
          if(count($errors)==0)
          {
          return response()->json([
          'message'  => 'class saved successfully',
          'data'=>$data,
         
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
  public function listStudent(request $request)
  {
      $student=AddStream::join('std_class','add_stream.class','=','std_class.class_id')
      ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
      ->join('admission','add_stream.id','=','admission.class')
      ->where('add_stream.id',$request->class_id)
      ->select('std_class.name as class','class_stream.name as stream',
      db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
      'add_stream.id as class_id','admission_id')
      ->get();
      return response()->json([
          'message'=>'success',
          'data'=>$student
      ]);
  }
 

}
