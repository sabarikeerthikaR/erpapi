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
use App\Models\Gradings;
use App\Models\Grade;
use App\Models\Grading_system;
use Illuminate\Support\Facades\Auth;

class GradingsController extends Controller
{
    public function store(Request $Gradings)
    {
        
        $grade=$Gradings->grade;
        $errors=[];
        foreach($grade as $g)
        {
         
        $Gradings = new Gradings(array(
          'grade'   =>$g['grade'],
          'min_mark'=>$g['min_mark'],
          'max_mark'=>$g['max_mark'],  
          'remark'  =>$g['remark'],
          'grading_system_id'=>$Gradings->grading_system_id,
          'created_by'=>auth::user()->id,
          'created_on'=>date('Y-d-m'),
         ));
          if(!$Gradings->save())
          {
            $errors[]=$g;
          }
        } 
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'Gradings saved successfully',
              'grading_system_id'=>$Gradings->grading_system_id,
              'created_by'=>'admin',
              'created_on'=>date('Y-d-m'),
              'data'=> $grade
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

    $Gradings = Gradings::find($request->grading_id);
     


             if(!empty($Gradings)){
                    return response()->json([
                    'data'  => $Gradings      
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
        $Gradings = Gradings::join('grading_system','gradings.grading_system_id','grading_system.grading_systm_id')
        ->select('grading_system.title as grading_system','grading_id','gradings.created_on','gradings.created_by','grading_systm_id','grading_system.description')
        ->groupBy('grading_systm_id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Gradings]);
    }
    public function moveTOTrash(request $request)
    {

      $Gradings = Gradings::where('grading_system_id',$request->grading_systm_id)->get();
      if(!empty($Gradings))

              {
                if($Gradings->each->delete()){
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

    public function gradeAndRemark()
    {
      $grade = Grade::select('title as grade','remarks','gradings_id')->get();
      return response()->json([
        'message'=>'success',
        'data'=>$grade
      ]);
    }

    public function gradeView(request $request)
    {
      $gradingsystem= Gradings::join('grading_system','gradings.grading_system_id','=','grading_system.grading_systm_id')
      ->where('gradings.grading_system_id',$request->grading_system_id)->select('title')->first();
      $grade = Gradings::join('grade','gradings.grade','=','grade.gradings_id')
      ->select('grade.title as grade','min_mark','max_mark','grade.remarks','grading_id','grading_system_id')->get();
      return response()->json([
        'message'=>'success',
        'gradingsystm'=>$gradingsystem,
        'data'=>$grade
      ]);
    }

    public function viewEdit(request $request)
    {
      $Gradings = Gradings::find($request->grading_id);
       
        $Gradings->min_mark = $request->min_mark ;
         $Gradings->max_mark = $request->max_mark ;

        if($Gradings->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Gradings
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }


public function update(Request $request)

   {
    
    $Gradings = Gradings::find($request->grading_id);
        $Gradings->grade = $request->grade ;
        $Gradings->min_mark = $request->min_mark ;
         $Gradings->max_mark = $request->max_mark ;
        $Gradings->remark = $request->remark ;
         $Gradings->grading_system_id = $request->grading_system_id ;
        if($Gradings->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Gradings
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Gradings = Gradings::find($request->grading_id);
        if(!empty($Gradings))

                {
                  if($Gradings->delete()){
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
