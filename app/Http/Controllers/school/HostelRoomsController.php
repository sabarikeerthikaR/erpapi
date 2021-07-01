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
use App\Models\HostelRooms;
use App\Models\HostelBed;
use App\Models\Hostel;
use App\Models\Settings;

class HostelRoomsController extends Controller
{
    public function store(Request $HostelRooms)
    {
        
        $Rooms=$HostelRooms->Rooms;
        $errors=[];
        foreach($Rooms as $g)
        {
        
        $HostelRooms = new HostelRooms(array(
          'hostel'=>$g['hostel'],
          'room_name'=>$g['room_name'],    
          'description'=>$g['description']
         ));
          //  $settings= new Settings(array(
        //  'group_name'=>'hostel_room',
        //  'key_name'=>$g['room_name'],
        //  'key_value' =>
        //  ));
        //  return $settings;die;
        //  $settings->save();
          if(!$HostelRooms->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'HostelRooms saved successfully',
             'data'  => $Rooms,
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

    $HostelRooms = HostelRooms::find($request->id);
             if(!empty($HostelRooms)){
                    return response()->json([
                    'data'  => $HostelRooms      
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
       $HostelBed = db::table('hostel_rooms')->leftjoin('hostel_bed','hostel_rooms.id','=','hostel_bed.hostel_room')
       ->leftjoin('hostel','hostel_rooms.hostel','=','hostel.id')
       ->select('hostel_rooms.id','hostel.title as hostel','bed_no','hostel_rooms.created_at','room_name','status','hostel_rooms.description',
   )
       ->groupBy('hostel_rooms.id')
       ->get();
        return response()->json(['status' => 'Success', 'data' => $HostelBed]);
    }


public function update(Request $request)

   {
        $Rooms=$request->Rooms;
        $errors=[];
        foreach($Rooms as $g)
        {
         
        $HostelRooms = HostelRooms::find($request->id); 
        if($HostelRooms){
        $HostelRooms['hostel']=$request['hostel'];
        $HostelRooms['room_name']=$request['room_name'];  
        $HostelRooms['description']=$request['description'];
        $settings=Settings::where('group_name','=','hostel_room')->where('key_value',$request->id)->first();
        $settings->key_name= $request->room_name;
        $settings->save();
         }
         if($HostelRooms->save())
         {
          
          $HostelRoom = new HostelRooms(array(
          'hostel'=>$g['hostel'],
          'room_name'=>$g['room_name'],    
          'description'=>$g['description']
          ));
          
        }
        if(!$HostelRooms->save())
          {
            $errors[]=$g;
          }

       }
           if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'HostelRooms updated successfully',
              'updated data'   =>     $HostelRooms,
               'data'  => $Rooms,
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
public function destroy(Request $request)
    {
        $HostelRooms = HostelRooms::find($request->id);
        if(!empty($HostelRooms))

                {
                  if($HostelRooms->delete()){
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
