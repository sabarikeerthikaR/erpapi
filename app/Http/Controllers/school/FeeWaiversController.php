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
use App\Models\Fee_waivers;

class FeeWaiversController extends Controller
{
    public function store(Request $Fee_waivers)
    {
      $validator =  Validator::make($Fee_waivers->all(), [
      	 'date'=> ['required'],
            'student'       => ['required', 'string'],
            'amount'       => ['required', 'numeric'],
            'term'       => ['required','string'],
             'year'       => ['required', 'string'],
            'remarks'       => ['required','string'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Fee_waivers=Fee_waivers::create([

        
          'date'  =>$Fee_waivers->date,
        'student'  =>$Fee_waivers->student,
        'amount'  =>$Fee_waivers->amount,
        'term'  =>$Fee_waivers->term,
         'year'  =>$Fee_waivers->year,
        'remarks'  =>$Fee_waivers->remarks,
       
         ]);
        if($Fee_waivers->save()){
                  return response()->json([
                 'message'  => 'Fee_waivers saved successfully',
                 'data'  => $Fee_waivers 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Fee_waivers = Fee_waivers::find($request->id);
             if(!empty($Fee_waivers)){
                    return response()->json([
                    'data'  => $Fee_waivers      
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
        $Fee_waivers = Fee_waivers::all();
        return response()->json(['status' => 'Success', 'data' => $Fee_waivers]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
           'date'=> ['required'],
            'student'       => ['required', 'string'],
            'amount'       => ['required', 'numeric'],
            'term'       => ['required','string'],
             'year'       => ['required', 'string'],
            'remarks'       => ['required','string'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Fee_waivers = Fee_waivers::find($request->id);
        $Fee_waivers->date= $request->date;
        $Fee_waivers->student= $request->student;
         $Fee_waivers->amount= $request->amount;
        $Fee_waivers->term= $request->term;
         $Fee_waivers->year= $request->year;
        $Fee_waivers->remarks= $request->remarks;
       

        if($Fee_waivers->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Fee_waivers
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Fee_waivers = Fee_waivers::find($request->id );
        if(!empty($Fee_waivers))

                {
                  if($Fee_waivers->delete()){
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
