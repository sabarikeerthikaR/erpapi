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
use App\Models\Activities;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{

    public function activityGet(request $request)
    {
    
        $id=auth::user()->id;
    $getdata=Activities::where('action_performer',$id )
    ->select('description','read_status')
    ->get();
    
    $post=Activities::where('action_performer',$id )
      ->where('read_status',0)
    ->select('id')
    ->get();
 
    foreach($post as $k)
    {
        $act=Activities::where('id',$k['id'])->first();

        $act->read_status=1;
     $act->save();
    }
    
    return response()->json([
        'message'=>'success',
        'data'=>$getdata
    ]);
    }
}
