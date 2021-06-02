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
use App\Models\Folder;

class FolderController extends Controller
{
    public function store(Request $Folder)
    {
      $validator =  Validator::make($Folder->all(), [
      	    'title' =>['required'],
            
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Folder=Folder::create([
         'title'  =>$Folder->title,
         'description'  =>$Folder->description,
        
         ]);
        if($Folder->save()){
                  return response()->json([
                 'message'  => 'Folder saved successfully',
                 'data'  => $Folder 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Folder = Folder::find($request->folder_id);
             if(!empty($Folder)){
                    return response()->json([
                    'data'  => $Folder      
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
        $Folder = Folder::all();
        return response()->json(['status' => 'Success', 'data' => $Folder]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'title' =>['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Folder = Folder::find($request->folder_id);
        $Folder->title= $request->title;
        $Folder->description= $request->description;
        if($Folder->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Folder
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Folder = Folder::find($request->folder_id);
        if(!empty($Folder))

                {
                  if($Folder->delete()){
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
