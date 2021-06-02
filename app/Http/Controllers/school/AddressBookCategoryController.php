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
use App\Models\Address_book_category;
use App\Models\Settings;

class AddressBookCategoryController extends Controller
{
     public function store(Request $Address_book_category)
    {
     
        $Address_book_category=Address_book_category::create([

        'name'  =>$Address_book_category->name,
         'description'  =>$Address_book_category->description,
       
         ]);
         $Address_book_category->save();
         $id=$Address_book_category->id;
         $settings=Settings::create([
            'group_name'=>'address_book_category',
            'key_name'=>$Address_book_category->name,
            'key_value'=>$id,
            ]);
            $settings->save();
        if($Address_book_category->save()){
                  return response()->json([
                 'message'  => 'Address_book_category saved successfully',
                 'data'  => $Address_book_category 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Address_book_category = Address_book_category::find($request->id);
             if(!empty($Address_book_category)){
                    return response()->json([
                    'data'  => $Address_book_category      
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
        $Address_book_category = Address_book_category::all();
        return response()->json(['status' => 'Success', 'data' => $Address_book_category]);
    }


public function update(Request $request)

   {
   
    $Address_book_category = Address_book_category::find($request->id);
        $Address_book_category->name= $request->name;
         $settings=Settings::where('group_name','=','address_book_category')->where('key_value',$request->id)->first();
         $settings->key_name= $request->name;
         $settings->save();
        if($Address_book_category->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Address_book_category
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Address_book_category = Address_book_category::find($request->id);
        if(!empty($Address_book_category))

                {
                  if($Address_book_category->delete()){
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
    public function storeAddress(Request $Address_book_category)
    {
     
        $Address_book_category=Address_book_category::create([

        'name'  =>$Address_book_category->name,
         
         ]);
        if($Address_book_category->save()){
                  return response()->json([
                 'message'  => 'Address_book_category saved successfully',
                 'data'  => $Address_book_category 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
}
