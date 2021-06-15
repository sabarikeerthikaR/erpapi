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
      $valiDationArray = [
      	    'year' =>['required'],
            'name' => ['required'],
            'class' => ['required'],
          ]; 
          if($Past_paper->upload_paper)
        {
          $valiDationArray["upload_paper"]='required|file';
        }
        $validator =  Validator::make($Past_paper->all(),$valiDationArray); 
        if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(), 400), 400);
        }
        $upload_paper='';
        if($Past_paper->file('upload_paper')){
        $upload_paper = $Past_paper->file('upload_paper');
        $imgName = time() . '.' . pathinfo($upload_paper->getClientOriginalName(), PATHINFO_EXTENSION);
        Storage::disk('public_uploads')->put('/upload_paper/' . $imgName, file_get_contents($upload_paper));
        $upload_paper=config('app.url').'/public/uploads/upload_paper/' . $imgName;
        }
        $Past_paper=Past_paper::create([
         'year'  =>$Past_paper->year,
         'name'  =>$Past_paper->name,
        'upload_paper'  =>$upload_paper,
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
    $valiDationArray = [
        'year' =>['required'],
        'name' => ['required'],
        'class' => ['required'],
        ]; 
        if($request->upload_paper)
        {
          $valiDationArray["upload_paper"]='required|file';
        }

    $Past_paper = Past_paper::find($request->past_paper_id);
    if($request->file('upload_paper')){
        $upload_paper = $request->file('upload_paper');
        $imgName = time() . '.' . pathinfo($upload_paper->getClientOriginalName(), PATHINFO_EXTENSION);
        Storage::disk('public_uploads')->put('/upload_paper/' . $imgName, file_get_contents($upload_paper));
        $upload_paper=config('app.url').'/public/uploads/upload_paper/' . $imgName;
        $Past_paper->upload_paper=$upload_paper;
        }
        $Past_paper->year= $request->year;
        $Past_paper->name= $request->name;
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
        $Past_paper = Past_paper::join('add_stream','past_papers.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->join('setings','past_papers.year','=','setings.s_d')->select('past_papers.name','upload_paper','folder_id',
    'std_class.name as class','class_stream.name as stream','setings.key_name as year','past_paper_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Past_paper]);
    }
    public function studentshowPastPaper()
    {
        $Past_paper = Past_paper::join('add_stream','past_papers.class','=','add_stream.id')
        ->join('std_class','add_stream.class','=','std_class.class_id')
        ->join('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->join('setings','past_papers.year','=','setings.s_d')->select('past_papers.name','upload_paper','folder_id',
    'std_class.name as class','class_stream.name as stream','setings.key_name as year','past_paper_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Past_paper]);
    }
}
