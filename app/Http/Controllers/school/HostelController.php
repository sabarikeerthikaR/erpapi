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
use App\Models\Hostel;
use App\Models\Staff;
use App\Models\Settings;

class HostelController extends Controller
{
    public function store(Request $Hostel)
    {
      $validator =  Validator::make($Hostel->all(), [
            'title' => ['required', 'string'],
            
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Hostel=Hostel::create([

        'title'  =>$Hostel->title ,
        'rooms'  =>$Hostel->rooms ,
        'janitor'  =>$Hostel->janitor ,
        'description'  =>$Hostel->description ,
        'created_om'  =>date("Y-m-d"),
         ]); 
         $settings=Settings::create([
            'group_name'=>'hostel',
            'key_name'=>$Hostel->title,
            'key_value'=>$Hostel->id,
            ]);
            $settings->save();
        if($Hostel->save()){
                  return response()->json([
                 'message'  => 'Hostel saved successfully',
                 'data'  => $Hostel 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Hostel = Hostel::find($request->id);
             if(!empty($Hostel)){
                    return response()->json([
                    'data'  => $Hostel      
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
        $Hostel = Hostel::leftjoin('staff','hostel.janitor','=','staff.employee_id')
        ->select('hostel.id','title','rooms','description',
        db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as janitor"))->get();
        return response()->json(['status' => 'Success', 'data' => $Hostel]);
    }


public function update(Request $request)

   {
    
    $Hostel = Hostel::find($request->id);
        $Hostel->title = $request->title ;
        $Hostel->rooms = $request->rooms ;
        $Hostel->janitor = $request->janitor ;
        $Hostel->description = $request->description;
        $settings=Settings::where('group_name','=','hostel')->where('key_value',$request->id)->first();
        $settings->key_name= $request->title;
        $settings->save();
        if($Hostel->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Hostel
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Hostel = Hostel::find($request->id);
        $settings=Settings::where('group_name','=','hostel')->where('key_value',$request->id)->first();
        $settings->group_name=NULL;
        $settings->key_name=NULL;
        $settings->key_value=NULL;
        $settings->save();
        if(!empty($Hostel))

                {
                  if($Hostel->delete()){
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
