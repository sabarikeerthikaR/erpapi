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
use App\Models\ClearanceDepartments;
use App\Models\Settings;

class ClearanceDepartmentsController extends Controller
{
    public function store(Request $ClearanceDepartments)
    {
    	 $validator =  Validator::make($ClearanceDepartments->all(), [

            'name' => ['required', 'string'],
           
            
        ]); 
           if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $ClearanceDepartments=ClearanceDepartments::create([
      
       
        'name'          =>$ClearanceDepartments->name,
        'description'         =>$ClearanceDepartments->description,
       
        
         ]);
         $ClearanceDepartments->save();
         $id=$ClearanceDepartments->department_id;
         $settings=Settings::create([
            'group_name'=>'department',
            'key_name'=>$ClearanceDepartments->name,
            'key_value'=>$id,
            ]);
            $settings->save();
        if($ClearanceDepartments->save()){
                  return response()->json([
                 'message'  => 'ClearanceDepartments saved successfully',
                 'data'  => $ClearanceDepartments 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $ClearanceDepartments = ClearanceDepartments::find($request->id);
             if(!empty($ClearanceDepartments)){
                    return response()->json([
                    'data'  => $ClearanceDepartments      
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
        $ClearanceDepartments = ClearanceDepartments::all();
        return response()->json(['knec_code' => 'Success', 'data' => $ClearanceDepartments]);
    }


public function update(Request $request)

   {
   	
    $ClearanceDepartments = ClearanceDepartments::find($request->id);
       
    
       $ClearanceDepartments->name= $request->name;
       $ClearanceDepartments->description= $request->description;
       $settings=Settings::where('group_name','=','department')->where('key_value',$request->id)->first();
        $settings->key_name= $request->name;
        $settings->save();
      
       
        if($ClearanceDepartments->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $ClearanceDepartments
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $ClearanceDepartments = ClearanceDepartments::find($request->id);
        $settings=Settings::where('group_name','=','department')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($ClearanceDepartments))

                {
                  if($ClearanceDepartments->delete()){
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
