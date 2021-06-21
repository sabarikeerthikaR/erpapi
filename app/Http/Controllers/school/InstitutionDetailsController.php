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
use App\Models\InstitutionDocs;
use App\Models\InstitutionSetup;
use App\Models\Ownership;
use App\Models\ContactPerson;
use App\Models\InstitutionDetails;
use App\Models\Settings;

class InstitutionDetailsController extends Controller
{
    public function store(Request $InstitutionDetails)
    {
      $validator =  Validator::make($InstitutionDetails->all(), [
            'School_Name' => ['required'],
            'County' => ['required'],
            'Institution_Type' => ['required'],
            'Education_System' => ['required'],
            'Education_Level' => ['required'],
            
            
          ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $InstitutionDetails=InstitutionDetails::create([

        'School_Name'  =>$InstitutionDetails->School_Name,
        'Registration_Number'  =>$InstitutionDetails->Registration_Number,
        'School_Code'  =>$InstitutionDetails->School_Code,
        'Registration_Date'  =>$InstitutionDetails->Registration_Date,
        'Institution_Category'  =>$InstitutionDetails->Institution_Category,
        'Institution_Cluster'  =>$InstitutionDetails->Institution_Cluster,
        'County'  =>$InstitutionDetails->Sub_County,
        'Ward'  =>$InstitutionDetails->Ward,
        'Institution_Type'  =>$InstitutionDetails->Institution_Type,
        'Education_System'  =>$InstitutionDetails->Education_System,
        'Education_Level'  =>$InstitutionDetails->Education_Level,
        'KNEC_Code'  =>$InstitutionDetails->KNEC_Code,
        'Institution_Accommodation'  =>$InstitutionDetails->Institution_Accommodation,
        'Scholars_Gender'  =>$InstitutionDetails->Scholars_Gender,
        'Locality'  =>$InstitutionDetails->Locality,
        'KRA_PIN'  =>$InstitutionDetails->KRA_PIN,
       
         ]);
        //  $Ownership=Ownership::create([
        //     'ownership'=>'',
        //     'proprietor'    =>'',
        //     'ownership_type'          =>'',
        //     'certificate_no'         =>'',
        //     'town'        =>'',
        //     'police_station'        =>'',
        //     'health_facility'        =>'',
        //     'institution_id'  =>'',
        //      ]);
        //      $Ownership->save();
        //      $InstitutionSetup=InstitutionSetup::create([

        //         'Address'  =>'',
        //         'Email'  =>'',
        //         'Telephone'  =>'',
        //         'CellNumber'  =>'',
        //         'Fax'  =>'',
        //         'EmployeesTimeIn'  =>'',
        //         'EmployeesTimeOut'  =>'',
        //         'UseRemarks'  =>'',
        //         'AdmissionNo'  =>'',
        //         'TaxRelief'  =>'',
        //         'Website'  =>'',
        //         'SocialMediaLink'  =>'',
        //         'SchoolMotto'  =>'',
        //         'Vision'  =>'',
        //         'Mission'  =>'',
        //         'DefaultListsSize'  =>'',
        //         'DefaultMessage'  =>'',
        //         'DefaultCurrency'  =>'',
        //         'MobilePaymentInfo'  =>'',
        //         'DefaultSMSSender'  =>'',
        //         'MapLocation'  =>'',
        //         'institution_id'  =>'',
               
        //          ]);
        //          $InstitutionDocs=InstitutionDocs::create([
        //             'ownership_doc'=>'',
        //             'institution_certificate'    =>'',
        //             'incorporation_certificate'  =>'',
        //             'ministry_approval'         =>'',
        //             'title_deed'        =>'',
        //             'sub_title_deed'        =>'',
                   
        //              ]);
        //              $ContactPerson=ContactPerson::create([
      
        //                 'name'    =>'',
        //                 'phone'          =>'',
        //                 'designation'         =>'',
        //                 'email'        =>'',
                        
        //                  ]);
        if($InstitutionDetails->save()){
                  return response()->json([
                 'message'  => 'InstitutionDetails saved successfully',
                 'data'  => $InstitutionDetails 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $InstitutionDetails = InstitutionDetails::find($request->id);
             if(!empty($InstitutionDetails)){
                    return response()->json([
                    'data'  => $InstitutionDetails      
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
        $InstitutionDetails = InstitutionDetails::leftjoin('setings as ins','institution_details.Institution_Category','=','ins.s_d')
        ->leftjoin('setings as insC','institution_details.Institution_Cluster','=','insC.s_d')
        ->leftjoin('counties','institution_details.County','=','counties.id')
        ->leftjoin('sub_county','institution_details.Sub_County','=','sub_county.id')
        ->leftjoin('setings as instt','institution_details.Institution_Type','=','instt.s_d')
        ->leftjoin('setings as ED','institution_details.Education_System','=','ED.s_d')
        ->leftjoin('setings as EL','institution_details.Education_Level','=','EL.s_d')
        ->leftjoin('setings as IA','institution_details.Institution_Accommodation','=','IA.s_d')
        ->leftjoin('setings as SA','institution_details.Scholars_Gender','=','SA.s_d')
        ->leftjoin('setings as LC','institution_details.Locality','=','LC.s_d')
        ->select('School_Name','Registration_Number','School_Code','Registration_Date',
        'ins.key_name as Institution_Category','insC.key_name as Institution_Cluster',
        'sub_county.sub_county as Sub_County','Ward'
        ,'counties.name as County','instt.key_name as Institution_Type','ED.key_name as Education_System',
        'EL.key_name as Education_Level','KNEC_Code','IA.key_name as Institution_Accommodation',
        'SA.key_name as Scholars_Gender','LC.key_name as Locality','KRA_PIN','institution_details.id')->first();
     return response()->json(['status' => 'Success', 'data' => $InstitutionDetails]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'School_Name' => ['required'],
        'County' => ['required'],
        'Institution_Type' => ['required'],
        'Education_System' => ['required'],
        'Education_Level' => ['required'],
            
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $InstitutionDetails = InstitutionDetails::find($request->id);
       $InstitutionDetails->School_Name= $request->School_Name;
       $InstitutionDetails->Registration_Number= $request->Registration_Number;
       $InstitutionDetails->School_Code= $request->School_Code;
       $InstitutionDetails->Registration_Date= $request->Registration_Date;
       $InstitutionDetails->Institution_Category= $request->Institution_Category;
       $InstitutionDetails->Institution_Cluster= $request->Institution_Cluster;
       $InstitutionDetails->County= $request->County;
       $InstitutionDetails->Sub_County= $request->Sub_County;
       $InstitutionDetails->Ward= $request->Ward;
       $InstitutionDetails->Institution_Type= $request->Institution_Type;
       $InstitutionDetails->Education_System= $request->Education_System;
       $InstitutionDetails->Education_Level= $request->Education_Level;
       $InstitutionDetails->KNEC_Code= $request->KNEC_Code;
       $InstitutionDetails->Institution_Accommodation= $request->Institution_Accommodation;
       $InstitutionDetails->Scholars_Gender= $request->Scholars_Gender;
       $InstitutionDetails->Locality= $request->Locality;
       $InstitutionDetails->KRA_PIN= $request->KRA_PIN;
        if($InstitutionDetails->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $InstitutionDetails
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $InstitutionDetails = InstitutionDetails::find($request->id);
        if(!empty($InstitutionDetails))

                {
                  if($InstitutionDetails->delete()){
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
    public function institutn(Request $request)
    {
       
        $ins=InstitutionDetails::leftjoin('setings as ins','institution_details.Institution_Category','=','ins.s_d')
        ->leftjoin('setings as insC','institution_details.Institution_Cluster','=','insC.s_d')
        ->leftjoin('counties','institution_details.County','=','counties.id')
        ->leftjoin('sub_county','institution_details.Sub_County','=','sub_county.id')
        ->leftjoin('setings as instt','institution_details.Institution_Type','=','instt.s_d')
        ->leftjoin('setings as ED','institution_details.Education_System','=','ED.s_d')
        ->leftjoin('setings as EL','institution_details.Education_Level','=','EL.s_d')
        ->leftjoin('setings as IA','institution_details.Institution_Accommodation','=','IA.s_d')
        ->leftjoin('setings as SA','institution_details.Scholars_Gender','=','SA.s_d')
        ->leftjoin('setings as LC','institution_details.Locality','=','LC.s_d')
        ->select('School_Name','Registration_Number','School_Code','Registration_Date',
        'ins.key_name as Institution_Category','insC.key_name as Institution_Cluster',
        'sub_county.sub_county as Sub_County','Ward'
        ,'counties.name as County','instt.key_name as Institution_Type','ED.key_name as Education_System',
        'EL.key_name as Education_Level','KNEC_Code','IA.key_name as Institution_Accommodation',
        'SA.key_name as Scholars_Gender','LC.key_name as Locality','KRA_PIN','institution_details.id')->first();
        $owner=Ownership::leftjoin('setings as ow','ownership.ownership','=','ow.s_d')
        ->leftjoin('setings as ot','ownership.ownership_type','=','ot.s_d')
        ->select('ow.key_name as ownership','proprietor','ot.key_name as ownership_type','certificate_no','town','police_station','health_facility','institution_id')->first();
        $institutiondocs=InstitutionDocs::first();
        $contact=ContactPerson::first();
        $insset=InstitutionSetup::leftjoin('setings as US','institution_setup.UseRemarks','=','US.s_d')
        ->leftjoin('setings as DS','institution_setup.DefaultListsSize','=','DS.s_d')->select('Address',
        'Email','Telephone','CellNumber','Fax','EmployeesTimeIn','EmployeesTimeOut','US.key_name as UseRemarks',
        'AdmissionNo','TaxRelief','Website','SocialMediaLink','SchoolMotto','Vision','Mission','DS.key_name as DefaultListsSize',
        'DefaultMessage','DefaultCurrency','MobilePaymentInfo','DefaultSMSSender','MapLocation','institution_id')->first();
         if(!empty($ins)){
            return response()->json([
                 'message'  => 'updated successfully',
                 'institution_details'  => $ins,
                 'Ownership'  => $owner,
                 'InstitutionDocs'  => $institutiondocs,
                 'ContactPerson'  => $contact,
                 'contact_details'  => $insset
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
    public function insdocs(request $request)
    {
        $p=$request->all();
        $id=$p['institution_id'];
        DB::enableQueryLog();
    $InstitutionDocs = InstitutionDocs::where('institution_id',$id)->first();
    $InstitutionDocs->Institution_Logo= $request->Institution_Logo;
    $InstitutionDocs->ownership_doc= $request->ownership_doc;
       $InstitutionDocs->institution_certificate= $request->institution_certificate;
       $InstitutionDocs->incorporation_certificate= $request->incorporation_certificate;
       $InstitutionDocs->ministry_approval= $request->ministry_approval;
       $InstitutionDocs->title_deed= $request->title_deed;
      
       $InstitutionDocs->save();
       $ContactPerson = ContactPerson::where('institution_id',$id)->first();
       
       $ContactPerson->name= $request->name;
       $ContactPerson->phone= $request->phone;
       $ContactPerson->designation= $request->designation;
       $ContactPerson->email= $request->email;
      
        if($ContactPerson->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'InstitutionDocs'  => $InstitutionDocs,
                 'ContactPerson'  => $ContactPerson
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }

    }
    public function docSelect(request $request)
    {
        $instituteDocs=InstitutionDocs::where('institution_id',$request->institution_id)->first();
        $contactPerson=ContactPerson::where('institution_id',$request->institution_id)->first();
        if(!empty($instituteDocs))
        {
            return response()->json([
            'message'=>'success',
            'instituteDocs'=>$instituteDocs,
            'contactPerson'=>$contactPerson
            ]);
            
        }else
        {
            return response()->json([
            'message'=>'not found'
            ]);
        }
    }
}
