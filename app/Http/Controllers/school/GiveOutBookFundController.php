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
use App\Models\Give_out_book_fund;

class GiveOutBookFundController extends Controller
{
    public function store(Request $request)
    {
        $data=$request->data;
        $errors=[];
        foreach($data as $g)
        {
          if ($request->borrow_date=='') 
          {
           return response()->json(apiResponseHandler([],'The borrow date field is required',400), 400);
          }
         
         
        $store = new Give_out_book_fund(array(
          'remarks'   =>$g['remarks'],
          'book'=>$g['book'],
          'borrow_date'=>$request->borrow_date,
          'student'=>$request->student,
          'status'=>'Not returned'
         ));
          if(!$store->save())
          {
            $errors[]=$g;
          }
        }
             
              if(count($errors)==0)
              {
              return response()->json([
              'message'  => 'request saved successfully',
              'data'=>$data,
              'request_date'=>$request->request_date,
               'admission_id'=>$request->student
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
   	 $Give_out_book_fund = Give_out_book_fund::find($request->give_out_id);
             if(!empty($Give_out_book_fund)){
                    return response()->json([
                    'data'  => $Give_out_book_fund      
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
      $Borrow = Give_out_book_fund::join('admission','give_out_book_fund.student','=','admission.admission_id')
      ->join('std_class','admission.class','=','std_class.class_id')
      ->join('book_for_fund','give_out_book_fund.book','=','book_for_fund.book_for_fund_id')
      ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),'std_class.name as class',
      'book_for_fund.title as book','give_out_book_fund.borrow_date','give_out_book_fund.status','remark','give_out_id')
      ->groupBy('give_out_id')
      ->get();
      return response()->json(['status' => 'Success', 'data' => $Borrow]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
          'borrow_date' => ['required'],
           
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Give_out_book_fund = Give_out_book_fund::find($request->give_out_id);
        $Give_out_book_fund->borrow_date = $request->borrow_date ;
        $Give_out_book_fund->student= $request->student;
                $Give_out_book_fund->book= $request->book;
                 $Give_out_book_fund->remark= $request->remark;
        if($Give_out_book_fund->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Give_out_book_fund
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Give_out_book_fund = Give_out_book_fund::find($request->give_out_id);
        if(!empty($Give_out_book_fund))

                {
                  if($Give_out_book_fund->delete()){
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
    public function returnBookforfund()
    {
      $book=Give_out_book_fund::join('admission','give_out_book_fund.student','=','admission.admission_id')
    ->join('std_class','admission.class','=','std_class.class_id')
    //->where("return_date","<",date("Y-m-d"))
    ->where('give_out_book_fund.status','Not returned')
      ->select(db::raw("CONCAT(first_name,' ',middle_name,' ',last_name)as student"),
      'std_class.name as class','borrow_date','remark',db::raw('COUNT(give_out_book_fund.give_out_id) as pending_books'),'give_out_id','give_out_book_fund.student')
      ->groupBy("give_out_book_fund.student")
      ->get();
      return response()->json([
        'message'=>'success',
        'data'=>$book
      ]);
    }
    public function addReturnBookforfund(request $request)
    {
      $p=$request->all();
        $id=$p['student'];
        DB::enableQueryLog();
       $Borrow = Give_out_book_fund::where('student',$id)->first();
      $Borrow->book= $request->book;
       $Borrow->return_date= $request->return_date;
      $Borrow->remark= $request->remark;
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
    public function listReturnBookforfund(request $request)
    {
      $books=Give_out_book_fund::where('student',$request->student)->leftjoin('book_for_fund','give_out_book_fund.book','=','book_for_fund.book_for_fund_id')
      ->select('book_for_fund.title as book','status','return_date','remark','give_out_id','give_out_book_fund.book')
      ->where('status','Not returned')
      ->get();
      return response()->json([
        'message'=>'success',
        'data'=>$books
      ]);
    }
}
