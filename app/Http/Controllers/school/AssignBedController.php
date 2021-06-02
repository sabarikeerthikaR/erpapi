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
use App\Models\AssignBed;
use App\Models\Settings;
use App\Models\HostelBed;


class AssignBedController extends Controller
{
    public function store(Request $AssignBed)
    {
      $validator =  Validator::make($AssignBed->all(), 
          [ 
            'date'    => ['required'],
            'student'=> ['required'],
            'term'    => ['required'],
            'year'    => ['required'],
            'bed'=> ['required'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $AssignBed=AssignBed::create([
        
        'date'  =>$AssignBed->date,
        'student'=>$AssignBed->student,
        'term'    =>$AssignBed->term,
        'year'  =>$AssignBed->year,
        'bed'=>$AssignBed->bed,
        'comment'    =>$AssignBed->comment,
         'assigned_by'    =>'admin',
         ]);
        if($AssignBed->save()){
                  return response()->json([
                 'message'  => 'AssignBed saved successfully',
                 'data'  => $AssignBed 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
             $AssignBed = AssignBed::find($request->id);
             if(!empty($AssignBed)){
                    return response()->json([
                    'data'  => $AssignBed      
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
        $AssignBed = AssignBed::join('admission','assign_bed.student','=','admission.admission_id')
        ->join('setings as term','assign_bed.term','=','term.s_d')
        ->join('setings as year','assign_bed.year','=','year.s_d')
        ->join('hostel_bed','assign_bed.bed','=','hostel_bed.id')
        ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name) as student")
        ,'term.key_name as term','year.key_name as year','hostel_bed.bed_no',
        'comment','assigned_by','assign_bed.date','assign_bed.id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $AssignBed]);
    }


public function update(Request $request)

   {
    
    $AssignBed = AssignBed::find($request->id);
        $AssignBed->date= $request->date;
        $AssignBed->student= $request->student;
        $AssignBed->term= $request->term;
        $AssignBed->year= $request->year;
        $AssignBed->bed= $request->bed;
        $AssignBed->comment= $request->comment;
        if($AssignBed->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $AssignBed
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $AssignBed = AssignBed::find($request->id);
        if(!empty($AssignBed))

                {
                  if($AssignBed->delete()){
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
