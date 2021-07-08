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
use App\Models\StaffClearance;
use App\Models\Staff;

class StaffClearanceController extends Controller
{
    public function store(Request $bulkclr)
    {
       $bulk=$bulkclr->bulk;
        $errors=[];
        foreach($bulk as $g)
        {
          if ($bulkclr->staff=='') 
          {
           return response()->json(apiResponseHandler([],'The staff field is required',400), 400);
          }
          
        
        $bulkclr = new StaffClearance(array(
          'department'=>$g['department'],
          'date'=>$g['date'], 
           'clear'=>$g['clear'],  
            'charge'=>$g['charge'],  
             'comment'=>$g['comment'],   
              'confirmed_by'=>$g['confirmed_by'],  
          'staff'=>$bulkclr->staff,
         ));
          if(!$bulkclr->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'bulkclr saved successfully',
              'staff'=>$bulkclr->staff,
          'data'=>$bulk,
                  ]);
              }
              else 
              {
                  return response()->json([
                   'message'  => 'failed',
                   'errors'=>$errors
                 ]);
               }
    }
public function show(request $request)
   {
   	 $StaffClearance = StaffClearance::find($request->id);
             if(!empty($StaffClearance)){
                    return response()->json([
                    'data'  => $StaffClearance      
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
        $StaffClearance = StaffClearance::leftjoin('staff','staff_clearance.staff','=','staff.employee_id')
        ->leftjoin('setings as clear','staff_clearance.clear','=','clear.s_d')
        ->leftjoin('staff as staf','staff_clearance.confirmed_by','=','staf.employee_id')
        ->leftjoin('department','staff_clearance.department','=','department.department_id')
        ->select('date','charge','comment',db::raw("CONCAT(staff.first_name,' ',COALESCE(staff.middle_name,''),' ',staff.last_name)as staff_name")
    ,'department.name as department','staff','clear.key_name as clear','staff_clearance.id',db::raw("CONCAT(staf.first_name,' ',COALESCE(staf.middle_name,''),' ',staf.last_name)as confirmed_by"))
    ->groupBy('staff_clearance.staff')
    ->get();
        return response()->json(['status' => 'Success', 'data' => $StaffClearance]);
      }


public function update(Request $request)

   {
    
    $StaffClearance = StaffClearance::find($request->id);
        $StaffClearance->staff= $request->staff;
        $StaffClearance->department= $request->department;
        $StaffClearance->department= $request->department;
        $StaffClearance->date= $request->date;
        $StaffClearance->clear= $request->clear;
        $StaffClearance->charge= $request->charge;
        $StaffClearance->comment= $request->comment;
        $StaffClearance->confirmed_by= $request->confirmed_by;
        if($StaffClearance->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $StaffClearance
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $StaffClearance = StaffClearance::find($request->id);
        if(!empty($StaffClearance))

                {
                  if($StaffClearance->delete()){
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
    public function view(request $request)
    {
      $id=$request->staff_id;
      $staff=Staff::select('employee_id','id_number',Db::raw("CONCAT(first_name,'',COALESCE(middle_name,''),'',last_name)as staff"),'phone','address','position')->where('employee_id','=',$id)->first();
      $clearance=StaffClearance::select('department','date','clear','charge','comment','staff')->where('staff','=',$id)->get();
      if(!empty($staff))
      {
        return response()->json([
          'message'=>'success',
          'staff'=>$staff,
          'data'=>$clearance
        ]);
      }else{
        return response()->json([
          'message'=>'No data found'
        ]);
      }
    }
}
