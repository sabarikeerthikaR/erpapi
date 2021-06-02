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
use App\Models\Rules_regulations;

class RulesRegulationsController extends Controller
{
    public function store(Request $Rules_regulations)
    {
      
        $Rules_regulations=Rules_regulations::create([

        'title'  =>$Rules_regulations->title ,
        'content'  =>$Rules_regulations->content ,
       
        
         ]);
        if($Rules_regulations->save()){
                  return response()->json([
                 'message'  => 'Rules_regulations saved successfully',
                 'data'  => $Rules_regulations 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Rules_regulations = Rules_regulations::find($request->id);
             if(!empty($Rules_regulations)){
                    return response()->json([
                    'data'  => $Rules_regulations      
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
        $Rules_regulations = Rules_regulations::all();
        return response()->json(['status' => 'Success', 'data' => $Rules_regulations]);
    }


public function update(Request $request)

   {
    
    $Rules_regulations = Rules_regulations::find($request->id);
        $Rules_regulations->title = $request->title ;
        $Rules_regulations->content = $request->content ;
       
       
        if($Rules_regulations->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Rules_regulations
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Rules_regulations = Rules_regulations::find($request->id);
        if(!empty($Rules_regulations))

                {
                  if($Rules_regulations->delete()){
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
