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

class InstitutionDocsController extends Controller
{
    public function store(Request $InstitutionDocs)
    {
        
        $InstitutionDocs=InstitutionDocs::create([
        'ownership_doc'=>$InstitutionDocs->ownership_doc,
        'institution_certificate'    =>$InstitutionDocs->institution_certificate,
        'incorporation_certificate'          =>$InstitutionDocs->incorporation_certificate,
        'ministry_approval'         =>$InstitutionDocs->ministry_approval,
        'title_deed'        =>$InstitutionDocs->title_deed,
       
         ]);
        if($InstitutionDocs->save()){
                  return response()->json([
                 'message'  => 'InstitutionDocs saved successfully',
                 'data'  => $InstitutionDocs 
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
    $InstitutionDocs = InstitutionDocs::where('institution_id',$id)->first();

             if(!empty($InstitutionDocs)){
                    return response()->json([
                    'data'  => $InstitutionDocs      
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
        $InstitutionDocs = InstitutionDocs::all();
        return response()->json(['knec_code' => 'Success', 'data' => $InstitutionDocs]);
    }


public function update(Request $request)

   {
  
        $p=$request->all();
        $id=$p['institution_id'];
        DB::enableQueryLog();
    $InstitutionDocs = InstitutionDocs::where('institution_id',$id)->first();

    $InstitutionDocs->ownership_doc= $request->ownership_doc;
       $InstitutionDocs->institution_certificate=$request->institution_certificate;
       $InstitutionDocs->incorporation_certificate=$request->incorporation_certificate;
       $InstitutionDocs->ministry_approval=$request->ministry_approval;
       $InstitutionDocs->title_deed=$request->title_deed;
      
        if($InstitutionDocs->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $InstitutionDocs
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
    $InstitutionDocs = InstitutionDocs::where('institution_id',$id)->first();
       
        if(!empty($InstitutionDocs))

                {
                  if($InstitutionDocs->delete()){
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
