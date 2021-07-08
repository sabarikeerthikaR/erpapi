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
use App\Models\FeeExtrass;
use Illuminate\Support\Facades\Auth;

class FeeExtrassController extends Controller
{
    public function storeFeeExtras(request $request)
    {

      $fee=$request->fee;
        $errors=[];
        foreach($fee as $g)
        {
          if ($request->student_id=='') 
          {
           return response()->json(apiResponseHandler([],'The student field is required',400), 400);
          }
                    $FeeExtrass = new FeeExtrass(array(
        
                      'amount'=>$g['amount'],
                      'select_fee'=>$g['select_fee'],  
                      'year'  =>$g['year'],
                      'term'   =>$g['term'],
                      'description'   =>$g['description'],
                      'student_id'=>$request->student_id,
                      'created_by'=>auth::user()->id
                     ));
          if(!$FeeExtrass->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'request saved successfully',
              'data'=>$fee,
              'student'=>$request->student_id
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
     $FeeExtrass = FeeExtrass::find($request->id);
             if(!empty($FeeExtrass)){
                    return response()->json([
                    'data'  => $FeeExtrass      
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
        $FeeExtrass = FeeExtrass::join('setings as fee','fee_extrass.year','=','fee.s_d')
        ->join('terms','fee_extrass.term','=','terms.term_id')
        ->join('admission','fee_extrass.student_id','=','admission.admission_id')
        ->join('fee_extras','fee_extrass.select_fee','=','fee_extras.id')
        ->select('fee_extrass.amount','fee_extrass.description','fee.key_name as year','fee_extrass.id',
          'terms.name as term','fee_extras.title as select_fee',
          db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as student_id"))
        ->get();
        return response()->json(['status' => 'Success', 'data' => $FeeExtrass]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
      'student_id' => ['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $FeeExtrass = FeeExtrass::find($request->id);
    $FeeExtrass->student_id = $request->student_id ;
        $FeeExtrass->select_fee = $request->select_fee ;
        $FeeExtrass->amount = $request->amount ;
        $FeeExtrass->year = $request->year ;
         $FeeExtrass->term = $request->term ;
          $FeeExtrass->description = $request->description ;
        if($FeeExtrass->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $FeeExtrass
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $FeeExtrass = FeeExtrass::find($request->id);
        if(!empty($FeeExtrass))

                {
                  if($FeeExtrass->delete()){
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


    public function manageFeeExtras(request $request)
    {

    }
    public function allInvoices(request $request)
    {

    }
    public function generateInvoice(request $request)
    {

    }
    public function viewInvoice(request $request)
    {

    }

}
