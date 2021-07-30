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
use App\Models\Add_event;

class AddEventController extends Controller
{
     public function store(Request $Add_event)
    {
      $validator =  Validator::make($Add_event->all(), [
            'title' => ['required', 'string'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'venue' => ['required', 'string'],
            'visibility' => ['required','string'],
            'description' => ['required', 'string'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Add_event=Add_event::create([

        'title'  =>$Add_event->title ,
        'start_date'  =>$Add_event->start_date ,
        'end_date'  =>$Add_event->end_date ,
        'venue'  =>$Add_event->venue ,
        'visibility'  =>$Add_event->visibility ,
         'description'  =>$Add_event->description ,

        
         ]);
        if($Add_event->save()){
                  return response()->json([
                 'message'  => 'Add_event saved successfully',
                 'data'  => $Add_event 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Add_event = Add_event::find($request->id);
             if(!empty($Add_event)){
                    return response()->json([
                    'data'  => $Add_event      
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
        $Add_event = Add_event::join('setings','add_event.visibility','=','setings.s_d')->select('id','title','start_date','end_date','venue','setings.key_name as visibility','description')->orderBy('id','desc')->get();
        return response()->json(['status' => 'Success', 'data' => $Add_event]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
          'title' => ['required', 'string'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'venue' => ['required', 'string'],
            'visibility' => ['required','string'],
            'description' => ['required', 'string'],
          
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Add_event = Add_event::find($request->id);
        $Add_event->title = $request->title ;
        $Add_event->start_date = $request->start_date ;
        $Add_event->end_date = $request->end_date ;
        $Add_event->venue = $request->venue;
        $Add_event->visibility = $request->visibility ;
        $Add_event->description = $request->description ;
       
        if($Add_event->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Add_event
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Add_event = Add_event::find($request->id);
        if(!empty($Add_event))

                {
                  if($Add_event->delete()){
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


    public function academicEventView(request $request)
    {
        $Add_event = Add_event::join('setings','add_event.visibility','=','setings.s_d')->select('id','title','start_date','end_date','venue','setings.key_name as visibility','description')
        ->where('add_event.id',$request->id)->get();
        return response()->json(['status' => 'Success', 'data' => $Add_event]);
    }
}
