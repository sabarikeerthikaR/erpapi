<?php

namespace App\Http\Controllers\school;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\User_group;
use App\Exceptions;
use Whoops\Handler;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;


class AuthController extends Controller
{
     public function register (Request $request)
    {
         $validator =  Validator::make($request->all(), [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_role' => ['required', 'string'],
            'phone' => ['required', 'digits:10'],
            
           
        ]);
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }

        $objUser = User::create([   'user_role' => $request->user_role,
                                    'first_name' => $request->first_name,
                                    'last_name' => $request->last_name,
                                    'email' => $request->email,
                                    'phone' => $request->phone,
                                    'password' => Hash::make($request['password']),
                                    'image' => $request->image,
                                    'country' => $request->country,
                                    'region' => $request->region,
                                    'city' => $request->city,
                                ]);

        $objUser->save();
        return response()->json(apiResponseHandler($objUser,'Registerd successfully' ));
    }

    public function update (Request $request)
    {
         $validator =  Validator::make($request->all(), [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'string', 'email','unique:users,email,'.$request->email],
            'password'=>['string','min:8', 'confirmed']
            
            
           
        ]);
         if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $user = User::find($request->id);
                                   $user->user_role= $request->user_role;
                                  $user->first_name = $request->first_name;
                                   $user->last_name = $request->last_name;
                                    $user->email = $request->email;
                                   $user->phone = $request->phone;
                                   $user->password =Hash::make($request['password']);
                              

        $user->save();
        return response()->json(apiResponseHandler($user,'updated successfully' ,200),200);
    }

public function Login(Request $request)
    {

    

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
             return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }

        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (Auth::attempt($credentials)) {

           
              $check = User::where('email', '=', $request->input('email'))
                ->first(); 
            $admin = User::where(['email' => $request->input('email')])->first();

            $token = $admin->createToken('myApp')->accessToken;
            $name=$admin->first_name.' '.$admin->middle_name.' '.$admin->last_name;

            return response()->json(apiResponseHandler(['token' => $token, 'user_role' => $admin->user_role, 'admission_id' => $admin->admission_id, 'staff_id' => $admin->staff_id, 'name' =>$name,'id'=>$admin->id ], 'success'));
       } else {
        
            return response()->json(apiResponseHandler([], 'Wrong Credentials', 400), 400);
        }
    }
   
   public function logout()
    {
        Auth::logout();
        return response()->json(['message'=>'Successfully logged out']);
    }


   public function forgotPassword(request $request)
 {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        } 
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json(apiResponseHandler([], 'We can\'t find a user with that e-mail address.', 400), 400);
        $token=randomFunctionNumber(6);
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $token
            ]
        );
        if ($user && $passwordReset)
        {
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
             
        return response()->json(apiResponseHandler([],'We have e-mailed your  password reset link!',200),200);
      }
      else
      {
        return response()->json(apiResponseHandler([], 'We can\'t find a user with that e-mail address.', 400), 400);
      }
    
 }
   
    public function reset(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed|min:8',
            'token' => 'required|string',
        ]);
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $passwordReset = PasswordReset::where([
            ['token', $request->input('token')],
            ['email', $request->input('email')],
        ])->first();

        if (!$passwordReset)
            return response()->json(apiResponseHandler([], 'This password reset token is invalid.', 400), 400);

        $user = User::where('email',$request->email)->first();
        if (!$user)
            return response()->json(apiResponseHandler([], 'We can\'t find a user with that e-mail address.', 400), 400);
        
       $user->password = Hash::make($request['password']); 
       $user->save();
       $passwordReset->delete();
       
        return response()->json([
            'message' => 'Password succssfully changed',
        ]);

    }
       public function show()
    {
            $users = DB::table('users')->leftjoin('user_group','users.user_role','=','user_group.group_id')
            ->leftjoin('oauth_access_tokens','users.id','=','oauth_access_tokens.user_id')
            ->select(DB::raw("CONCAT(users.first_name,' ',COALESCE(users.middle_name,''),' ',users.last_name) as full_name"),
            'email','status','user_group.name','phone','oauth_access_tokens.created_at','users.id')
            ->groupBy('users.id')
            ->get();
          
            if(!empty($users)) 
            {
            return response()->json(['status' => 'Success', 'data' => $users]);
            }
            else{
                return response()->json([
                    'message'=>'No data found'
                ]);
            }
    }
    public function selectuser(request $request)
    {
       $user = User::find($request->id);
       if(!empty($user)) 
       {
       return response()->json(['status' => 'Success', 'data' => $user]);
       }
       else{
           return response()->json([
               'message'=>'No data found'
           ]);
           }
           
    }

     public function destroy(request $request)
    {
       $user = User::find($request->id);
             if(!empty($user)){
                    return response()->json([
                    'data'  => $user      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found in this id'  
                  ]);
                 }
    }

    public function profile(request $request)
    {
       
        $id=$request->id;
        $role=$request->user_role;
        $find= DB::table('users')
        ->where('id','=',$id)->where('user_role','=',$role)
        ->where(function($query) use ($role,$id) {
        
        
              // admin
              if($role==2)
              { 
                 $admin= DB::table('users')->where('id',$id )->first();
                 return response()->json(apiResponseHandler(['user' => 'Admin',
                                                         'profile' => $admin,  
                                                         ], 
                                                        'success',200),200);
              }
              // student/parent
              else if($role==3)
              {
                $staff= DB::table('staff')->where('employee_id',$id)->first();
               return response()->json(apiResponseHandler(['user' => 'Staff',
                                                         'profile' => $staff,  
                                                         ], 
                                                        'success',200),200);

              }
              // staff
              else if($role==4)
              {
                 $student= DB::table('users')->join('admission', 'admission.admission_id', '=','users.admission_id')->where('admission_id','users.admission_id')->first();

             return response()->json(apiResponseHandler([ 'user' => 'Student',
                                                           'profile' => $student,  
                                                         ], 
                                                        'success',200),200);
              }
       })->get();
             
              
          
           //else{
               return response()->json(apiResponseHandler(['data' =>$find,  
                                                         ], 
                                                        'success',200),200);
          
    }


public function notification(request $request)
 {

       //activities('21','21','update','action','to student');
       //notification('21','21','update','action','to student');
    email('keerthi@southsoft.co.in');
       
 // }->join('first_parent', 'users.admission_id', '=', 'first_parent.admission_id')
 //            ->join('second_parent', 'users.admission_id', '=', 'second_parent.admission_id')
 //            ->join('emergency_contact', 'users.admission_id', '=', 'emergency_contact.admission_id')->

 }
}
 
