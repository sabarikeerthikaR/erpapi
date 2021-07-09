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
use App\Models\Fee_payment;
use App\Models\Terms;
use App\Models\Admission;
use App\Models\Fee_structure;
use App\Models\InstitutionDetails;
use Illuminate\Support\Facades\Auth;

class OnlinePaymentController extends Controller
{

public function OnlinePaymentTermFeeList(request $request)

{
  $feeList=Admission::where('admission_id',$request->student)
  ->select('class')
  ->first();
   $termFee=Fee_payment::where('student',$request->student)
  ->leftjoin('fee_structure','fee_payment.class','=','fee_payment.class')
  ->select('fee_structure.fee_amount','fee_structure.term',
            db::raw('sum(amount)as paid'),db::raw('(fee_amount - amount)as payable')) 
  ->groupBy('fee_structure.term')   
  ->get();


   if(!empty($feeList))
      {
        return response()->json([
          'message'=>'success',
          'data'=>$feeList,
          'term'=>$termFee
        ]);
      }else{
        return response()->json([
          'message'=>'No data found'
        ]);
      }
}   
public function OnlinePaymentpost(request $request)

{
 
}   


}
