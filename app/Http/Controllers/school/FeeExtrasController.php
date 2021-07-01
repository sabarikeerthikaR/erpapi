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
use App\Models\Fee_extras;
use App\Models\Settings;

class FeeExtrasController extends Controller
{
   public function store(Request $Fee_extras)
    {
      $validator =  Validator::make($Fee_extras->all(), [
           'title' => ['required'],
            'fee_type' => ['required'],
            'amount' => ['required'],
             'charged' => ['required'],
              'description' => ['required'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Fee_extras=Fee_extras::create([

        'title'  =>$Fee_extras->title ,
        'fee_type'  =>$Fee_extras->fee_type ,
        'amount'  =>$Fee_extras->amount ,
        'charged'  =>$Fee_extras->charged ,
        'description'  =>$Fee_extras->description ,
         ]);
        $settings=Settings::create([
            'group_name'=>'fee_extras',
            'key_name'=>$Fee_extras->title ,
            'key_value'=>$Fee_extras->id,
            ]);
            $settings->save();
        if($Fee_extras->save()){
                  return response()->json([
                 'message'  => 'Fee_extras saved successfully',
                 'data'  => $Fee_extras 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Fee_extras = Fee_extras::find($request->id);
             if(!empty($Fee_extras)){
                    return response()->json([
                    'data'  => $Fee_extras      
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
        $Fee_extras = Fee_extras::join('setings as fee','fee_extras.fee_type','=','fee.s_d')
        ->join('setings as charge','fee_extras.charged','=','charge.s_d')
        ->select('fee.key_name as fee_type','amount','description','charge.key_name as charge','fee_extras.id','title')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Fee_extras]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
      'title' => ['required'],
            'fee_type' => ['required'],
            'amount' => ['required'],
             'charged' => ['required'],
              'description' => ['required'],
          
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Fee_extras = Fee_extras::find($request->id);
        $Fee_extras->title = $request->title ;
        $Fee_extras->fee_type = $request->fee_type ;
        $Fee_extras->amount = $request->amount ;
         $Fee_extras->charged = $request->charged ;
          $Fee_extras->description = $request->description ;
       
        $settings=Settings::where('group_name','=','fee_extras')->where('key_value',$request->id)->first();
        $settings->key_name= $request->title ;
        $settings->save();
        if($Fee_extras->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Fee_extras
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Fee_extras = Fee_extras::find($request->id);
          $settings=Settings::where('group_name','=','fee_extras')->where('key_value',$request->id)->first();
        $settings->key_name=NULL;
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($Fee_extras))

                {
                  if($Fee_extras->delete()){
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
