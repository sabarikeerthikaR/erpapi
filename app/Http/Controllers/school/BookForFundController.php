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
use App\Models\Book_for_fund;
use App\Models\Settings;

class BookForFundController extends Controller
{
    public function store(Request $Book_for_fund)
    {
      $validator =  Validator::make($Book_for_fund->all(), [
            'category' => ['required'],
            'pages'     => ['required'],
            'title'    => ['required'],
            
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Book_for_fund=Book_for_fund::create([

        'category'  =>$Book_for_fund->category ,
        'pages' =>$Book_for_fund->pages,
        'author'  =>$Book_for_fund->author,
        'edition'  =>$Book_for_fund->edition,
        'title'  =>$Book_for_fund->title,   
        'description'  =>$Book_for_fund->description,
        'date'  =>date('d-m-Y'),
         ]);
         $settings=Settings::create([
            'group_name'=>'Book_for_fund',
            'key_name'=>$Book_for_fund->title,
            'key_value'=>$Book_for_fund->book_for_fund_id,
            ]);
            $settings->save();
        if($Book_for_fund->save()){
                  return response()->json([
                 'message'  => 'Book_for_fund saved successfully',
                 'data'  => $Book_for_fund 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Book_for_fund = Book_for_fund::find($request->book_for_fund_id);
             if(!empty($Book_for_fund)){
                    return response()->json([
                    'data'  => $Book_for_fund      
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
        $Book_for_fund = Book_for_fund::
        leftjoin('books_category','book_for_fund.category','=','books_category.book_category_id')
        ->leftjoin('give_out_book_fund','book_for_fund.book_for_fund_id','=','give_out_book_fund.give_out_id')
        ->select('title','author','books_category.name','edition','quantity',
        DB::raw("COUNT('give_out_book_fund.book'='book_for_fund.book_for_fund_id')as borrowed"),
        db::raw('quantity - give_out_book_fund.book as remaining_Book_for_fund'),'book_for_fund.book_for_fund_id')
        ->groupBy('book_for_fund_id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Book_for_fund]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'category' => ['required'],
        'pages'     => ['required'],
        'title'    => ['required'],
        ]); 
         if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Book_for_fund = Book_for_fund::find($request->book_for_fund_id);
        $Book_for_fund->category = $request->category ;
        $Book_for_fund->pages= $request->pages;
                $Book_for_fund->author= $request->author;
                 $Book_for_fund->edition= $request->edition;
                 $Book_for_fund->title= $request->title;
                 $Book_for_fund->description= $request->description;
                 $settings=Settings::where('group_name','=','Book_for_fund')->where('key_value',$request->book_for_fund_id)->first();
                 $settings->key_name= $request->title;
                 $settings->save();
        if($Book_for_fund->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Book_for_fund
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Book_for_fund = Book_for_fund::find($request->book_for_fund_id);
        $settings=Settings::where('group_name','=','Book_for_fund')->where('key_value',$request->book_for_fund_id)->first();
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->key_name=NULL;
        $settings->save();
        if(!empty($Book_for_fund))

                {
                  if($Book_for_fund->delete()){
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
    public function managefundStock()
    {
        $book=Book_for_fund::select('purchase_date','quantity','title','book_for_fund_id','receipt')->where('quantity','>',0)->get();
        return response()->json([
         'message'=>'success',
         'data'=>$book
        ]);
    }
    public function deletebookfund(request $request)
    {
        $stock = Book_for_fund::find($request->book_for_fund_id);
        $stock->quantity=0;

    if($stock->save()){
        return response()->json([
       'message'  => 'stock deleted successfully',
       'data'  => $stock 
        ]);
    }else {
        return response()->json([
       'message'  => 'failed'
       ]);
       }
    }
    public function addfundStock(request $request)
    {
        $stock = Book_for_fund::find($request->book_for_fund_id);
            $stock->purchase_date=$request->purchase_date;
            $stock->quantity=$request->quantity;
            $stock->receipt=$request->receipt;

        if($stock->save()){
            return response()->json([
           'message'  => 'stock saved successfully',
           'data'  => $stock 
            ]);
        }else {
            return response()->json([
           'message'  => 'failed'
           ]);
           }
    }
    public function bookfundProfile(request $request)
    {
        $Book_for_fund = Book_for_fund::leftjoin('books_category','book_for_fund.category','=','books_category.book_category_id')
        ->select('title','author','books_category.name','edition','pages','book_for_fund.description as memo','date as added_on')
        ->where('book_for_fund_id',$request->book_for_fund_id)->groupBy('book_for_fund_id')->first();
        return response()->json(['status' => 'Success', 'data' => $Book_for_fund]);
    }
}
