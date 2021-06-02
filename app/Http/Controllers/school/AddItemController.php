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
use App\Models\Add_item;
use App\Models\Item_category;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class AddItemController extends Controller
{
    public function store(Request $Add_item)
    {
      $validator =  Validator::make($Add_item->all(), [
            'name' => ['required'],
            'category_id' => ['required'],
          
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Add_item=Add_item::create([

        'name'  =>$Add_item->name,
        'category_id'          =>$Add_item->category_id,
        'reorder_level'         =>$Add_item->reorder_level,
        'description'        =>$Add_item->description,
        'created_by'        =>Auth::user()->user_role,
         ]); dd($Add_item);
         $settings=Settings::create([
            'group_name'=>'item',
            'key_name'=>$Add_item->name,
            'key_value'=>$Add_item->item_id,
            ]);
            $settings->save();
        if($Add_item->save()){
                  return response()->json([
                 'message'  => 'Add_item saved successfully',
                 'data'  => $Add_item 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Add_item = Add_item::find($request->item_id);
             if(!empty($Add_item)){
                    return response()->json([
                    'data'  => $Add_item      
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
        $Add_item = Add_item::join('item_category','add_item.category_id','=','item_category.item_category_id')
        ->join('user_group','add_item.created_by','=','user_group.group_id')
        ->select('add_item.name','reorder_level','add_item.description','user_group.name as created_by','item_category.name as category','item_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Add_item]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'name' => ['required'],
            'category_id' => ['required'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Add_item = Add_item::find($request->item_id);
        $Add_item->name= $request->title;
        $Add_item->category_id= $request->category_id;
                $Add_item->reorder_level= $request->reorder_level;
        $Add_item->description= $request->description;
       
        if($Add_item->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Add_item
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Add_item = Add_item::find($request->item_id);
        if(!empty($Add_item))

                {
                  if($Add_item->delete()){
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
