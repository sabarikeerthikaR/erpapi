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
use App\Models\User;

class AdminController extends Controller
{
    public function Admindata(request $request)
    {
      $id=$request->id;
      $admin=User::where('user_role','=',2)->where('users.id','=',$id)->select(DB::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as fullname"),'email','phone','image','country','region','city','id')->first();
      
      if(!empty($admin))
      {
          return response()->json([
              "message"=>"success",
              "data"=>$admin
          ]);
      }else{
          return response()->json([
              "message"=>"data not found"
          ]);
      }
    }
    public function adminupdate(request $request)
    {
        $validator =  Validator::make($request->all(), [
            'email' => 'unique:users,email,'.$request->id
            
       
        ]);
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $user = User::find($request->id);
                                  $user->first_name = $request->first_name;
                                   $user->country = $request->country;
                                    $user->email = $request->email;
                                   $user->phone = $request->phone;
                                   $user->region = $request->region;
                                   $user->city = $request->city;                                
         if($user->save()){
             return response()->json([
                 'message'=>'success',
                 'data'=>$user
             ]);
         }
             else{
                 return response()->json([
                     'message'=>'data not found'
                 ]);
             }
   
    }
    public function changePassword(request $request)
    {
        $validator =  Validator::make($request->all(), [
            
            'password'  => ['required'],
            'new_password'=>['confirmed','min:8','different:password']
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $admin=User::find($request->id);
        if (Hash::check($request->password, $admin->password)) { 
            $admin->fill([
             'password' => Hash::make($request->new_password)
             ])->save();
            return response()->json([
            'message'  => ' password changed', 
                   'data'=>$admin,
             ]);


         }else {
             return response()->json([
            'message'  => 'failed'
            ]);
     }
       
    }
}
