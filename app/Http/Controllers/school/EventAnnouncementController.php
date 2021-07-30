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
use App\Models\Event_announcement;
use App\Models\Add_event;
use App\Models\Other_event;


class EventAnnouncementController extends Controller
{
    public function store(Request $Event_announcement)
    {
      $validator =  Validator::make($Event_announcement->all(), [
            'title' => ['required'],
            'description' => ['required'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Event_announcement=Event_announcement::create([

        'title'  =>$Event_announcement->title ,
         'description'  =>$Event_announcement->description ,

        
         ]);
        if($Event_announcement->save()){
                  return response()->json([
                 'message'  => 'Event_announcement saved successfully',
                 'data'  => $Event_announcement 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Event_announcement = Event_announcement::find($request->id);
             if(!empty($Event_announcement)){
                    return response()->json([
                    'data'  => $Event_announcement      
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
        $Event_announcement = Event_announcement::orderBy('id','desc')->get();
        return response()->json(['status' => 'Success', 'data' => $Event_announcement]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'title' => ['required'],
            'description' => ['required'],
          
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Event_announcement = Event_announcement::find($request->id);
        $Event_announcement->title = $request->title ;
        $Event_announcement->description = $request->description ;
       
        if($Event_announcement->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Event_announcement
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Event_announcement = Event_announcement::find($request->id);
        if(!empty($Event_announcement))

                {
                  if($Event_announcement->delete()){
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
    public function eventCalenderview(request $request)
    {
        $academicEvent=Add_event::join('setings','add_event.visibility','=','setings.s_d')->select('id as academic_event_id','title','start_date','end_date','venue','setings.key_name as visibility','description')->get();
        $otherEvent=Other_event::select('id as other_event_id','title','date','start_time','end_time','venue','description')->get();
        $array = array_merge($academicEvent->toArray(), $otherEvent->toArray());
           return response()->json([
                  'data'  => $array
                   ]);
        
    }
}
