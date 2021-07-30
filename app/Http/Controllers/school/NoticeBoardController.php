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
use App\Models\Notice_board;
use Illuminate\Support\Facades\Auth;
use App\Helper;

class NoticeBoardController extends Controller
{
    public function store(Request $Notice_board)
    {
      $validator =  Validator::make($Notice_board->all(), [
            'date' => ['required'],
            'title' => ['required'],
            
            
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Notice_board=Notice_board::create([

        'date'  =>$Notice_board->date ,
        'title'  =>$Notice_board->title ,
        'description'  =>$Notice_board->description ,

       
         ]);
       
        
        if($Notice_board->save()){
                  return response()->json([
                 'message'  => 'Notice_board saved successfully',
                 'data'  => $Notice_board 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
     $Notice_board = Notice_board::find($request->id);
             if(!empty($Notice_board)){
                    return response()->json([
                    'data'  => $Notice_board      
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
        $Notice_board = Notice_board::
        orderBy('id','desc')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Notice_board]);
    }
    public function teachernoticeBoard(request $request)
    {
        $Notice_board = Notice_board::orderBy('id','desc')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Notice_board]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
         'date' => ['required'],
            'title' => ['required'],
            
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Notice_board = Notice_board::find($request->id);
        $Notice_board->date = $request->date ;
        $Notice_board->title = $request->title ;
        $Notice_board->description = $request->description;
       
        if($Notice_board->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Notice_board
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Notice_board = Notice_board::find($request->id);
        if(!empty($Notice_board))

                {
                  if($Notice_board->delete()){
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
