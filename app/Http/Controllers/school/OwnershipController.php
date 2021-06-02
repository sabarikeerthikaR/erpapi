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
use App\Models\Ownership;

class OwnershipController extends Controller
{
    public function store(Request $Ownership)
    {
    	
        $Ownership=Ownership::create([
        'ownership'=>$Ownership->ownership,
        'proprietor'    =>$Ownership->proprietor,
        'ownership_type'          =>$Ownership->ownership_type,
        'certificate_no'         =>$Ownership->certificate_no,
        'town'        =>$Ownership->town,
        'police_station'        =>$Ownership->police_station,
        'health_facility'        =>$Ownership->health_facility,
        
         ]);
        if($Ownership->save()){
                  return response()->json([
                 'message'  => 'Ownership saved successfully',
                 'data'  => $Ownership 
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
    $Ownership = Ownership::where('institution_id',$id)->first();
             if(!empty($Ownership)){
                    return response()->json([
                    'data'  => $Ownership      
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
        $Ownership = Ownership::all();
        return response()->json(['knec_code' => 'Success', 'data' => $Ownership]);
    }


public function update(Request $request)

   {
    $p=$request->all();
    $id=$p['institution_id'];
    DB::enableQueryLog();
$Ownership = Ownership::where('institution_id',$id)->first();

        $Ownership->ownership= $request->ownership;
       $Ownership->proprietor= $request->proprietor;
       $Ownership->ownership_type= $request->ownership_type;
       $Ownership->certificate_no= $request->certificate_no;
       $Ownership->town= $request->town;
       $Ownership->police_station= $request->police_station;
       $Ownership->health_facility= $request->health_facility;
       
        if($Ownership->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Ownership
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
    $Ownership = Ownership::where('institution_id',$id)->first();
        if(!empty($Ownership))

                {
                  if($Ownership->delete()){
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
