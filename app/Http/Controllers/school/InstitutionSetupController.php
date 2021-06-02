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
use App\Models\InstitutionSetup;
use App\Models\Settings;

class InstitutionSetupController extends Controller
{
    public function store(Request $InstitutionSetup)
    {
      $validator =  Validator::make($InstitutionSetup->all(), [
            'Address' => ['required'],
            'Email' => ['required'],
            'CellNumber' => ['required'],
            'EmployeesTimeIn' => ['required'],
            'EmployeesTimeOut' => ['required'],
            'UseRemarks' => ['required'],
            'AdmissionNo' => ['required'],
            'TaxRelief' => ['required'],
            'SchoolMotto' => ['required'],
            'DefaultMessage' => ['required'],
            'DefaultCurrency' => ['required'],
 
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $InstitutionSetup=InstitutionSetup::create([

        'Address'  =>$InstitutionSetup->Address,
        'Email'  =>$InstitutionSetup->Email,
        'Telephone'  =>$InstitutionSetup->Telephone,
        'CellNumber'  =>$InstitutionSetup->CellNumber,
        'Fax'  =>$InstitutionSetup->Fax,
        'EmployeesTimeIn'  =>$InstitutionSetup->EmployeesTimeIn,
        'EmployeesTimeOut'  =>$InstitutionSetup->EmployeesTimeOut,
        'UseRemarks'  =>$InstitutionSetup->UseRemarks,
        'AdmissionNo'  =>$InstitutionSetup->AdmissionNo,
        'TaxRelief'  =>$InstitutionSetup->TaxRelief,
        'Website'  =>$InstitutionSetup->Website,
        'SocialMediaLink'  =>$InstitutionSetup->SocialMediaLink,
        'SchoolMotto'  =>$InstitutionSetup->SchoolMotto,
        'Vision'  =>$InstitutionSetup->Vision,
        'Mission'  =>$InstitutionSetup->Mission,
        'DefaultListsSize'  =>$InstitutionSetup->DefaultListsSize,
        'DefaultMessage'  =>$InstitutionSetup->DefaultMessage,
        'DefaultCurrency'  =>$InstitutionSetup->DefaultCurrency,
        'MobilePaymentInfo'  =>$InstitutionSetup->MobilePaymentInfo,
        'DefaultSMSSender'  =>$InstitutionSetup->DefaultSMSSender,
        'MapLocation'  =>$InstitutionSetup->MapLocation,
        'institution_id'  =>$InstitutionSetup->institution_id,
       
         ]);
        if($InstitutionSetup->save()){
                  return response()->json([
                 'message'  => 'InstitutionSetup saved successfully',
                 'data'  => $InstitutionSetup 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
    $p=$request->all();
    $id=$p['institution_id'];
    DB::enableQueryLog();
$InstitutionSetup = InstitutionSetup::where('institution_id',$id)->first();
             if(!empty($InstitutionSetup)){
                    return response()->json([
                    'data'  => $InstitutionSetup      
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
        $InstitutionSetup = InstitutionSetup::all();
        return response()->json(['status' => 'Success', 'data' => $InstitutionSetup]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'Address' => ['required'],
        'Email' => ['required'],
        'CellNumber' => ['required'],
        'EmployeesTimeIn' => ['required'],
        'EmployeesTimeOut' => ['required'],
        'UseRemarks' => ['required'],
        'AdmissionNo' => ['required'],
        'TaxRelief' => ['required'],
        'SchoolMotto' => ['required'],
        'DefaultMessage' => ['required'],
        'DefaultCurrency' => ['required'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $p=$request->all();
        $id=$p['institution_id'];
        DB::enableQueryLog();
    $InstitutionSetup = InstitutionSetup::where('institution_id',$id)->first();
       $InstitutionSetup->Address= $request->Address;
       $InstitutionSetup->Email= $request->Email;
       $InstitutionSetup->Telephone= $request->Telephone;
       $InstitutionSetup->CellNumber= $request->CellNumber;
       $InstitutionSetup->Fax= $request->Fax;
       $InstitutionSetup->EmployeesTimeIn= $request->EmployeesTimeIn;
       $InstitutionSetup->EmployeesTimeOut= $request->EmployeesTimeOut;
       $InstitutionSetup->UseRemarks= $request->UseRemarks;
       $InstitutionSetup->AdmissionNo= $request->AdmissionNo;
       $InstitutionSetup->TaxRelief= $request->TaxRelief;
       $InstitutionSetup->Website= $request->Website;
       $InstitutionSetup->SocialMediaLink= $request->SocialMediaLink;
       $InstitutionSetup->SchoolMotto= $request->SchoolMotto;
       $InstitutionSetup->Vision= $request->Vision;
       $InstitutionSetup->Mission= $request->Mission;
       $InstitutionSetup->DefaultListsSize= $request->DefaultListsSize;
       $InstitutionSetup->DefaultMessage= $request->DefaultMessage;
       $InstitutionSetup->DefaultCurrency= $request->DefaultCurrency;
       $InstitutionSetup->MobilePaymentInfo= $request->MobilePaymentInfo;
       $InstitutionSetup->DefaultSMSSender= $request->DefaultSMSSender;
       $InstitutionSetup->MapLocation= $request->MapLocation;

        if($InstitutionSetup->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $InstitutionSetup
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $p=$request->all();
        $id=$p['institution_id'];
        DB::enableQueryLog();
    $InstitutionSetup = InstitutionSetup::where('institution_id',$id)->first();
        if(!empty($InstitutionSetup))

                {
                  if($InstitutionSetup->delete()){
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
