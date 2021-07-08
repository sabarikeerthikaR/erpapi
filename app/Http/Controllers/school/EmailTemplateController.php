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
use App\Models\Email_template;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class EmailTemplateController extends Controller
{
    public function store(Request $Email_template)
    {
     
        $Email_template=Email_template::create([

        'title'  =>$Email_template->title ,
        'slug'  =>$Email_template->slug ,
        'description'  =>$Email_template->description ,
        'email_body'  =>$Email_template->email_body ,
        'status'  =>$Email_template->status ,
        'created_by'  =>auth::user()->id ,
        
         ]);
       
        if($Email_template->save()){
                  return response()->json([
                 'message'  => 'Email_template saved successfully',
                 'data'  => $Email_template 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Email_template = Email_template::find($request->id);
             if(!empty($Email_template)){
                    return response()->json([
                    'data'  => $Email_template      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
   }
   public function index(request $request)
    {
        $Email_template = Email_template::leftjoin('users','email_template.created_by','=','users.id')
        ->select(db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as created_by"),
           'title','slug','description','email_body','email_template.status','email_template.id')
        ->first();
        return response()->json(['status' => 'Success', 'data' => $Email_template]);
    }


public function update(Request $request)

   {
   
    $Email_template = Email_template::find($request->id);
        $Email_template->title = $request->title ;
        $Email_template->slug = $request->slug ;
        $Email_template->description = $request->description ;
        $Email_template->email_body = $request->email_body ;
        $Email_template->status = $request->status ;
        if($Email_template->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Email_template
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Email_template = Email_template::find($request->id);
        if(!empty($Email_template))

                {
                  if($Email_template->delete()){
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
