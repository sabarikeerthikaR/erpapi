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
use App\Models\Position;
use App\Models\Settings;
use Hamcrest\Core\Set;

class PositionController extends Controller
{
    public function store(Request $Position)
    {
      
        $Position=Position::create([

        'name'  =>$Position->name ,
        'description'  =>$Position->description ,
        
         ]); 
         $id=$Position->id;
         $settings=Settings::create([
             'key_name'=>$Position->name,
             'group_name'=>'placement_position',
             'key_value'=>$id
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
     $Position = Position::find($request->id);
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
        $Position = Position::all();
        return response()->json(['status' => 'Success', 'data' => $Position]);
    }


public function update(Request $request)

   {
   
    $Position = Position::find($request->id);
        $Position->name = $request->name ;
        $Position->description = $request->description ;
        $settings=Settings::where('group_name','=','placement_position')->where('key_value',$request->id)->first();
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
        $Position = Position::find($request->id);
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
