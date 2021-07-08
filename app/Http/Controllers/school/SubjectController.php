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
use App\Models\Settings;

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
        $SubjectGet=Subject::where('name',$request->name)
        ->where('name',$request->name)
        ->where('code',$request->code)
        ->where('short_name',$request->short_name)
        ->where('priority',$request->priority)
        ->where('type',$request->type)
        ->select('subject_id')->get();
       foreach($SubjectGet as $s)
        {
        $settings=new Settings(array(
            'group_name'=>'subject',
            'key_name'=>$request->name,
            'key_value'=>$s['subject_id'],
            ));
             if(!$settings->save())
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
     $Subject = Subject::find($request->subject_id);
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
        $Subject = Subject::leftjoin('setings as unit','subjects.sub_units','=','unit.s_d')
        ->leftjoin('terms','subjects.term','=','terms.term_id')
        ->leftjoin('add_stream','subjects.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','stream_id')
        ->select('subject_id','subjects.name','code','short_name','priority','unit.key_name as sub_units','class_stream.name as stream','std_class.name as class','terms.name as term')->get();
        return response()->json(['status' => 'Success', 'data' => $Subject]);
    }
    public function subjectProfile(request $request)
    {
      
        $Subject = Subject::leftjoin('setings as unit','subjects.sub_units','=','unit.s_d')
        ->leftjoin('terms','subjects.term','=','terms.term_id')
        ->leftjoin('add_stream','subjects.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','stream_id')
        ->where('subjects.code',$request->code)
        ->select('subject_id','subjects.name','code','short_name','priority','unit.key_name as sub_units','class_stream.name as stream','std_class.name as class','terms.name as term')->first();
        $class = Subject::leftjoin('add_stream','subjects.class','=','add_stream.id')
        ->leftjoin('setings as unit','subjects.sub_units','=','unit.s_d')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','stream_id')
        ->where('subjects.code',$request->code)
        ->select('unit.key_name as sub_units','class_stream.name as stream','std_class.name as class')->get();
        return response()->json(['status' => 'Success', 'data' => $Subject,
                                                         'class'=>$class]);
    }
    public function subunitShow(request $request)
    {
        $subunit=Subject::where('subject_id',$request->subject_id)
        ->select('unit_title','mark')
        ->first();
          if(!empty($subunit)){
                    return response()->json([
                    'data'  => $subunit      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'Not yet saved'  
                  ]);
                 }

    }

    public function subUnits(request $request)
    {
        $subUnits=Subject::find($request->subject_id);
        $subUnits->unit_title = $request->unit_title;
        $subUnits->mark = $request->mark;
         if($subUnits->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $subUnits
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
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
    $Subject = Subject::find($request->subject_id);
        $Subject->name = $request->name ;
        $Subject->term = $request->term ;
        $Subject->name = $request->name ;
        $Subject->code = $request->code ;
        $Subject->short_name = $request->short_name ;
        $Subject->priority = $request->priority ;
         $Subject->type = $request->type ;
          $Subject->sub_units = $request->sub_units ;
           $Subject->class = $request->class ;
       $settings=Settings::where('group_name','=','subject')->where('key_value',$request->subject_id)->first();
        $settings->key_name= $request->name;
        $settings->save();  
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
        $Subject = Subject::find($request->subject_id);
         $settings=Settings::where('group_name','=','subject')->where('key_value',$request->subject_id)->first();
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->key_name=NULL;
        $settings->save();  
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
      $student=Admission::where('admission.class',$request->class_id)
      ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
      'admission_id','admission.image')
     ->groupBy('admission_id')
      ->get();
      return response()->json([
        'message'=>'success',
        'data'=>$student
      ]);
    }

}
