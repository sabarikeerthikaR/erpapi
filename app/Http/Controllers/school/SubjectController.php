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
use App\Models\SubUnit;
use App\Models\SubjectClass;
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
        $validator =  Validator::make($request->all(), [
            'name' => ['required'],
             'short_name' => ['required'],
              'priority' => ['required'],
               'type' => ['required'],
                'sub_units' => ['required']
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $subject=Subject::create([

       'name'=>$request->name,
          'code'=>$request->code,
          'short_name'=>$request->short_name,
          'priority'=>$request->priority,
          'type'=>$request->type,
          'sub_units'=>$request->sub_units,
 
         ]); $subject->save();
         $settings=Settings::create([
            'group_name'=>'subject',
            'key_name'=>$request->name,
            'key_value'=>$subject->subject_id,
            ]);
            $settings->save();
          
      
        foreach($data as $g)
        {

        $class = new SubjectClass(array(
          'subject'=>$subject->subject_id,
          'term'   =>$g['term'],
          'class'=>$g['class'],
         ));

        if(!$class->save())
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
        ->select('subject_id','subjects.name','code','short_name','priority','unit.key_name as sub_units',)->get();
        return response()->json(['status' => 'Success', 'data' => $Subject]);
    }
    public function subjectProfile(request $request)
    {
      
        $Subject = Subject::where('subject_id',$request->subject_id)
        ->select('subject_id','subjects.name','code','short_name','priority')->first();

        $subunit = SubUnit::where('subject',$request->subject_id)
        ->select('name','mark')->get();

        $class = SubjectClass::leftjoin('terms','subject_class.term','=','terms.term_id')
        ->leftjoin('add_stream','subject_class.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','stream_id')
        ->where('subject',$request->subject_id)
        ->select('class_stream.name as stream','std_class.name as class','terms.name as term')->get();

        return response()->json(['status' => 'Success', 'data' => $Subject,
                                                         'sub_count'=> $subunit->count(),  
                                                         'class_count'=> $class->count(),
                                                         'class'=>$class,
                                                         'subunit'=>$subunit]);
    }
    public function subunitShow(request $request)
    {
        $subunit=SubUnit::where('subject',$request->subject_id)
        ->get();
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
    public function SubUnitdestroy(Request $request)
    {
        $Subject = SubUnit::find($request->id);
        $Subjectget = SubUnit::where('id',$request->id)->select('subject')->first();
        $Subjectcount = SubUnit::where('subject',$Subjectget->subject)->count();
        if($Subjectcount==0)
        {
        $Subjectup = Subject::find($Subjectget->subject);
        $Subjectup->sub_units =340 ;
        $Subjectup->save();
        }
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

    public function subUnits(request $request)
    {
        $subUnits=new SubUnit([
        'subject' => $request->subject,
       'name' => $request->name,
        'mark' => $request->mark,
        ]);
        $Subjectup = Subject::find($request->subject);
        $Subjectup->sub_units =339 ;
        $Subjectup->save();
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
           
          
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Subject = Subject::find($request->subject_id);
          $Subject->name = $request->name ;  
          $Subject->code = $request->code ;
          $Subject->short_name = $request->short_name ;
          $Subject->priority = $request->priority ;
          $Subject->type = $request->type ;
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
        $subunit = SubUnit::where('subject',$request->subject_id);
        $subunit->delete();
        $class = SubjectClass::where('subject',$request->subject_id);
        $class->delete();
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
      ->select(db::raw("CONCAT(first_name,' ',coalesce(middle_name,''),' ',last_name)as student"),
      'admission_id','admission.image')
     ->groupBy('admission_id')
      ->get();
      return response()->json([
        'message'=>'success',
        'data'=>$student
      ]);
    }

}
