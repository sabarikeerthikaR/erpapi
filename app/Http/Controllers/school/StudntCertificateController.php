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
      $valiDationArray =[
            'student_name' => ['required'],
            'date' => ['required'],
            'title' => ['required'],
            'certificate_no' => ['required'],
           
          ]; 
      if($Studnt_certificate->upload_certificate)
       {
         $valiDationArray["upload_certificate"]='required|file';
       }
        $validator =  Validator::make($Studnt_certificate->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $upload_certificate='';
        if($Studnt_certificate->file('upload_certificate')){
        $upload_certificate = $Studnt_certificate->file('upload_certificate');
        $imgName = time() . '.' . pathinfo($upload_certificate->getClientOriginalName(), PATHINFO_EXTENSION);
        Storage::disk('public_uploads')->put('/upload_certificate/' . $imgName, file_get_contents($upload_certificate));
        $upload_certificate=config('app.url').'/public/uploads/upload_certificate/' . $imgName;
        }
        $Studnt_certificate=Studnt_certificate::create([

        'student_name'  =>$Studnt_certificate->student_name ,
        'date'  =>$Studnt_certificate->date ,
        'title'  =>$Studnt_certificate->title ,
        'certificate_no'  =>$Studnt_certificate->certificate_no ,
        'upload_certificate'  =>$upload_certificate ,
        'description'  =>$Studnt_certificate->description ,
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
    $valiDationArray = [
         'student_name' => ['required'],
            'date' => ['required'],
            'title' => ['required'],
            'certificate_no' => ['required'],
          
        ]; 
          if($request->upload_certificate)
     {
       $valiDationArray["upload_certificate"]='required|file';
     }
      $Studnt_certificate = Studnt_certificate::find($request->std_certificate);
      if($request->file('upload_certificate')){
      $upload_certificate = $request->file('upload_certificate');
      $imgName = time() . '.' . pathinfo($upload_certificate->getClientOriginalName(), PATHINFO_EXTENSION);
      Storage::disk('public_uploads')->put('/upload_certificate/' . $imgName, file_get_contents($upload_certificate));
      $upload_certificate=config('app.url').'/public/uploads/upload_certificate/' . $imgName;
      $Admission->upload_certificate=$upload_certificate;
      }
    
        $Studnt_certificate->student_name = $request->student_name ;
        $Studnt_certificate->date = $request->date ;
        $Studnt_certificate->title = $request->title ;
        $Studnt_certificate->certificate_no = $request->certificate_no ;
        $Studnt_certificate->description = $request->description ;
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
