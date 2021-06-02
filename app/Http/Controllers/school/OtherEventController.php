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
use App\Models\Other_event;

class OtherEventController extends Controller
{
   public function store(Request $Other_event)
    {
      $validator =  Validator::make($Other_event->all(), [
            'title' => ['required', 'string'],
            'date' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'venue' => ['required','string'],
            'description' => ['required', 'string'],
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Other_event=Other_event::create([

        'title'  =>$Other_event->title ,
        'date'  =>$Other_event->date ,
        'start_time'  =>$Other_event->start_time ,
        'end_time'  =>$Other_event->end_time ,
        'venue'  =>$Other_event->venue ,
         'description'  =>$Other_event->description ,

        
         ]);
        if($Other_event->save()){
                  return response()->json([
                 'message'  => 'Other_event saved successfully',
                 'data'  => $Other_event 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Other_event = Other_event::find($request->id);
             if(!empty($Other_event)){
                    return response()->json([
                    'data'  => $Other_event      
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
        $Other_event = Other_event::all();
        return response()->json(['status' => 'Success', 'data' => $Other_event]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'title' => ['required', 'string'],
            'date' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'venue' => ['required','string'],
            'description' => ['required', 'string'],
          
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Other_event = Other_event::find($request->id);
        $Other_event->title = $request->title ;
        $Other_event->date = $request->date ;
        $Other_event->start_time = $request->start_time ;
        $Other_event->end_time = $request->end_time;
        $Other_event->venue = $request->venue ;
        $Other_event->description = $request->description ;
       
        if($Other_event->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Other_event
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Other_event = Other_event::find($request->id);
        if(!empty($Other_event))

                {
                  if($Other_event->delete()){
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
