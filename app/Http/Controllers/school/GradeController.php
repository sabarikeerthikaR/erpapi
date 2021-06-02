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
use App\Models\Grade;

class GradeController extends Controller
{
    public function store(Request $Grade)
    {
      $validator =  Validator::make($Grade->all(), [
            'title' => ['required', 'string'],
            'remarks' => ['required', 'string'],  
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Grade=Grade::create([

        'title'  =>$Grade->title ,
        'remarks'  =>$Grade->remarks ,
        
         ]);
        if($Grade->save()){
                  return response()->json([
                 'message'  => 'Grade saved successfully',
                 'data'  => $Grade 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Grade = Grade::find($request->gradings_id);
             if(!empty($Grade)){
                    return response()->json([
                    'data'  => $Grade      
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
        $Grade = Grade::all();
        return response()->json(['status' => 'Success', 'data' => $Grade]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'title' => ['required', 'string'],
            'remarks' => ['required', 'string'], 
          
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Grade = Grade::find($request->gradings_id);
        $Grade->title = $request->title ;
        $Grade->remarks = $request->remarks ;
        if($Grade->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Grade
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Grade = Grade::find($request->gradings_id);
        if(!empty($Grade))

                {
                  if($Grade->delete()){
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
