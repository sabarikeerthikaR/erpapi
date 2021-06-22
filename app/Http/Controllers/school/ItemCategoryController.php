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
use App\Models\Item_category;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class ItemCategoryController extends Controller
{
    public function store(Request $Item_category)
    {
      $validator =  Validator::make($Item_category->all(), [
            'name' => ['required'],
           
            
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Item_category=Item_category::create([

        'name'  =>$Item_category->name,
        'description'          =>$Item_category->description,
        'created_by'         =>Auth::user()->id,
        
         ]);
         $settings=Settings::create([
            'group_name'=>'item_category',
            'key_name'=>$Item_category->name,
            'key_value'=>$Item_category->item_category_id,
            ]);
            $settings->save();
        if($Item_category->save()){
                  return response()->json([
                 'message'  => 'Item_category saved successfully',
                 'data'  => $Item_category 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Item_category = Item_category::find($request->item_category_id);
           if(!empty($Item_category)){
                    return response()->json([
                    'data'  => $Item_category      
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
        $Item_category = Item_category::leftjoin('users','item_category.created_by','=','users.id')->select('item_category_id','name','description',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),'',last_name)as created_by"),DB::raw("DATE_FORMAT(item_category.created_at, '%Y-%m-%d') as created_on"))->get();
        return response()->json(['status' => 'Success', 'data' => $Item_category]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'name' => ['required'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Item_category = Item_category::find($request->item_category_id);
        $Item_category->name= $request->name;
        $Item_category->description= $request->description;
        $settings=Settings::where('group_name','=','item_category')->where('key_value',$request->item_category_id)->first();
        $settings->key_name= $request->name;
        $settings->save();      
        if($Item_category->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Item_category
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Item_category = Item_category::find($request->item_category_id);
        $settings=Settings::where('group_name','=','item_category')->where('key_value',$request->item_category_id)->first();
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->key_name=NULL;
        $settings->save();  
        if(!empty($Item_category))

                {
                  if($Item_category->delete()){
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
