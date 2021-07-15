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
use App\Helper;

class SyllabusController extends Controller
{
    public function makeSyllabus(request $request)
    {
        $valiDationArray=[];
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
        $syllabs=Syllabus::leftjoin('add_stream','syllabus.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('subjects','syllabus.subject','=','subjects.subject_id')
        ->select('std_class.name as class','class_stream.name as stream','subjects.name as subject',
        'syllabus.date','syllabus.description','syllabus.file','syllabus.id')
        ->get();
        return response()->json([
            'message'=>'success',
            'data'=>$syllabs
        ]);
    }
    public function show(request $request)
   {
     $syllabs = Syllabus::find($request->id);
             if(!empty($syllabs)){
                    return response()->json([
                    'data'  => $syllabs      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
   }
    public function update(Request $request)

   {
   
         if($request->file)
        {
          $valiDationArray["file"]='required|file';
        }
        $validator =  Validator::make($request->all(),$valiDationArray); 
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
         }
       $Syllabus = Syllabus::find($request->id);
          if($request->file('file')){
              $file = $request->file('file');
              $imgName = time() . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
              Storage::disk('public_uploads')->put('/syllabus-file/' . $imgName, file_get_contents($file));
              $file=config('app.url').'/public/uploads/syllabus-file/' . $imgName;
              $Syllabus->file=$file;
              }
   
        $Syllabus->date= $request->date;
        $Syllabus->class= $request->class;
        $Syllabus->subject= $request->subject;
       
       
        if($Syllabus->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Syllabus
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Syllabus = Syllabus::find($request->id);
        if(!empty($Syllabus))

                {
                  if($Syllabus->delete()){
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




    public function teachermakeSyllabus(request $request)
    {
        $valiDationArray=[];
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

        $id=auth::user()->id;
         //activity
         sendActivities($id, $request->class,'syllabus', 'you have uploaded new syllabus',0);

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
        $syllabs=Syllabus::leftjoin('add_stream','syllabus.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('subjects','syllabus.subject','=','subjects.subject_id')
        ->leftjoin('users','syllabus.created_by','=','users.id')
        ->where('syllabus.class',$request->class)
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
        $syllabs=Syllabus::leftjoin('add_stream','syllabus.class','=','add_stream.id')
        ->leftjoin('admission','syllabus.class','=','admission.class')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('subjects','syllabus.subject','=','subjects.subject_id')
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
