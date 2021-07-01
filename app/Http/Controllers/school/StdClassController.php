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
use App\Models\Std_class;
use App\Models\Class_stream;
use App\Models\Admission;
use App\Models\AddStream;
use App\Models\Settings;
use App\Models\StudentClass;
use App\Post;


class StdClassController extends Controller
{
     public function store(Request $Std_class)
    {
      
        $Std_class=Std_class::create([
        
        'name'  =>$Std_class->name,
        'description'  =>$Std_class->description,

         ]);
         $Std_class->save();
         $id=$Std_class->class_id;
         $settings=Settings::create([
         'group_name'=>'class',
         'key_name'=>$Std_class->name,
         'key_value'=>$id,
         ]);
         $settings->save();
         $stream=AddStream::create([
          'class'=>$id,
          ]);
          $stream->save();
        if($settings->save()){
                  return response()->json([
                 'message'  => 'Std_class saved successfully',
                 'data'  => $Std_class 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Std_class = Std_class::find($request->class_id);
             if(!empty($Std_class)){
                    return response()->json([
                    'data'  => $Std_class      
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
        $Std_class = Std_class::leftjoin('add_stream','std_class.class_id','=','add_stream.class')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('admission','add_stream.id','=','admission.class')
        ->select(db::raw("COUNT(admission.class) as total_student"),'class_id','std_class.name as class','std_class.description','std_class.status','class_stream.name as stream','class_id','stream_id')
        ->groupBy('std_class.class_id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Std_class]);
    }
    public function update(Request $request)

    {
    
     $Std_class = Std_class::find($request->class_id);
         $Std_class->name= $request->name;
         $Std_class->description= $request->description;
             $settings=Settings::where('group_name','=','class')->where('key_value',$request->class_id)->first();
             $settings->key_name= $request->name;
             $settings->save();
         if($Std_class->save()){
             return response()->json([
                  'message'  => 'updated successfully',
                  'data'  => $Std_class
             ]);
         }else {
             return response()->json([
                  'message'  => 'failed'
                  ]);
         }
     }

public function add_stream(Request $request)

   {
    $data=$request->data;
    $errors=[];
    $streams=[];
    $class=Std_class::where('class_id',$request->class)->select('name')->first();
   
    foreach($data as $g)
    {
       
      $Std_classstream = new AddStream(array(
      'class'=>$request->class,
      'stream'=>$g['stream'], 
      'date'=>date('Y-m-d'),   
      ));
       if(!$Std_classstream->save())
      {
        $errors[]=$g;
      }
  //     else{
  //       $streams[]=$Std_classstream->id;
  //      $streanName[]=
  //     $settings= new Settings(array(
  //       'group_name'=>'class_stream',
  //       'key_name'=>$name=$class->name .' '. $Std_classstream->name,
  //       'key_value'=>$g[$Std_classstream->id]
  //       ));
  //     $settings->save();
  //  }
   }
  
   if(count($errors)==0)
          {
          return response()->json([
          'message'  => 'add stream updated successfully',
         'class'=>$request->class,
         'data'=>$data
          
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
public function destroy(Request $request)
    {
        $Std_class = Std_class::find($request->class_id);
        $settings=Settings::where('group_name','=','class')->where('key_value',$request->class_id)->first();
        $settings->key_name=NULL;
        $settings->key_name=NULL;
        $settings->key_name=NULL;
        $settings->save();
        if(!empty($Std_class))

                {
                  if($Std_class->delete()){
                 
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
    public function classsetting()
    {
      $class=Std_class::leftjoin('add_stream','std_class.class_id','=','add_stream.class')
      ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
      ->leftjoin('admission','std_class.class_id','=','admission.class')
     
      ->select(db::raw("CONCAT(std_class.name,' ',class_stream.name) as class"),db::raw("COUNT('admission.class') as total_student"),
      'std_class.status as status','std_class.description as description',
      'class_id','stream_id')
      ->groupBy('class_id')
      ->get();
     // $comments = AddStream::find(1)->comments()->where('stream',$class->stream)->first(); 

  //    $attrs = [];
      
   // foreach ($class as $key => $value) {
   //  $many=DB::table("add_stream")->whereIn("class",[$value->class_id])->select('stream')->get();
   //   $streams=DB::table('class_stream')->whereIn("stream_id",[$many->stream])->select('name')
   //   ->get(); 
   //  $attrs[$value->stream][] = $streams;
   //   }
      return response([
        'message'=>'success',
        'class'=>$class,
       // 'stream'=>$attrs,
      ]);
    }
    public function enabelClass(request $request)
    {
      $enable=Std_class::find($request->class_id);
      $enable->status='active';
     if($enable->save())
     {
       return response()->json([
       'message'=>'class enabled'
       ]);
     }
     else{
      return response()->json([
        'message'=>'unable to enable'
        ]);
     }
    }
    public function disablClass(request $request)
    {
      $disable=Std_class::find($request->class_id);
      $disable->status='disabled';
     if($disable->save())
     {
       return response()->json([
       'message'=>'class disabled'
       ]);
     }
     else{
      return response()->json([
        'message'=>'unable to disable'
        ]);
     }
    }
    public function allclass()
    {
      $class=Std_class::
      join('add_stream','std_class.class_id','=','add_stream.class')
      ->leftjoin('staff','add_stream.teacher','staff.employee_id')
      ->leftjoin('setings as status','add_stream.status','=','status.s_d')
      ->leftjoin('admission','add_stream.id','=','admission.class')
      ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
      ->select('std_class.name as class',
      db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),' ',staff.last_name)as teacher"),
      db::raw('COUNT(admission.class)as total_student'),
      'status.key_name as status','class_id','class_stream.name as stream','add_stream.id')
      ->groupBy('add_stream.id')->get();
      return response()->json([
      'message'=>'success',
      'data'=>$class
      ]);
    }
public function selectAllClassStream(request $request)
{
    $classes=AddStream::find($request->id);
  
             if(!empty($classes)){
                    return response()->json([
                    'data'  => $classes      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
}
public function classStreamView(request $request)
{
  $class=AddStream::leftjoin('staff','add_stream.teacher','=','staff.employee_id')
  ->leftjoin('admission','add_stream.id','=','admission.class')
  ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
  ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
  ->where('add_stream.id',$request->id)
  ->select(db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),staff.last_name)as teacher"),
  'std_class.name as class','class_stream.name as stream',
  'add_stream.date as class_date',db::raw('COUNT(admission.class)as total_student'))
  ->groupBy('add_stream.id')
  ->first();
  $student=Admission::leftjoin('add_stream','admission.class','=','add_stream.id')
  ->leftjoin('setings as gender','admission.gender','=','gender.s_d')
  ->where('add_stream.id',$request->id)
  ->select('admission_id','admission.image','gender.key_name as gender','admission.date as admission_date',
  db::raw("CONCAT(admission.first_name,' ',COALESCE(admission.middle_name,''),admission.last_name)as student"),
  'admission_no')
  ->get();
  return response()->json([
    'message'=>'success',
    'class'=>$class,
    'student'=>$student
  ]);

}
    public function addteacher(request $request)
    {
      $p=$request->all();
        $id=$p['class_id'];
        DB::enableQueryLog();
      $teacher=AddStream::where('id',$id)->first();
      $teacher->teacher= $request->teacher;
      $teacher->exam_recording= $request->exam_recording;
      if($teacher->save())
     {
       return response()->json([
       'message'=>'Added',
       'data'=>$teacher
       ]);
     }
     else{
      return response()->json([
        'message'=>'unable to add'
        ]);
     }

    }
}
