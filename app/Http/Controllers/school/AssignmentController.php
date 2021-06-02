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
use App\Models\Assignment;
use App\Models\AddStream;
use App\Models\Class_stream;
use App\Models\Std_class;
class AssignmentController extends Controller
{
     public function store(Request $Assignment)
    {
      $validator =  Validator::make($Assignment->all(), [
            'title' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        // $upload_document = time() . '-' . $Assignment->upload_document->extension();
        // $Assignment->upload_document->move(public_path('images'),$upload_document);
      
        $Assignment=Assignment::create([

        'title'  =>$Assignment->title ,
        'start_date'  =>$Assignment->start_date ,
        'end_date'  =>$Assignment->end_date ,
        'class'  =>$Assignment->class ,
        'upload_document'  =>$Assignment->upload_document ,
         'assignment'  =>$Assignment->assignment ,
          'comment'  =>$Assignment->comment ,
          'created_on'  =>date("Y-m-d") ,
          'created_by'  =>'admin' , 
         ]);
        if($Assignment->save()){
                  return response()->json([
                 'message'  => 'Assignment saved successfully',
                 'data'  => $Assignment 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Assignment = Assignment::find($request->assignment_id);
             if(!empty($Assignment)){
                    return response()->json([
                    'data'  => $Assignment      
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
        $Assignment = Assignment::join('add_stream','assignment.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->select('std_class.name as class','class_stream.name as stream','title',
    'start_date','end_date','upload_document','assignment','comment','assignment_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Assignment]);
    }
public function assignmentview(Request $request)
{
    $Assignment = Assignment::join('add_stream','assignment.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->where('assignment_id',$request->assignment_id)
        ->select('std_class.name as class','class_stream.name as stream','title',
    'start_date','end_date','upload_document','assignment','comment','assignment_id','created_on',
    'created_by')->first();
    if(!empty($Assignment))
    {
        return response()->json(['status' => 'Success', 'data' => $Assignment]);
    }else{
        return response()->json(['status' => 'failed']);
    }
}

public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'title' => ['required'],
        'start_date' => ['required'],
        'end_date' => ['required'],
       
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        // $upload_document = time() . '-' . $request->upload_document->extension();
        // $request->upload_document->move(public_path('images'),$upload_document);
      
    $Assignment = Assignment::find($request->assignment_id);
        $Assignment->title = $request->title ;
        $Assignment->start_date = $request->start_date ;
        $Assignment->end_date = $request->end_date ;
        $Assignment->class = $request->class;
        $Assignment->upload_document =$request->upload_document ;
        $Assignment->assignment = $request->assignment ;
        $Assignment->comment = $request->comment ;
       
        if($Assignment->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Assignment
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Assignment = Assignment::find($request->assignment_id);
        if(!empty($Assignment))

                {
                  if($Assignment->delete()){
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

    public function assignmentstoreTeacher(Request $Assignment)
    {
      $validator =  Validator::make($Assignment->all(), [
            'title' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        // $upload_document = time() . '-' . $Assignment->upload_document->extension();
        // $Assignment->upload_document->move(public_path('images'),$upload_document);
      
        $Assignment=Assignment::create([

        'title'  =>$Assignment->title ,
        'start_date'  =>$Assignment->start_date ,
        'end_date'  =>$Assignment->end_date ,
        'class'  =>$Assignment->class ,
        'upload_document'  =>$Assignment->upload_document ,
         'assignment'  =>$Assignment->assignment ,
          'comment'  =>$Assignment->comment ,
          'created_on'  =>date("Y-m-d") ,
          'created_by'  =>'admin' , 
         ]);
        if($Assignment->save()){
                  return response()->json([
                 'message'  => 'Assignment saved successfully',
                 'data'  => $Assignment 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
    public function AssignmetShowTeacher()
    {
        $Assignment = Assignment::join('add_stream','assignment.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->select('std_class.name as class','class_stream.name as stream','title',
    'start_date','end_date','upload_document','assignment','comment','assignment_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Assignment]);
    }

    public function assignmentviewteacher(Request $request)
{
    $Assignment = Assignment::join('add_stream','assignment.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->where('assignment_id',$request->assignment_id)
        ->select('std_class.name as class','class_stream.name as stream','title',
    'start_date','end_date','upload_document','assignment','comment','assignment_id','created_on',
    'created_by')->first();
    if(!empty($Assignment))
    {
        return response()->json(['status' => 'Success', 'data' => $Assignment]);
    }else{
        return response()->json(['status' => 'failed']);
    }
}
public function AssignmetShowStudent()
{
    $Assignment = Assignment::join('add_stream','assignment.class','=','add_stream.id')
    ->join('std_class','add_stream.class','=','std_class.class_id')
    ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
    ->select('std_class.name as class','class_stream.name as stream','title',
'start_date','end_date','upload_document','assignment','comment','assignment_id')->get();
    return response()->json(['status' => 'Success', 'data' => $Assignment]);
}
}
