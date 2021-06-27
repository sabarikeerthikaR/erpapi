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
        $valiDationArray[]='';
        if($InstitutionDocs->ownership_doc)
        {
          $valiDationArray["ownership_doc"]='required|file';
        }
        if($InstitutionDocs->institution_certificate)
        {
          $valiDationArray["institution_certificate"]='required|file';
        }
        if($InstitutionDocs->incorporation_certificate)
        {
          $valiDationArray["incorporation_certificate"]='required|file';
        }
        if($InstitutionDocs->ministry_approval)
        {
          $valiDationArray["ministry_approval"]='required|file';
        }
        $validator =  Validator::make($InstitutionDocs->all(),$valiDationArray); 
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
         }
         $ownership_doc='';$institution_certificate='';
         if($InstitutionDocs->file('ownership_doc')){
         $ownership_doc = $InstitutionDocs->file('ownership_doc');
         $imgName = time() . '.' . pathinfo($ownership_doc->getClientOriginalName(), PATHINFO_EXTENSION);
         Storage::disk('public_uploads')->put('/ownership-doc/' . $imgName, file_get_contents($ownership_doc));
         $ownership_doc=config('app.url').'/public/uploads/ownership-doc/' . $imgName;
         }
         if($InstitutionDocs->file('institution_certificate')){
           $institution_certificate= $InstitutionDocs->file('institution_certificate');
           $cerName = time() . '.' . pathinfo($institution_certificate->getClientOriginalName(), PATHINFO_EXTENSION);
           Storage::disk('public_uploads')->put('/institution-certificate/' . $cerName, file_get_contents($institution_certificate));
           $institution_certificate=config('app.url').'/public/uploads/institution-certificate/' . $cerName;
         }
         $incorporation_certificate='';$ministry_approval='';
         if($InstitutionDocs->file('incorporation_certificate')){
         $incorporation_certificate = $InstitutionDocs->file('incorporation_certificate');
         $imgName = time() . '.' . pathinfo($incorporation_certificate->getClientOriginalName(), PATHINFO_EXTENSION);
         Storage::disk('public_uploads')->put('/incorporation-certificate/' . $imgName, file_get_contents($incorporation_certificate));
         $incorporation_certificate=config('app.url').'/public/uploads/incorporation-certificate/' . $imgName;
         }
         if($InstitutionDocs->file('ministry_approval')){
           $ministry_approval= $InstitutionDocs->file('ministry_approval');
           $cerName = time() . '.' . pathinfo($ministry_approval->getClientOriginalName(), PATHINFO_EXTENSION);
           Storage::disk('public_uploads')->put('/ministry-approval/' . $cerName, file_get_contents($ministry_approval));
           $ministry_approval=config('app.url').'/public/uploads/ministry-approval/' . $cerName;
         }
         $title_deed='';
         if($InstitutionDocs->file('title_deed')){
            $title_deed= $InstitutionDocs->file('title_deed');
            $cerName = time() . '.' . pathinfo($title_deed->getClientOriginalName(), PATHINFO_EXTENSION);
            Storage::disk('public_uploads')->put('/title-deed/' . $cerName, file_get_contents($title_deed));
            $title_deed=config('app.url').'/public/uploads/title-deed/' . $cerName;
          }
        
        $InstitutionDocs=InstitutionDocs::create([
        'ownership_doc'=>$ownership_doc,
        'institution_certificate'    =>$institution_certificate,
        'incorporation_certificate'          =>$incorporation_certificate,
        'ministry_approval'         =>$ministry_approval,
        'title_deed'        =>$title_deed,
       
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
    if($request->ownership_doc)
    {
      $valiDationArray["ownership_doc"]='required|file';
    }
    if($request->institution_certificate)
    {
      $valiDationArray["institution_certificate"]='required|file';
    }
    if($request->incorporation_certificate)
    {
      $valiDationArray["incorporation_certificate"]='required|file';
    }
    if($request->ministry_approval)
    {
      $valiDationArray["ministry_approval"]='required|file';
    }
    if($request->title_deed)
    {
      $valiDationArray["title_deed"]='required|file';
    }
    $validator =  Validator::make($request->all(),$valiDationArray); 
     if ($validator->fails()) {
         return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
     }
     $p=$request->all();
     $id=$p['institution_id'];
     DB::enableQueryLog();
 $InstitutionDocs = InstitutionDocs::where('institution_id',$id)->first();



      if($request->file('ownership_doc')){
          $ownership_doc = $request->file('ownership_doc');
          $imgName = time() . '.' . pathinfo($ownership_doc->getClientOriginalName(), PATHINFO_EXTENSION);
          Storage::disk('public_uploads')->put('/ownership-doc/' . $imgName, file_get_contents($ownership_doc));
          $ownership_doc=config('app.url').'/public/uploads/ownership-doc/' . $imgName;
          $InstitutionDocs->ownership_doc=$ownership_doc;
          }
          if($request->file('institution_certificate')){
            $institution_certificate= $request->file('institution_certificate');
            $cerName = time() . '.' . pathinfo($institution_certificate->getClientOriginalName(), PATHINFO_EXTENSION);
            Storage::disk('public_uploads')->put('/institution-certificate/' . $cerName, file_get_contents($institution_certificate));
            $institution_certificate=config('app.url').'/public/uploads/institution-certificate/' . $cerName;
            $InstitutionDocs->institution_certificate=$institution_certificate;
            }
            if($request->file('incorporation_certificate')){
              $incorporation_certificate = $request->file('incorporation_certificate');
              $imgName = time() . '.' . pathinfo($incorporation_certificate->getClientOriginalName(), PATHINFO_EXTENSION);
              Storage::disk('public_uploads')->put('/incorporation-certificate/' . $imgName, file_get_contents($incorporation_certificate));
              $incorporation_certificate=config('app.url').'/public/uploads/incorporation-certificate/' . $imgName;
              $InstitutionDocs->incorporation_certificate=$incorporation_certificate;
              }
              if($request->file('ministry_approval')){
                $ministry_approval= $request->file('ministry_approval');
                $cerName = time() . '.' . pathinfo($ministry_approval->getClientOriginalName(), PATHINFO_EXTENSION);
                Storage::disk('public_uploads')->put('/ministry-approval/' . $cerName, file_get_contents($ministry_approval));
                $ministry_approval=config('app.url').'/public/uploads/ministry-approval/' . $cerName;
                $InstitutionDocs->ministry_approval=$ministry_approval;
                }
                if($request->file('title_deed')){
                    $title_deed= $request->file('title_deed');
                    $cerName = time() . '.' . pathinfo($title_deed->getClientOriginalName(), PATHINFO_EXTENSION);
                    Storage::disk('public_uploads')->put('/ministry-approval/' . $cerName, file_get_contents($title_deed));
                    $title_deed=config('app.url').'/public/uploads/ministry-approval/' . $cerName;
                    $InstitutionDocs->title_deed=$title_deed;
                    }

  
       
    // $InstitutionDocs->ownership_doc= $ownership_doc;
    //    $InstitutionDocs->institution_certificate=$institution_certificate;
    //    $InstitutionDocs->incorporation_certificate=$incorporation_certificate;
    //    $InstitutionDocs->ministry_approval=$ministry_approval;
    //    $InstitutionDocs->title_deed=$title_deed;
      
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
