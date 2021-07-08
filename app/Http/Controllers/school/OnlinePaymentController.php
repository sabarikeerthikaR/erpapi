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
  $feeList=Fee_payment::leftjoin('fee_structure','fee_payment.term','=','fee_structure.term')
  ->where('student',$request->student)
  ->where('fee_payment.tuition_fee','<=','fee_payment.amount')
  ->select(db::raw('fee_payment.tuition_fee - fee_payment.amount as amount'),'fee_payment.term')
  ->groupBy('fee_structure.term')  
  ->get();
   $termFee=Fee_structure::rightjoin('fee_payment','fee_structure.term','!=','fee_payment.term')
  ->where('fee_payment.student',$request->student)
 // ->where('fee_payment.term','!=','fee_structure.term')
  ->select('fee_structure.fee_amount','fee_structure.term') 
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


}
