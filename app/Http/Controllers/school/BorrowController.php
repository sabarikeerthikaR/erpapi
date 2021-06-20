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
use App\Models\Borrow;
use App\Models\Books;
use App\Models\Admission;
use App\Models\ClassRooms;
use App\Models\Std_class;
use Illuminate\Support\Carbon;

class BorrowController extends Controller
{
   public function store(Request $Borrow)
    {
        
       
        $data=$Borrow->data;
        $errors=[];
        foreach($data as $g)
        {
          if ($Borrow->borrow_date=='') 
          {
           return response()->json(apiResponseHandler([],'The Borrow_date field is required',400), 400);
          }
          if ($Borrow->admission_id=='') 
          {
           return response()->json(apiResponseHandler([],'The admission_id field is required',400), 400);
          }
          $start = new \Carbon\Carbon($Borrow->borrow_date);
        $store = new Borrow(array(
          'remarks'   =>$g['remarks'],
          'book_id'=>$g['book_id'],
          'borrow_date'=>$Borrow->borrow_date,
          'return_date'=>$start->addDays(7),
          'admission_id'=>$Borrow->admission_id
         ));

          if(!$store->save())
          {
            $errors[]=$g;
          }
        }
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'Borrow saved successfully',
              'data'=>$data,
              'Borrow_date'=>$Borrow->Borrow_date,
               'admission_id'=>$Borrow->admission_id
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
   	 $Borrow = Borrow::find($request->b_id);
             if(!empty($Borrow)){
                    return response()->json([
                    'data'  => $Borrow      
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
        $Borrow = Borrow::leftjoin('admission','borrow.admission_id','=','admission.admission_id')
        ->leftjoin('add_stream','admission.class','=','add_stream.id')
    ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
    ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
        ->leftjoin('books','borrow.book_id','=','books.book_id')
        ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),db::raw("CONCAT(std_class.name,' ',class_stream.name)as class"),
        'books.title as book','borrow.borrow_date','return_date','borrow.status','remarks','b_id')->get();
        return response()->json(['status' => 'Success', 'data' => $Borrow]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
             
        
        'borrow_date' =>['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Borrow = Borrow::find($request->b_id);
        $Borrow->book_id= $request->book_id;
        $Borrow->admission_id= $request->admission_id;
         $Borrow->borrow_date= $request->borrow_date;
        $Borrow->remarks= $request->remarks;
        if($Borrow->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Borrow
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Borrow = Borrow::find($request->b_id);
        if(!empty($Borrow))

                {
                  if($Borrow->delete()){
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
    public function returnBook()
    {
      $book=Borrow::leftjoin('admission','borrow.admission_id','=','admission.admission_id')
      ->leftjoin('add_stream','admission.class','=','add_stream.id')
    ->leftjoin('std_class','add_stream.class','=','std_class.class_id')
    ->leftjoin('class_stream','add_stream.stream','=','class_stream.stream_id')
    //->where("return_date","<",date("Y-m-d"))
    ->where('borrow.status',NULL)
      ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
      db::raw("CONCAT(std_class.name,' ',class_stream.name)as class"),'borrow_date','remarks',db::raw('COUNT(borrow.book_id) as pending_books'),'b_id','borrow.admission_id')
      ->groupBy("borrow.admission_id")
      ->get();
      return response()->json([
        'message'=>'success',
        'data'=>$book
      ]);
    }
    public function addReturn(request $request)
    {
      $p=$request->all();
        $id=$p['admission_id'];
        DB::enableQueryLog();
       $Borrow = Borrow::where('admission_id',$id)->first();
      $Borrow->book_id= $request->book_id;
       $Borrow->actual_return= $request->actual_return;
      $Borrow->remarks= $request->remarks;
      $Borrow->status='book returned';
      if($Borrow->save()){
          return response()->json([
               'message'  => 'updated successfully',
               'data'  => $Borrow
          ]);
      }else {
          return response()->json([
               'message'  => 'failed'
               ]);
      }
    }
    public function listReturnBooks(request $request)
    {
      $books=Borrow::where('admission_id',$request->admission_id)->leftjoin('books','borrow.book_id','=','books.book_id')
      ->select('books.title as book','status','actual_return','remarks','b_id','borrow.book_id')
      ->where('status',NULL)
      ->get();
      if(!empty($books))
      {
      return response()->json([
        'message'=>'success',
        'data'=>$books
      ]);
    }
      else{
        return response()->json([
          'message'=>'no data found',
         
        ]);
      }
    }
}
