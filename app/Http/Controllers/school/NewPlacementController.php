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
use App\Models\NewPlacement;
use App\Models\Admission;
use App\Models\ClassRooms;
use App\Models\Position;

class NewPlacementController extends Controller
{
    public function store(Request $NewPlacement)
    {
      $validator =  Validator::make($NewPlacement->all(), [
            'student' => ['required'],
            'date' => ['required'],
            'position' => ['required'],
            'rep_of' => ['required'],
            'date_upto' => ['required'],
            'description' => ['required'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $NewPlacement=NewPlacement::create([

        'student'  =>$NewPlacement->student ,
        'date'  =>$NewPlacement->date ,
        'position'  =>$NewPlacement->position ,
        'rep_of'  =>$NewPlacement->rep_of ,
        'date_upto'  =>$NewPlacement->date_upto ,
        'description'  =>$NewPlacement->description ,
        
         ]); 
        if($NewPlacement->save()){
                  return response()->json([
                 'message'  => 'NewPlacement saved successfully',
                 'data'  => $NewPlacement 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
    $p=$request->all();
        $id=$p['student'];
        DB::enableQueryLog();
     $NewPlacement = NewPlacement::where("student",$id)->first();
             if(!empty($NewPlacement)){
                    return response()->json([
                    'data'  => $NewPlacement      
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
        $NewPlacement = NewPlacement::
        join('admission','new_placement.student', '=', 'admission.admission_id')
        ->join('add_stream','new_placement.rep_of','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->join('position','new_placement.position','=','position.id')
        ->select('std_class.name as class','class_stream.name as stream',
        DB::raw("CONCAT(first_name,' ',COALESCE(middle_name_s,''),' ',last_name)as full_name"),
        'new_placement.date','date_upto','position.name as position','admission_id')->get();
        if(!empty($NewPlacement))
        {
        return response()->json(['status' => 'Success', 'data' => $NewPlacement]);
      }else
      {
         return response()->json(['status' => 'no data found', ]);
      }
    }


public function update(Request $request)

   {
    $p=$request->all();
        $id=$p['student'];
        DB::enableQueryLog();
     $NewPlacement = NewPlacement::where("student",$id)->first();
        $NewPlacement->student = $request->student ;
        $NewPlacement->date = $request->date ;
        $NewPlacement->position = $request->position ;
        $NewPlacement->rep_of = $request->rep_of;
        $NewPlacement->date_upto = $request->date_upto ;
        $NewPlacement->description = $request->description;
      
        if($NewPlacement->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $NewPlacement
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $p=$request->all();
        $id=$p['student'];
        DB::enableQueryLog();
     $NewPlacement = NewPlacement::where("student",$id)->first();
        if(!empty($NewPlacement))

                {
                  if($NewPlacement->delete()){
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
    public function profile (request $request)
    {
        $id=$request->student;
        $student=Admission::leftjoin('add_stream','admission.class','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('setings as gen','admission.gender','=','gen.s_d')
        ->leftjoin('setings as dis','admission.disabled','=','dis.s_d')
        ->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),
        'admission_id','admission_no','gender','std_class.name as class',
        'gen.key_name as gender','dis.key_name as disabled','class_stream.name as stream','admission.date')
        ->where('admission_id',$id)->first();
        $placement=NewPlacement::leftjoin('add_stream','new_placement.rep_of','=','add_stream.id')
        ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
        ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('position','new_placement.position','=','position.id')
        ->select('new_placement.date','date_upto','position',db::raw("CONCAT(std_class.name,' ',class_stream.name)as rep_of")
        ,'position.name as position','new_placement.description')
        ->where('student',$id)->first();
        if(!empty($placement)){
        return response()->json([
            'message'=>'success',
            'student'=>$student,
            'placement'=>$placement
        ]);
    }else{
        return response()->json(['message'=>'no data found', 
       
                             ]);

       }
    }
}
