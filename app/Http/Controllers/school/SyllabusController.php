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
use App\Models\Syllabus;
use App\Models\Std_class;
use App\Models\Subject;

class SyllabusController extends Controller
{
    public function makeSyllabus(request $request)
    {
        // $file = time() . '-' . $request->file->extension();
        // $request->file->move(public_path('images'),$file);
      
        $syllabus=Syllabus::create([
          'date'=>$request->date,
          'class'=>$request->class,
          'subject'=>$request->subject,
          'description'=>$request->description,
          'file'=>$request->file

        ]);
        if($syllabus->save())
        {
            return response()->json([
             'message'=>'saved successfully',
             'data'=>$syllabus
            ]);
        }else
        {
            return response()->json([
                'message'=>'failed'

            ]);
        }
    }
    public function select(request $request)
    {
        $syllabs=Syllabus::join('add_stream','syllabus.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->join('subjects','syllabus.subject','=','subjects.subject_id')
        ->select('std_class.name as class','class_stream.name as stream','subjects.name as subject',
        'date','syllabus.description','file')
        ->get();
        return response()->json([
            'message'=>'success',
            'data'=>$syllabs
        ]);
    }
    public function teachermakeSyllabus(request $request)
    {
        // $file = time() . '-' . $request->file->extension();
        // $request->file->move(public_path('images'),$file);
      
        $syllabus=Syllabus::create([
          'date'=>$request->date,
          'class'=>$request->class,
          'subject'=>$request->subject,
          'description'=>$request->description,
          'file'=>$request->file

        ]);
        if($syllabus->save())
        {
            return response()->json([
             'message'=>'saved successfully',
             'data'=>$syllabus
            ]);
        }else
        {
            return response()->json([
                'message'=>'failed'

            ]);
        }
    }
    public function teacherselectsyllabus(request $request)
    {
        $syllabs=Syllabus::join('add_stream','syllabus.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->join('subjects','syllabus.subject','=','subjects.subject_id')
        ->select('std_class.name as class','class_stream.name as stream','subjects.name as subject',
        'date','syllabus.description','file')
        ->get();
        return response()->json([
            'message'=>'success',
            'data'=>$syllabs
        ]);
    }

    public function studentselectsyllabus(request $request)
    {
        $syllabs=Syllabus::join('add_stream','syllabus.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->join('subjects','syllabus.subject','=','subjects.subject_id')
        ->select('std_class.name as class','class_stream.name as stream','subjects.name as subject',
        'date','syllabus.description','file')
        ->get();
        return response()->json([
            'message'=>'success',
            'data'=>$syllabs
        ]);
    }
   
}
