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
use App\Models\Certificate_base;
use App\Models\Certificate_type;

class CertificateSubjectController extends Controller
{
    public function store(Request $certificate_type)
    {
        $data=$certificate_type->data;
        $errors=[];
        foreach($data as $g)
        {
         
        $Gradings = new Certificate_base(array(
          'subject'   =>$g['subject'],
          'grade'=>$g['grade'],
          'certificate_type'=>$certificate_type->certificate_type,
          'serial_number'=>$certificate_type->serial_number,
          'student'=>$certificate_type->student,
          'mean_grade'=>$certificate_type->mean_grade,
          'points'=>$certificate_type->points,
          'certificate'=>$certificate_type->certificate,

         ));
          if(!$Gradings->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'certificate saved successfully',
              'certificate_type'=>$certificate_type->certificate_type,
              'serial_number'=>$certificate_type->serial_number,
              'student'=>$certificate_type->student,
              'mean_grade'=>$certificate_type->mean_grade,
              'points'=>$certificate_type->points,
              'certificate'=>$certificate_type->certificate,
              'data'  => $data,
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
     $certificate_type = Certificate_base::find($request->id)
      ->select('student','certificate_type','serial_number','mean_data','points','id','subject','grade')->where('Certificate_base.id','=',$request->id)->get();
      

             if(!empty($certificate_type)){
                    return response()->json([
                    'certificate_type'  => $certificate_type,
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
        $certificate_type = Certificate_base::all();
        return response()->json(['status' => 'Success', 'data' => $certificate_type]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
            'student' => ['required'],
             'certificate_type' => ['required'],
              'serial_number' => ['required'],
               'mean_data' => ['required'],
                 'certificate' => ['required'],
          
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $certificate_type = Certificate_base::find($request->id);
        $certificate_type->student = $request->student ;
         $certificate_type->certificate_type = $request->certificate_type ;
          $certificate_type->serial_number = $request->serial_number ;
           $certificate_type->mean_data = $request->mean_data ;
            $certificate_type->points = $request->points ;
             $certificate_type->certificate = $request->certificate ;
             $certificate_type->subject = $request->subject ;
             $certificate_type->grade = $request->grade ;
        
        if($certificate_type->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $certificate_type
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $id=($request->id);
        $certificate_type1=DB::table('Certificate_subject')->where('Certificate_subject.base_id','=',$id)->delete();
    

        if(!empty($certificate_type1))

                {
                  return response()->json([
                  'message'  => 'successfully deleted'
                   ]);
           }else
           {
           return response()->json([
                 'message'  => 'No data found in this id'  
                 ]);
            }
    }
}
