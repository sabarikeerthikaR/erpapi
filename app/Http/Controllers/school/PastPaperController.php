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
use App\Models\Past_paper;
use App\Models\Std_class;
use App\Models\Year;


class PastPaperController extends Controller
{
    public function store(Request $Past_paper)
    {
      $validator =  Validator::make($Past_paper->all(), [
      	    'year' =>['required'],
            'name' => ['required'],
            'upload_paper' => ['required'],
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Past_paper=Past_paper::create([
         'year'  =>$Past_paper->year,
         'name'  =>$Past_paper->name,
        'upload_paper'  =>config('app.name').$Past_paper->upload_paper,
        'folder_id'  =>$Past_paper->folder_id,
        'class'=>$Past_paper->class
 
         ]);
        if($Past_paper->save()){
                  return response()->json([
                 'message'  => 'Past_paper saved successfully',
                 'data'  => $Past_paper 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Past_paper = Past_paper::find($request->past_paper_id);
             if(!empty($Past_paper)){
                    return response()->json([
                    'data'  => $Past_paper      
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
        $Past_paper = Past_paper::join('std_class','past_papers.class','=','std_class.class_id')
        ->join('year','past_papers.year','=','year.id')->select('past_papers.name','upload_paper','folder_id',
    'std_class.name as class','year.year','past_paper_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Past_paper]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'year' =>['required'],
        'name' => ['required'],
        'upload_paper' => ['required'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Past_paper = Past_paper::find($request->past_paper_id);
        $Past_paper->year= $request->year;
        $Past_paper->name= $request->name;
        $Past_paper->upload_paper= config('app.name').$request->upload_paper;
        $Past_paper->folder_id= $request->folder_id;
        $Past_paper->class= $request->class;
        if($Past_paper->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Past_paper
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Past_paper = Past_paper::find($request->past_paper_id);
        if(!empty($Past_paper))

                {
                  if($Past_paper->delete()){
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
    public function teachershowPastPaper()
    {
        $Past_paper = Past_paper::join('std_class','past_papers.class','=','std_class.class_id')
        ->join('year','past_papers.year','=','year.id')->select('past_papers.name','upload_paper','folder_id',
    'std_class.name as class','year.year','past_paper_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Past_paper]);
    }
    public function studentshowPastPaper()
    {
        $Past_paper = Past_paper::join('std_class','past_papers.class','=','std_class.class_id')
        ->join('year','past_papers.year','=','year.id')->select('past_papers.name','upload_paper','folder_id',
    'std_class.name as class','year.year','past_paper_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Past_paper]);
    }
}
