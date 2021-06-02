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
use App\Models\Settings;
use App\Models\Std_class;
use App\Models\Staff;
use App\Models\AssignTeacher;

class AssignTeacherController extends Controller
{
    public function store(Request $request)
    {
       
        $request=AssignTeacher::create([

        'date'  =>$request->date,
        'item_name'=>$request->item_name,
        'quantity'=>$request->quantity,
        'unit_price'=>$request->unit_price,
        'total'   =>$request->quantity*$request->unit_price,
        'person_responsible'=>$request->person_responsible,
        'receipt'        =>$request->receipt,
        'description'        =>$request->description,
        
         ]);
        if($request->save()){
                  return response()->json([
                 'message'  => 'AssignTeacher saved successfully',
                 'data'  => $request 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
       public function show(request $request)
   {
   	 $request = AssignTeacher::find($request->AssignTeacher_id);
             if(!empty($request)){
                    return response()->json([
                    'data'  => $request      
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
        $request = AssignTeacher::join('staff','AssignTeacher.person_responsible','=','staff.employee_id')->join('add_item','AssignTeacher.item_name','=','add_item.item_id')->select('AssignTeacher_id','AssignTeacher.date','add_item.name as product','quantity','unit_price','total','receipt',db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as person_responsible"))->get();
        return response()->json(['status' => 'Success', 'data' => $request]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'date' => ['required'],
        'item_name' => ['required'],
        'quantity'    => ['required'],
        'unit_price'  => ['required'],
        'person_responsible'       => ['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $request = AssignTeacher::find($request->AssignTeacher_id);
        $request->date= $request->date;
        $request->item_name= $request->item_name;
                $request->quantity= $request->quantity;
        $request->unit_price= $request->unit_price;
        $request->total=  $request->quantity*$request->unit_price;
        $request->person_responsible= $request->person_responsible;
        $request->receipt= $request->receipt;
        $request->description= $request->description;
       
        if($request->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $request
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $request = AssignTeacher::find($request->AssignTeacher_id);
        if(!empty($request))

                {
                  if($request->delete()){
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
