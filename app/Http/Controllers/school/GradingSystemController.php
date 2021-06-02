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
use App\Models\Grading_system;
use App\Models\Settings;

class GradingSystemController extends Controller
{
   public function store(Request $Grading_system)
    {
      $validator =  Validator::make($Grading_system->all(), [
            'title' => ['required'],
            'pass_mark' => ['required'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Grading_system=Grading_system::create([

        'title'  =>$Grading_system->title ,
        'pass_mark'  =>$Grading_system->pass_mark ,
        'description'  =>$Grading_system->description ,
        'created_by'  =>'admin' ,
        'created_on'  =>date('Y-m-d') ,
        
         ]);
         $settings=Settings::create([
            'key_name'=>$Grading_system->title ,
            'group_name'=>'grading_system',
            'key_value'=>$Grading_system->grading_systm_id,
          ]);
  $settings->save();
        if($Grading_system->save()){
                  return response()->json([
                 'message'  => 'Grading_system saved successfully',
                 'data'  => $Grading_system 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Grading_system = Grading_system::find($request->grading_systm_id);
             if(!empty($Grading_system)){
                    return response()->json([
                    'data'  => $Grading_system      
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
        $Grading_system = Grading_system::select('title','pass_mark','created_by','created_on','grading_systm_id','description')->get();
        return response()->json(['status' => 'Success', 'data' => $Grading_system]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
            'title' => ['required'],
            'pass_mark' => ['required'],
          
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Grading_system = Grading_system::find($request->grading_systm_id);
        $Grading_system->title = $request->title ;
        $Grading_system->pass_mark = $request->pass_mark ;
         $Grading_system->description = $request->description ;
         $settings=Settings::where('group_name','=','grading_system')->where('key_value',$request->grading_systm_id)->first();
         $settings->key_name=  $request->title ;
         $settings->save();
        
        if($Grading_system->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Grading_system
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Grading_system = Grading_system::find($request->grading_systm_id);
        $settings=Settings::where('group_name','=','grading_system')->where('key_value',$request->grading_systm_id)->first();
         $settings->key_name=NULL;
         $settings->group_name=NULL;
         $settings->key_value=NULL;
         $settings->save();
        if(!empty($Grading_system))

                {
                  if($Grading_system->delete()){
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
