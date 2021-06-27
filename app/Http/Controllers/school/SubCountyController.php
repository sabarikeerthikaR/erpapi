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
use App\Models\SubCounty;
use App\Models\Settings;
use App\Models\Counties;

class SubCountyController extends Controller
{
    public function store(Request $SubCounty)
    {
    	 $validator =  Validator::make($SubCounty->all(), [

            'county' => ['required'],
            'sub_county' => ['required'],
            
           
            
        ]); 
           if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $SubCounty=SubCounty::create([
      
       
        'county'          =>$SubCounty->county,
        'sub_county'         =>$SubCounty->sub_county,
       
        
         ]);
         $SubCounty->save();
         $id=$SubCounty->id;
         $settings=Settings::create([
            'group_name'=>'sub_county',
            'key_name'=>$SubCounty->sub_county,
            'key_value'=>$id,
            ]);
            $settings->save();
        if($SubCounty->save()){
                  return response()->json([
                 'message'  => 'SubCounty saved successfully',
                 'data'  => $SubCounty 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $SubCounty = SubCounty::where('id',$request->id)
               ->select('sub_county','sub_county.id as subCounty_id','county as county_id')->first();
             if(!empty($SubCounty)){
                    return response()->json([
                    'data'  => $SubCounty      
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
        $SubCounty = SubCounty::join('counties','sub_county.county','=','counties.id')->select('counties.name as county','sub_county','sub_county.id as subCounty_id','counties.id as county_id')->get();
        return response()->json(['knec_code' => 'Success', 'data' => $SubCounty]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
   	 	
           'county' => ['required'],
            'sub_county' => ['required'],

        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $SubCounty = SubCounty::find($request->id);
       
    
       $SubCounty->county= $request->county;
       $SubCounty->sub_county= $request->sub_county;
      
       $settings=Settings::where('group_name','=','sub_county')->where('key_value',$request->id)->first();
        $settings->key_name= $request->sub_county;
        $settings->save();
       
        if($SubCounty->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $SubCounty
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $SubCounty = SubCounty::find($request->id);
        $settings=Settings::where('group_name','=','sub_county')->where('key_value',$request->id)->first();
        $settings->key_name=NULL;
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
       
        if(!empty($SubCounty))

                {
                  if($SubCounty->delete()){
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
