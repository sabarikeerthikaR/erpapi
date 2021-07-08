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
use App\Models\LeavingCertificate;
use App\Models\Admission;

class LeavingCertificateController extends Controller
{
    public function store(Request $LeavingCertificate)
    {
      $validator =  Validator::make($LeavingCertificate->all(), [
            
            'student' => ['required', 'string'],
            
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $year=date("Y");
        $date=date("d-m-Y");
        $LeavingCertificate=LeavingCertificate::create([

        'date'  =>$LeavingCertificate->date ,
        'student'  =>$LeavingCertificate->student ,
        'headteachr_remark'  =>$LeavingCertificate->headteachr_remark ,
        'curricular_activity'  =>$LeavingCertificate->curricular_activity ,
        'created_on'=>$date,
         'created_by'  =>'admin',
        
         ]); 

         $alumni = Admission::find($LeavingCertificate->student); 
       $alumni->completion_yr = $year;
       $alumni->save();
        if($LeavingCertificate->save()){
                  return response()->json([
                 'message'  => 'LeavingCertificate saved successfully',
                 'data'  => $LeavingCertificate 
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
     $LeavingCertificate = LeavingCertificate::where("student",$id)->first();
             if(!empty($LeavingCertificate)){
                    return response()->json([
                    'data'  => $LeavingCertificate      
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
        $LeavingCertificate = LeavingCertificate::join('admission','leaving_certificate.student','=','admission.admission_id')
        ->select(DB::raw("CONCAT(admission.first_name,' ',admission.middle_name,' ',admission.last_name)as full_name"),
        'leaving_certificate.date','leaving_certificate.headteachr_remark','leaving_certificate.curricular_activity',
        'leaving_certificate.created_by','leaving_certificate.created_on','admission_id')->get();
        return response()->json(['status' => 'Success', 'data' => $LeavingCertificate]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
            'student' => ['required']
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
         $year=date("Y");
        $p=$request->all();
        $id=$p['student'];
        DB::enableQueryLog();
    $LeavingCertificate = LeavingCertificate::where("student",$id)->first(); 
        $LeavingCertificate->date = $request->date ;
        $LeavingCertificate->student = $request->student ;
        $LeavingCertificate->headteachr_remark = $request->headteachr_remark ;
        $LeavingCertificate->curricular_activity = $request->curricular_activity;
        $LeavingCertificate->save();
        $alumni = Admission::find($id); 
       $alumni->completion_yr = $year;
       
      
        if($alumni->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $LeavingCertificate
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
    $LeavingCertificate = LeavingCertificate::where("student",$id)->first(); 
   $Admission = Admission::find($id); 
    $Admission->completion_yr= null;
    $Admission->save();

        if(!empty($LeavingCertificate))

                {
                  if($LeavingCertificate->delete()){
                  return response()->json([
                  'message'  => 'successfully deleted',
                  'data'  => $Admission
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
   public function profile(request $request)
   {
       $id=$request->student;
       $admission=Admission::leftjoin('add_stream','admission.class','=','add_stream.id')
       ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
       ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
       ->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as full_name"),
       'admission_no','dob',
       DB::raw("CONCAT(std_class.name,' ',class_stream.name) as class"))->where('admission_id',$id)->first();
       $leaving=LeavingCertificate::select('date','headteachr_remark','curricular_activity','created_on')->where('student',$id)->first();
       if(!empty($leaving))
       {
       return response()->json([
           'message'=>'success',
           'admission_details'=>$admission,
           'leaving details'=>$leaving
       ]);
    }else{
        return response()->json(['message'=>'no data found', 
       
                             ]);

       }
   }
    
}
