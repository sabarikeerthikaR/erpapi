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
use Illuminate\Support\Facades\Auth;
use App\Models\Syllabus;
use App\Models\Std_class;
use App\Models\Subject;

class SyllabusController extends Controller
{
    public function makeSyllabus(request $request)
    {
        if($request->file)
        {
          $valiDationArray["file"]='required|file';
        }
        $validator =  Validator::make($request->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $file='';
         if($request->file('file')){
         $file = $request->file('file');
         $imgName = time() . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
         Storage::disk('public_uploads')->put('/syllabus-file/' . $imgName, file_get_contents($file));
         $file=config('app.url').'/public/uploads/syllabus-file/' . $imgName;
         }
        $syllabus=Syllabus::create([
          'date'=>$request->date,
          'class'=>$request->class,
          'subject'=>$request->subject,
          'description'=>$request->description,
          'file'=>$file,
          'created_by'=>auth::user()->id

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
        if($request->file)
        {
          $valiDationArray["file"]='required|file';
        }
        $validator =  Validator::make($request->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $file='';
         if($request->file('file')){
         $file = $request->file('file');
         $imgName = time() . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
         Storage::disk('public_uploads')->put('/syllabus-file/' . $imgName, file_get_contents($file));
         $file=config('app.url').'/public/uploads/syllabus-file/' . $imgName;
         }
       
        $syllabus=Syllabus::create([
          'date'=>$request->date,
          'class'=>$request->class,
          'subject'=>$request->subject,
          'description'=>$request->description,
          'file'=>$file

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
        ->join('users','syllabus.created_by','=','users.id')
        ->where('users.staff_id',$request->staff_id)
        ->select('std_class.name as class','class_stream.name as stream','subjects.name as subject',
        'syllabus.date','syllabus.description','file','add_stream.id as class_id','syllabus.id as syllabus_id','syllabus.created_at')
        ->get();
        return response()->json([
            'message'=>'success',
            'data'=>$syllabs
        ]);
    }

    public function studentselectsyllabus(request $request)
    {
        $syllabs=Syllabus::join('add_stream','syllabus.class','=','add_stream.id')
        ->join('admission','syllabus.class','=','admission.class')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->join('subjects','syllabus.subject','=','subjects.subject_id')
        ->where('admission.admission_id',$request->admission_id)
        ->select('std_class.name as class','class_stream.name as stream','subjects.name as subject',
        'syllabus.date','syllabus.description','file','syllabus.created_at')
        ->get();
        return response()->json([
            'message'=>'success',
            'data'=>$syllabs
        ]);
    }
   
}
