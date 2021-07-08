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
use App\Models\Books_category;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class BooksCategoryController extends Controller
{
   public function store(Request $Books_category)
    {
      $validator =  Validator::make($Books_category->all(), [
      	    'name' =>['required'],
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Books_category=Books_category::create([
         'name'  =>$Books_category->name,
         'description'  =>$Books_category->description,
          'added_by'  =>auth::user()->id
        
         ]);
         $settings=Settings::create([
            'group_name'=>'books_category',
            'key_name'=>$Books_category->name,
            'key_value'=>$Books_category->book_category_id,
            ]);
            
            $settings->save();
        if($Books_category->save()){
                  return response()->json([
                 'message'  => 'Books_category saved successfully',
                 'data'  => $Books_category 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Books_category = Books_category::find($request->book_category_id);
             if(!empty($Books_category)){
                    return response()->json([
                    'data'  => $Books_category      
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
        $Books_category = Books_category::leftjoin('users','books_category.added_by','=','users.id')
        ->select('name','description',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as added_by"),'created_on','book_category_id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Books_category]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'name' =>['required'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Books_category = Books_category::find($request->book_category_id);
        $Books_category->name= $request->name;
        $Books_category->description= $request->description;
        $settings=Settings::where('group_name','=','books_category')->where('key_value',$request->book_category_id)->first();
        $settings->key_name= $request->name;
        $settings->save();
        if($Books_category->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Books_category
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Books_category = Books_category::find($request->books_category_id);
        $settings=Settings::where('group_name','=','books_category')->where('key_value',$request->book_category_id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($Books_category))

                {
                  if($Books_category->delete()){
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
