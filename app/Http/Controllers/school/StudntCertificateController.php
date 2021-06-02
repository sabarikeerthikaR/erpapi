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
use App\Models\Studnt_certificate;

class StudntCertificateController extends Controller
{
     public function store(Request $Studnt_certificate)
    {
      $validator =  Validator::make($Studnt_certificate->all(), [
            'student_name' => ['required', 'string'],
            'date' => ['required', 'string'],
            'title' => ['required', 'string'],
            'certificate_no' => ['required', 'string'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Studnt_certificate=Studnt_certificate::create([

        'student_name'  =>$Studnt_certificate->student_name ,
        'date'  =>$Studnt_certificate->date ,
        'title'  =>$Studnt_certificate->title ,
        'certificate_no'  =>$Studnt_certificate->certificate_no ,
        'upload_certificate'  =>$Studnt_certificate->upload_certificate ,
        
         ]);
        if($Studnt_certificate->save()){
                  return response()->json([
                 'message'  => 'Studnt_certificate saved successfully',
                 'data'  => $Studnt_certificate 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Studnt_certificate = Studnt_certificate::find($request->std_certificate);
             if(!empty($Studnt_certificate)){
                    return response()->json([
                    'data'  => $Studnt_certificate      
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
        $Studnt_certificate = Studnt_certificate::all();
        return response()->json(['status' => 'Success', 'data' => $Studnt_certificate]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
         'student_name' => ['required', 'string'],
            'date' => ['required', 'string'],
            'title' => ['required', 'string'],
            'certificate_no' => ['required', 'string'],
            'upload_certificate' => ['required','mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf','max:2048'],
          
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Studnt_certificate = Studnt_certificate::find($request->std_certificate);
        $Studnt_certificate->student_name = $request->student_name ;
        $Studnt_certificate->date = $request->date ;
        $Studnt_certificate->title = $request->title ;
        $Studnt_certificate->certificate_no = $request->certificate_no ;
        $Studnt_certificate->upload_certificate = $request->upload_certificate ;
       
        if($Studnt_certificate->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Studnt_certificate
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Studnt_certificate = Studnt_certificate::find($request->std_certificate);
        if(!empty($Studnt_certificate))

                {
                  if($Studnt_certificate->delete()){
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
}
