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
use App\Models\SettingsPositions;
use App\Models\Settings;

class SettingsPositionsController extends Controller
{
    public function store(Request $Position)
    {
      $validator =  Validator::make($Position->all(), [
            'name' => ['required']
            
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Position=SettingsPositions::create([

        'name'  =>$Position->name ,
        'description'  =>$Position->description ,
        
         ]); 
         $Position->save();
         $id=$Position->id;
         $settings=Settings::create([
            'group_name'=>'settings_position',
            'key_name'=>$Position->name,
            'key_value'=>$id,
            ]);
            $settings->save();
        if($Position->save()){
                  return response()->json([
                 'message'  => 'Position saved successfully',
                 'data'  => $Position 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Position = SettingsPositions::find($request->id);
             if(!empty($Position)){
                    return response()->json([
                    'data'  => $Position      
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
        $Position = SettingsPositions::all();
        return response()->json(['status' => 'Success', 'data' => $Position]);
    }


public function update(Request $request)

   {
   
    $Position = SettingsPositions::find($request->id);
        $Position->name = $request->name ;
        $Position->description = $request->description ;
        $settings=Settings::where('group_name','=','settings_position')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
       
        if($Position->save()){
            return response()->json([
                 'message'  => 'update successfully',
                 'data'  => $Position
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Position = SettingsPositions::find($request->id);
        if(!empty($Position))

                {
                  if($Position->delete()){
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
