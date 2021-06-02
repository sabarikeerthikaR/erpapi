<?php

namespace App\Http\Controllers\school;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Providers\user_roleServiceProvider;
use App\Http\Controllers\Controller;
use Illuminate\Database\Migrations\Migration;
use App\Models\User_group;
use App\Models\Settings;

class UserGroupController extends Controller
{
   public function store(Request $User_group)
    {
      $validator =  Validator::make($User_group->all(), [
            'name' => ['required','string']
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $User_group=User_group::create([

        'name'  =>$User_group->name,
        'description'  =>$User_group->description,
 
         ]);
         $settings=Settings::create([
            'group_name'=>'user_role',
            'key_name'=>$User_group->name,
            'key_value'=>$User_group->group_id,
            ]);
            $settings->save();
        if($User_group->save()){
                  return response()->json([
                 'message'  => 'User_group saved successfully',
                 'data'  => $User_group 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $User_group = User_group::find($request->group_id);
             if(!empty($User_group)){
                    return response()->json([
                    'data'  => $User_group      
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
        $User_group = User_group::all();
        return response()->json(['status' => 'Success', 'data' => $User_group]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
             'name' => ['required','string']
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $User_group = User_group::find($request->group_id);
        $User_group->name= $request->name;
        $User_group->description= $request->description;
        $settings=Settings::where('group_name','=','user_role')->where('key_value',$request->group_id)->first();
        $settings->key_name= $request->name;
        $settings->save();
        if($User_group->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $User_group
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $User_group = User_group::find($request->group_id);
        $settings=Settings::where('group_name','=','user_role')->where('key_value',$request->group_id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($User_group))

                {
                  if($User_group->delete()){
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
