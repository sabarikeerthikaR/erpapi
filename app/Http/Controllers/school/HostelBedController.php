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
use App\Models\HostelBed;
use App\Models\AssignBed;
use App\Models\Settings;

class HostelBedController extends Controller
{
     public function store(Request $HostelBed)
    {
        
        $bed=$HostelBed->bed;
        $errors=[];
        foreach($bed as $g)
        {
          
          
        $HostelBed = new HostelBed(array(
          'hostel_room'=>$g['hostel_room'],
          'bed_no'=>$g['bed_no'],    
          
         ));

        //  $settings= new Settings(array(
        //  'group_name'=>'bed',
        //  'key_name'=>$g['bed_no'],
        //  'key_value' =>
        //  ));
        //  return $settings;die;
        //  $settings->save();
          if(!$HostelBed->save())
          {
            $errors[]=$g;
          }
        } 

             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'HostelBed saved successfully',
              'data'     =>  $bed,
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

    $HostelBed = HostelBed::find($request->id);
             if(!empty($HostelBed)){
                    return response()->json([
                    'data'  => $HostelBed      
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
        $HostelBed = db::table('hostel_bed')->join('hostel_rooms','hostel_bed.hostel_room','=','hostel_rooms.id')->join('assign_bed','hostel_bed.id','=','assign_bed.bed')->join('admission','assign_bed.student','=','admission.admission_id')
        ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as full_name"),
        'hostel_bed.id','admission_id','bed_no','hostel_bed.created_at',
        'hostel_rooms.room_name','status')->first();
        return response()->json(['status' => 'Success', 'data' => $HostelBed]);
    }


public function update(Request $request)

   {
        
    $HostelBed = HostelBed::find($request->id);
        $HostelBed->hostel_room = $request->hostel_room ;
        $HostelBed->bed_no = $request->bed_no ;
        $settings=Settings::where('group_name','=','bed')->where('key_value',$request->id)->first();
        $settings->key_name= $request->bed_no;
        $settings->save();
        if($HostelBed->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $HostelBed
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
              }
      
}
public function destroy(Request $request)
    {
        $HostelBed = HostelBed::find($request->id);
        if(!empty($HostelBed))

                {
                  if($HostelBed->delete()){
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
