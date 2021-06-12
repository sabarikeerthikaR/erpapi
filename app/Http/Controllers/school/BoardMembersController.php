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
use App\Models\Board_members;
use App\Models\SettingsPositions;

class BoardMembersController extends Controller
{
    public function store(Request $Board_members)
    {
      $valiDationArray =[
            'title' => ['required'],
            'first_name' => ['required'],
            'other_name'    => ['required'],
            'gender'  => ['required'],
            'phone'   => ['required', 'numeric', 'digits:10'],
            'position'       => ['required'],
            'date_joined' => ['required'],
          ]; 
          if($Board_members->passport_photo)
        {
          $valiDationArray["passport_photo"]='required|file';
        }
        if($Board_members->copy_id)
        {
          $valiDationArray["copy_id"]='required|file';
        }
        $validator =  Validator::make($Board_members->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $passport_photo='';$copy_id='';
         if($Board_members->file('passport_photo')){
         $passport_photo = $Board_members->file('passport_photo');
         $imgName = time() . '.' . pathinfo($passport_photo->getClientOriginalName(), PATHINFO_EXTENSION);
         Storage::disk('public_uploads')->put('/staff-photo/' . $imgName, file_get_contents($passport_photo));
         $passport_photo=config('app.url').'/public/uploads/staff-photo/' . $imgName;
         }
         
         if($Board_members->file('copy_id')){
         $copy_id = $Board_members->file('copy_id');
         $imgName = time() . '.' . pathinfo($copy_id->getClientOriginalName(), PATHINFO_EXTENSION);
         Storage::disk('public_uploads')->put('/staff-national-id/' . $imgName, file_get_contents($copy_id));
         $copy_id=config('app.url').'/public/uploads/staff-national-id/' . $imgName;
         } 

       
      
        $Board_members=Board_members::create([

        'title'  =>$Board_members->title,
        'first_name'          =>$Board_members->first_name,
        'other_name'         =>$Board_members->other_name,
        'gender'        =>$Board_members->gender,
        'phone'        =>$Board_members->phone,
        'email'        =>$Board_members->email,
        'position'        =>$Board_members->position,
        'date_joined'        =>$Board_members->date_joined,
        'work_place'        =>$Board_members->work_place,
        'profile_details'        =>$Board_members->profile_details,
        'passport_photo'        =>$passport_photo,
        'copy_id'        =>$copy_id,
         ]);
        if($Board_members->save()){
                  return response()->json([
                 'message'  => 'Board_members saved successfully',
                 'data'  => $Board_members 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Board_members = Board_members::find($request->board_mem_id);
             if(!empty($Board_members)){
                    return response()->json([
                    'data'  => $Board_members      
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
        $Board_members = Board_members::where('disable','=',null)
        ->leftjoin('setings as gen','board_members.gender','=','gen.s_d')
        ->leftjoin('setting_postition','board_members.position','=','setting_postition.id')
        ->select('board_mem_id','title','first_name','other_name','gen.key_name as gender',
        'phone','email','setting_postition.name as position','date_joined','work_place',
        'profile_details','passport_photo','copy_id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Board_members]);
    }
    public function profile(request $request)
    {
        $Board_members = Board_members::leftjoin('setings as gen','board_members.gender','=','gen.s_d')
        ->leftjoin('setting_postition','board_members.position','=','setting_postition.id')
        ->where('board_mem_id',$request->board_mem_id)
        ->select('board_mem_id','title','first_name','other_name','gen.key_name as gender',
        'phone','email','setting_postition.name as position','date_joined','work_place',
        'profile_details','passport_photo','copy_id')
        ->first();
        return response()->json(['status' => 'Success', 'data' => $Board_members]);
    }


public function update(Request $request)

   {
    $valiDationArray =  [
        'title' => ['required'],
        'first_name' => ['required'],
        'other_name'    => ['required'],
        'gender'  => ['required'],
        'phone'   => ['required', 'numeric', 'digits:10'],
        'position'       => ['required'],
        'date_joined' => ['required'],
        ]; 
        if($request->passport_photo)
        {
          $valiDationArray["passport_photo"]='required|file';
        }
        if($request->copy_id)
        {
          $valiDationArray["copy_id"]='required|file';
        }
       
        $Board_members = Board_members::find($request->board_mem_id);
    
    if($request->file('passport_photo')){
        $passport_photo = $request->file('passport_photo');
        $imgName = time() . '.' . pathinfo($passport_photo->getClientOriginalName(), PATHINFO_EXTENSION);
        Storage::disk('public_uploads')->put('/staff-photo/' . $imgName, file_get_contents($passport_photo));
        $passport_photo=config('app.url').'/public/uploads/staff-photo/' . $imgName;
        $Board_members->passport_photo=$passport_photo;
        }

        if($request->file('copy_id')){
            $copy_id = $request->file('copy_id');
            $imgName = time() . '.' . pathinfo($copy_id->getClientOriginalName(), PATHINFO_EXTENSION);
            Storage::disk('public_uploads')->put('/staff-national-id/' . $imgName, file_get_contents($copy_id));
            $copy_id=config('app.url').'/public/uploads/staff-national-id/' . $imgName;
            $Board_members->copy_id=$copy_id;
            }

       
 
        $Board_members->title= $request->title;
        $Board_members->first_name= $request->first_name;
                $Board_members->other_name= $request->other_name;
        $Board_members->gender= $request->gender;
        $Board_members->phone= $request->phone;
        $Board_members->email= $request->email;
        $Board_members->position= $request->position;
        $Board_members->date_joined= $request->date_joined;
        $Board_members->work_place= $request->work_place;
        $Board_members->profile_details= $request->profile_details;
        // $Board_members->passport_photo= $passport_photo;
        // $Board_members->copy_id= $copy_id;
        if($Board_members->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Board_members
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Board_members = Board_members::find($request->board_mem_id);
        if(!empty($Board_members))

                {
                  if($Board_members->delete()){
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
    public function disable(request $request)
    {
       
        $Board_members = Board_members::find($request->board_mem_id);
        $Board_members->disable=1;
        if($Board_members->save()){
            return response()->json([
                 'message'  => 'disabled successfully',
                
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
    public function enable(request $request)
    {
       
        $Board_members = Board_members::find($request->board_mem_id);
        $Board_members->disable=NULL;
        if($Board_members->save()){
            return response()->json([
                 'message'  => 'enabled successfully',
                
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
    public function inactive()
    {
        $Board_members = Board_members::where('disable','=',1)
        ->leftjoin('setings as gen','board_members.gender','=','gen.s_d')
        ->leftjoin('setting_postition','board_members.position','=','setting_postition.id')
        ->select('board_mem_id','title','first_name','other_name','gen.key_name as gender',
        'phone','email','setting_postition.name as position','date_joined','work_place',
        'profile_details','passport_photo','copy_id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Board_members]);
    }
}
