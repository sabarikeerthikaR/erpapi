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
use Illuminate\Support\Facades\Auth;
use App\Helper;
use App\Models\Std_class;
class AssignmentController extends Controller
{
     public function store(Request $Assignment)
    {
      $valiDationArray =[
            'title' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
           
          ]; 
          if($Assignment->upload_document)
        {
          $valiDationArray["upload_document"]='required|file';
        }
        $validator =  Validator::make($Assignment->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $upload_document='';
        if($Assignment->file('upload_document')){
        $upload_document = $Assignment->file('upload_document');
        $imgName = time() . '.' . pathinfo($upload_document->getClientOriginalName(), PATHINFO_EXTENSION);
        Storage::disk('public_uploads')->put('/assignment-upload-document/' . $imgName, file_get_contents($upload_document));
        $upload_document=config('app.url').'/public/uploads/assignment-upload-document/' . $imgName;
        }
        $Assignment=Assignment::create([

        'title'  =>$Assignment->title ,
        'start_date'  =>$Assignment->start_date ,
        'end_date'  =>$Assignment->end_date ,
        'class'  =>$Assignment->class ,
        'upload_document'  =>$upload_document ,
         'assignment'  =>$Assignment->assignment ,
          'comment'  =>$Assignment->comment ,
          'created_on'  =>date("Y-m-d") ,
          'created_by'  =>auth::user()->id, 
         ]);

         sendActivities($Assignment->created_by, $Assignment->class,'assignment', 'you have uploaded new assignment',0);
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
        $Assignment = Assignment::leftjoin('add_stream','assignment.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->select('std_class.name as class','class_stream.name as stream','title',
    'start_date','end_date','upload_document','assignment','comment','assignment_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Assignment]);
    }
public function assignmentview(Request $request)
{
    $Assignment = Assignment::leftjoin('add_stream','assignment.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('users','assignment.created_by','=','users.id')
        ->where('assignment_id',$request->assignment_id)
        ->select('std_class.name as class','class_stream.name as stream','title',
    'start_date','end_date','upload_document','assignment','comment','assignment_id','created_on',
    DB::raw("CONCAT (users.first_name,' ',COALESCE(users.middle_name,''),' ',users.last_name)As created_by"))->first();
    if(!empty($Assignment))
    {
        return response()->json(['status' => 'Success', 'data' => $Assignment]);
    }else{
        return response()->json(['status' => 'failed']);
    }
}

public function update(Request $request)

   {
    $valiDationArray =  [
        'title' => ['required'],
        'start_date' => ['required'],
        'end_date' => ['required'],
       
        ]; 
        if($request->upload_document)
        {
          $valiDationArray["upload_document"]='required|file';
        }
        $validator =  Validator::make($request->all(),$valiDationArray); 
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
         }
         $Assignment = Assignment::find($request->assignment_id);
          if($request->file('upload_document')){
              $upload_document = $request->file('upload_document');
              $imgName = time() . '.' . pathinfo($upload_document->getClientOriginalName(), PATHINFO_EXTENSION);
              Storage::disk('public_uploads')->put('/assignment-upload-document/' . $imgName, file_get_contents($upload_document));
              $upload_document=config('app.url').'/public/uploads/assignment-upload-document/' . $imgName;
              $Assignment->upload_document=$upload_document;
              }

        $Assignment->title = $request->title ;
        $Assignment->start_date = $request->start_date ;
        $Assignment->end_date = $request->end_date ;
        $Assignment->class = $request->class;
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
      $valiDationArray =  [
            'title' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
           
          ]; 
          if($Assignment->upload_document)
          {
            $valiDationArray["upload_document"]='required|file';
          }
          $validator =  Validator::make($Assignment->all(),$valiDationArray); 
          if ($validator->fails()) {
              return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
          }
          $upload_document='';
          if($Assignment->file('upload_document')){
          $upload_document = $Assignment->file('upload_document');
          $imgName = time() . '.' . pathinfo($upload_document->getClientOriginalName(), PATHINFO_EXTENSION);
          Storage::disk('public_uploads')->put('/assignment-upload-document/' . $imgName, file_get_contents($upload_document));
          $upload_document=config('app.url').'/public/uploads/assignment-upload-document/' . $imgName;
          }
        $Assignment=Assignment::create([

        'title'  =>$Assignment->title ,
        'start_date'  =>$Assignment->start_date ,
        'end_date'  =>$Assignment->end_date ,
        'class'  =>$Assignment->class ,
        'upload_document'  =>$upload_document ,
         'assignment'  =>$Assignment->assignment ,
          'comment'  =>$Assignment->comment ,
          'created_on'  =>date("Y-m-d") ,
          'created_by'  =>Auth::user()->id, 
         ]);
         $id=auth::user()->id;
         //activity
         sendActivities($id, $Assignment->class,'Assignment', 'you have uploaded new assignment',0);

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
    public function AssignmetShowTeacher(Request $request)
    {
        $Assignment = Assignment::leftjoin('add_stream','assignment.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('users','assignment.created_by','=','users.id')
        ->where('users.staff_id',$request->staff_id)
        ->select('std_class.name as class','class_stream.name as stream','title',
    'start_date','end_date','upload_document','assignment','comment','assignment_id','add_stream.id as class_id',
    'assignment.created_at'
    )->orderBy('assignment_id', 'desc')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Assignment]);
    }

    public function assignmentviewteacher(Request $request)
{
    $Assignment = Assignment::leftjoin('add_stream','assignment.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('users','assignment.created_by','=','users.id')
        ->where('assignment_id',$request->assignment_id)
        ->select(DB::raw("CONCAT (users.first_name,' ',COALESCE(users.middle_name,''),' ',users.last_name)As created_by"),
            'std_class.name as class','class_stream.name as stream','title',
    'start_date','end_date','upload_document','assignment','comment','assignment_id','created_on'
    )->first();
    if(!empty($Assignment))
    {
        return response()->json(['status' => 'Success', 'data' => $Assignment]);
    }else{
        return response()->json(['status' => 'failed']);
    }
}
public function AssignmetShowStudent(Request $request)
{
    $Assignment = Assignment::leftjoin('add_stream','assignment.class','=','add_stream.id')
    ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
    ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
    ->leftjoin('admission','add_stream.id','=','admission.class')
    ->leftjoin('users','assignment.created_by','=','users.id')
    ->where('admission.admission_id',$request->admission_id)
    ->select(DB::raw("CONCAT (users.first_name,' ',COALESCE(users.middle_name,''),' ',users.last_name)As created_by"),
        'std_class.name as class','class_stream.name as stream','title',
'start_date','end_date','upload_document','assignment','comment','assignment_id','created_on','assignment.created_at')
    ->orderBy('assignment_id', 'desc')
    ->get();
    return response()->json(['status' => 'Success', 'data' => $Assignment]);
}
}
