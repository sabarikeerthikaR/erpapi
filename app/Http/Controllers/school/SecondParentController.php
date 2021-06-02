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
use App\Models\Second_parent;

class SecondParentController extends Controller
{
    public function store(Request $Second_parent)
    {
      $validator =  Validator::make($Second_parent->all(), [
           'admission_id' => ['required', 'numeric'],
            'title'       => ['required', 'string'],
            'relation'    => ['required', 'string'],
            'first_name'  => ['required', 'string'],
            'last_name'   => ['required', 'string'],
            'phone'       => ['required','numeric','digits:10'],
            'email'       => ['required','email', 'unique:users'],
            'id_passport' => ['required', 'string'],
            'occupation'  => ['required', 'string'],
            'address'     => ['required', 'string'],
            'postal_code' => ['required', 'string'],
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Second_parent=Second_parent::create([
          'admission_id'  =>$Second_parent->admission_id,
        'title'  =>$Second_parent->title,
        'relation'          =>$Second_parent->relation,
        'first_name'         =>$Second_parent->first_name,
        'last_name'        =>$Second_parent->last_name,
        'phone'        =>$Second_parent->phone,
        'email'        =>$Second_parent->email,
        'id_passport'        =>$Second_parent->id_passport,
        'occupation'        =>$Second_parent->occupation,
        'address'        =>$Second_parent->address,
        'postal_code'        =>$Second_parent->postal_code,
        'passport_photo'        =>$Second_parent->passport_photo,
        'national_id'        =>$Second_parent->national_id,
         ]);
        if($Second_parent->save()){
                  return response()->json([
                 'message'  => 'Second_parent saved successfully',
                 'data'  => $Second_parent 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Second_parent = Second_parent::find($request->parent2_id );
             if(!empty($Second_parent)){
                    return response()->json([
                    'data'  => $Second_parent      
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
        $Second_parent = Second_parent::all();
        return response()->json(['status' => 'Success', 'data' => $Second_parent]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'admission_id' => ['required', 'numeric'],
            'title'       => ['required', 'string'],
            'relation'    => ['required', 'string'],
            'first_name'  => ['required', 'string'],
            'last_name'   => ['required', 'string'],
            'phone'       => ['required','numeric','digits:10'],
            'email'       => ['required','email', 'unique:users'],
            'id_passport' => ['required', 'string'],
            'occupation'  => ['required', 'string'],
            'address'     => ['required', 'string'],
            'postal_code' => ['required', 'string'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Second_parent = Second_parent::find($request->parent2_id );
       $Second_parent->admission_id= $request->admission_id;
        $Second_parent->title= $request->title;
        $Second_parent->relation= $request->relation;
        $Second_parent->first_name= $request->first_name;
        $Second_parent->last_name= $request->last_name;
        $Second_parent->phone= $request->phone;
        $Second_parent->email= $request->email;
        $Second_parent->id_passport= $request->id_passport;
        $Second_parent->occupation= $request->occupation;
        $Second_parent->address= $request->address;
        $Second_parent->postal_code= $request->postal_code;
        $Second_parent->passport_photo= $request->passport_photo;
        $Second_parent->national_id= $request->national_id;
        if($Second_parent->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Second_parent
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Second_parent = Second_parent::find($request->parent2_id );
        if(!empty($Second_parent))

                {
                  if($Second_parent->delete()){
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
