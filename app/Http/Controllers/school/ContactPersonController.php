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
use App\Models\ContactPerson;

class ContactPersonController extends Controller
{
   public function store(Request $ContactPerson)
    {
    	 $validator =  Validator::make($ContactPerson->all(), [
    	 	
            'name' => ['required'],
            'phone' => ['required'],
            'designation' => ['required'],
            
            
        ]); 
           if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $ContactPerson=ContactPerson::create([
      
        'name'    =>$ContactPerson->name,
        'phone'          =>$ContactPerson->phone,
        'designation'         =>$ContactPerson->designation,
        'email'        =>$ContactPerson->email,
        
         ]);
        if($ContactPerson->save()){
                  return response()->json([
                 'message'  => 'ContactPerson saved successfully',
                 'data'  => $ContactPerson 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)

    { 
        $p=$request->all();
        $id=$p['institution_id'];
        DB::enableQueryLog();
    $ContactPerson = ContactPerson::where('institution_id',$id)->first();

             if(!empty($ContactPerson)){
                    return response()->json([
                    'data'  => $ContactPerson      
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
        $ContactPerson = ContactPerson::all();
        return response()->json(['knec_code' => 'Success', 'data' => $ContactPerson]);
    }


public function update(Request $request)

   {
   	 $validator =  Validator::make($request->all(), [
   	 	
        'name' => ['required'],
        'phone' => ['required'],
        'designation' => ['required'],
           
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $p=$request->all();
        $id=$p['institution_id'];
        DB::enableQueryLog();
    $ContactPerson = ContactPerson::where('institution_id',$id)->first();
       
       $ContactPerson->name= $request->name;
       $ContactPerson->phone= $request->phone;
       $ContactPerson->designation= $request->designation;
       $ContactPerson->email= $request->email;
      
       
        if($ContactPerson->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $ContactPerson
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $p=$request->all();
        $id=$p['institution_id'];
        DB::enableQueryLog();
    $ContactPerson = ContactPerson::where('institution_id',$id)->first();
       
        $ContactPerson = ContactPerson::find($request->id);
        if(!empty($ContactPerson))

                {
                  if($ContactPerson->delete()){
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
