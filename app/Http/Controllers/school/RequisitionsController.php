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
use App\Models\Requisitions;
use Illuminate\Support\Facades\Auth;

class RequisitionsController extends Controller
{
    public function store(Request $Request)
    {
        
        $data=$Request->data;
        $errors=[];
        $Requisitions_data =[];
        foreach($data as $g)
        {

        $Requisitions[]= array(
         'sub_total'  =>$g['qty']*$g['unit_price'],
        
        );
       
        $Requisitions_data=new Requisitions( array(
          'item'   =>$g['item'],
          'qty'=>$g['qty'],
          'unit_price'=>$g['unit_price'],  
          'sub_total'  =>$g['qty']*$g['unit_price'],
          "total"=> array_sum(array_column($Requisitions,'sub_total')),
          'created_by'=>auth::user()->id
          
         ));
        
          
      if(!$Requisitions_data->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'Requisitions_data saved successfully',
              'data'=>$Requisitions_data,
              //'student'=>$Fee_payment->student
                  ]);
              }
              else 
              {
                  return response()->json([
                   'message'  => 'failed',
                   'errors'=>$errors
                 ]);
               }
    }
public function show(request $request)
   {

    $Requisitions = Requisitions::find($request->id);

             if(!empty($Requisitions)){
                    return response()->json([
                    'data'  => $Requisitions      
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
        $Requisitions = Requisitions::all();
        return response()->json(['status' => 'Success', 'data' => $Requisitions]);
    }


public function update(Request $request)

   {
    
    $Requisitions = Requisitions::find($request->id);
        $Requisitions->date = $request->date ;
        $Requisitions->title = $request->title ;
         $Requisitions->category = $request->category ;
        $Requisitions->amount = $request->amount ;
         $Requisitions->person_responsible = $request->person_responsible ;
         $Requisitions->receipt = $request->receipt ;
         $Requisitions->description = $request->description ;
        if($Requisitions->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Requisitions
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Requisitions = Requisitions::find($request->id);
        if(!empty($Requisitions))

                {
                  if($Requisitions->delete()){
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
