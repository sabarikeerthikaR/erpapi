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
use App\Models\Settings;

class CommonController extends Controller
{
   
   public function settings(Request $Request)
   {
   	 $settings = settings::all();
      
        return response()->json(['status' => 'Success', 'data' => $settings]);
      
   }

   public function userStatus(Request $request)
   {
   	$group_name =$request->filter;
       if($group_name && !empty($group_name)){
                    return response()->json([
                    'data'  => settings::where("group_name",$group_name)->get()     
                    ]);
                }
                else
                {
                  return response()->json([
                 'data'  => settings::get()  
                  ]);
        }
   }
}
