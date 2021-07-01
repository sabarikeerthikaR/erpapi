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
          // $class=Admission::where('admission_id',$request->student)->select('class')->first();
          // $feeAmount=Fee_structure::where('class',$class->class)->where('term',$g['term'])
          // ->select('fee_amount')->first();
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
