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
use App\Models\Transport;
use App\Models\Admission;

class TransportController extends Controller
{
    public function addstudenToRoute(Request $route)
    {
        
        $add=$route->add;
        $errors=[];
        foreach($add as $g)
        {
          if ($route->route=='') 
          {
           return response()->json(apiResponseHandler([],'The route field is required',400), 400);
          }
         
        $route = new Transport(array(
          'stud_name'   =>$g['stud_name'],
          'status'=>$g['status'],  
          'route'=>$route->route,
          
         ));
        
        } 
        $check=$g['status'];
        if($check == true)
        {
          if(!$route->save())
          {
            $errors[]=$g;
          }
        
           if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'route saved successfully',
              'activity'=>$route->route,
          'data'=>$add,
                  ]);
              }
        }
             
             
              else 
              {
                  return response()->json([
                   'message'  => 'failed',
                   'errors'=>$errors
                 ]);
               }
         
    }
    public function liststudentroute(Request $Request)
    {
    	$class=$Request->class;
    	$year=$Request->year;
    	$Admission=Admission::join('add_stream','admission.class','=','add_stream.id')
      ->join('std_class','add_stream.class','=','std_class.class_id')
      ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
      ->select(DB::raw("CONCAT(admission.first_name,' ',admission.middle_name,' ',admission.last_name) as full_name"),
      'admission_no','std_class.name as class','class_stream.name as stream','admission_id')
      ->where('admission.class',$class)
      ->where('admission.year',$year)
      ->groupBy('admission_id')
      ->get();
    	if(!empty($Admission)){
                    return response()->json([
                    'data'  => $Admission      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found'  
                  ]);
                 }
    }
    public function listTransport(Request $Request)
    {
      $class=$Request->class;
      $year=$Request->year;
      $route=$Request->route;
      $Admission=Transport::join('admission','transport.stud_name','=','admission.admission_id')
      ->join('add_stream','admission.class','=','add_stream.id')
      ->join('std_class','add_stream.class','=','std_class.class_id')
      ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
      ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
      'std_class.name as class','class_stream.name as stream','admission_no')
      ->where('admission.class',$class)->where('admission.year',$year)
      ->where('route',$route)
      ->get();
      if(!empty($Admission)){
                    return response()->json([
                    'data'  => $Admission      
                    ]);
                }else
                {
                  return response()->json([
                 'message'  => 'No data found'  
                  ]);
                 }

    }
}
