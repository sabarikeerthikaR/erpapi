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
use App\Models\Address_book;

class AddressBookController extends Controller
{
   public function store(Request $Address_book)
    {
      $validator =  Validator::make($Address_book->all(), [
            'name' => ['required'],
            
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Address_book=Address_book::create([

        'name'  =>$Address_book->name,
         
       
         ]);
         $settings=Settings::create([
            'group_name'=>'address_book',
            'key_name'=>$Address_book->name,
            'key_value'=>$Address_book->id,
            ]);
            $settings->save();
        if($Address_book->save()){
                  return response()->json([
                 'message'  => 'Address_book saved successfully',
                 'data'  => $Address_book 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Address_book = Address_book::find($request->id);
             if(!empty($Address_book)){
                    return response()->json([
                    'data'  => $Address_book      
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
        $Address_book = Address_book::all();
        return response()->json(['status' => 'Success', 'data' => $Address_book]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'name' => ['required'],
            
           
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Address_book = Address_book::find($request->id);
        $Address_book->name= $request->name;
        $settings=Settings::where('group_name','=','address_book')->where('key_value',$request->id)->first();
         $settings->key_name= $request->name;
         $settings->save();
        
        if($Address_book->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Address_book
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Address_book = Address_book::find($request->id);
        $settings=Settings::where('group_name','=','address_book')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($Address_book))

                {
                  if($Address_book->delete()){
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
