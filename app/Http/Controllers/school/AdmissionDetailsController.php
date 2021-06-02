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
use App\Models\Admission;

class AdmissionDetailsController extends Controller
{
    public function store(Request $request)
    {
       
       $Admission = Admission::find($request->admission_id); 
        $Admission->date= $request->date;
        $Admission->class= $request->class;
        $Admission->admission_no= $request->admission_no;
        $Admission->house= $request->house;
        if($Admission->save()){
            return response()->json([
                 'message'  => 'success',
                 'data'  => $Admission
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
      }
public function show(request $request)
    { 
    	       $Admission = Admission::where('admission.admission_id',$request->admission_id)->select('date','class','admission_no','house')->get();
             if(!empty($Admission)){
                    return response()->json([
                    'data'  => $Admission      
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
        $Admission = Admission::all();
        return response()->json(['status' => 'Success', 'data' => $Admission]);
    }


public function update(Request $request)

   {
    $Admission = Admission::find($request->admission_id);
        $Admission->date= $request->date;
        $Admission->class= $request->class;
        $Admission->admission_no= $request->admission_no;
        $Admission->house= $request->house;
        if($Admission->save()){
            return response()->json([
                 'message'  => 'success',
                 'data'  => $Admission
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Admission = Admission::find($request->admission_id);
        if(!empty($Admission))

                {
                  if($Admission->delete()){
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
