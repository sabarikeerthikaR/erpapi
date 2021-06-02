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
use App\Models\Library_settings;

class LibrarySettingsController extends Controller
{
    public function store(Request $Library_settings)
    {
      $validator =  Validator::make($Library_settings->all(), [
            'fine_per_day' => ['required'],
            'book_duration' => ['required'],
            'borrow_limit'    => ['required'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Library_settings=Library_settings::create([

        'fine_per_day'  =>$Library_settings->fine_per_day,
        'book_duration' =>$Library_settings->book_duration,
        'borrow_limit'  =>$Library_settings->borrow_limit,
         ]);
        if($Library_settings->save()){
                  return response()->json([
                 'message'  => 'Library_settings saved successfully',
                 'data'  => $Library_settings 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Library_settings = Library_settings::find($request->library_setting_id);
             if(!empty($Library_settings)){
                    return response()->json([
                    'data'  => $Library_settings      
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
        $Library_settings = Library_settings::all();
        return response()->json(['status' => 'Success', 'data' => $Library_settings]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
            'fine_per_day' => ['required'],
            'book_duration' => ['required'],
            'borrow_limit'    => ['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Library_settings = Library_settings::find($request->library_setting_id);
        $Library_settings->fine_per_day= $request->fine_per_day;
        $Library_settings->book_duration= $request->book_duration;
                $Library_settings->borrow_limit= $request->borrow_limit;
        if($Library_settings->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Library_settings
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Library_settings = Library_settings::find($request->library_setting_id);
        if(!empty($Library_settings))

                {
                  if($Library_settings->delete()){
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
