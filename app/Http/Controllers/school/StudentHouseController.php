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
use App\Models\Student_house;
use App\Models\Settings;
use App\Models\Staff;


class StudentHouseController extends Controller
{
     public function store(Request $Student_house)
    {
      $validator =  Validator::make($Student_house->all(), 
          [ 
            'name'=> ['required'],
           
            'leader'=> ['required'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Student_house=Student_house::create([
        
        'name'  =>$Student_house->name,
        'slogan'  =>$Student_house->slogan,
        'leader'  =>$Student_house->leader,
        'description'  =>$Student_house->description,
         ]);
         $Student_house->save();
         $id=$Student_house->house_id;
         $settings=Settings::create([
            'group_name'=>'house',
            'key_name'=>$Student_house->name,
            'key_value'=>$id,
            ]);
            $settings->save();
        if($Student_house->save()){
                  return response()->json([
                 'message'  => 'Student_house saved successfully',
                 'data'  => $Student_house 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Student_house = Student_house::find($request->house_id);
             if(!empty($Student_house)){
                    return response()->json([
                    'data'  => $Student_house      
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
        $Student_house = Student_house::leftjoin('staff','student_house.leader','=','staff.employee_id')
        ->select(db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as leader"),
        'student_house.name','slogan','description','house_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Student_house]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
         
            'name'=> ['required'],
            
            'leader'=> ['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Student_house = Student_house::find($request->house_id);
        $Student_house->name= $request->name;
        $Student_house->slogan= $request->slogan;
        $Student_house->leader= $request->leader;
        $Student_house->description= $request->description;
        $Student_house->save();
        $settings=Settings::where('group_name','=','house')->where('key_value',$request->house_id)->first();
        $settings->key_name= $request->name;
        $settings->save();
        if($Student_house->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Student_house
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Student_house = Student_house::find($request->house_id);
        $settings=Settings::where('group_name','=','house')->where('key_value',$request->house_id)->first();
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->key_name=NULL;
        $settings->save();
        if(!empty($Student_house))

                {
                  if($Student_house->delete()){
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
