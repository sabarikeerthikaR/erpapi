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
use App\Models\Books;
use App\Models\Books_category;
use App\Models\BookStock;
use App\Models\Borrow;
use App\Models\Settings;

class BooksController extends Controller
{
     public function store(Request $Books)
    {
      $validator =  Validator::make($Books->all(), [
      	    'title' =>['required'],
            'author' => ['required'],
            'publisher' =>['required'],
            'book_category_id' => ['required'],

          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Books=Books::create([
         'title'  =>$Books->title,
         'author'  =>$Books->author,
        'publisher'  =>$Books->publisher,
        'year_published'  =>$Books->year_published,
         'isbn_number'  =>$Books->isbn_number,
        'book_category_id'  =>$Books->book_category_id,
        'edition'  =>$Books->edition,
         'pages'  =>$Books->pages,
        'copyright'  =>$Books->copyright,
        'shelf'  =>$Books->shelf,
         'memo'  =>$Books->memo,
         'date'  =>date('d-m-Y'),
         ]);
         $settings=Settings::create([
            'group_name'=>'books',
            'key_name'=>$Books->title,
            'key_value'=>$Books->book_id,
            ]);
            $settings->save();
        if($Books->save()){
                  return response()->json([
                 'message'  => 'Books saved successfully',
                 'data'  => $Books 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
   {
   	 $Books = Books::find($request->book_id);
             if(!empty($Books)){
                    return response()->json([
                    'data'  => $Books      
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
        $Books = Books::leftjoin('books_category','books.book_category_id','=','books_category.book_category_id')
        ->leftjoin('borrow','books.book_id','=','borrow.book_id')
        ->select('title','author','books_category.name','edition','quantity',
        DB::raw("COUNT('borrow.book_id'='books.book_id')as borrowed"),
        db::raw('quantity - borrow.book_id as remaining_books'),'books.book_id')
        ->groupBy('book_id')
        ->get();
        return response()->json(['status' => 'Success', 'data' => $Books]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'title' =>['required'],
        'author' => ['required'],
        'publisher' =>['required'],
        'book_category_id' => ['required'],
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Books = Books::find($request->book_id);
        $Books->title= $request->title;
        $Books->author= $request->author;
        $Books->publisher= $request->publisher;
        $Books->year_published= $request->year_published;
        $Books->isbn_number= $request->isbn_number;
        $Books->book_category_id= $request->book_category_id;
        $Books->edition= $request->edition;
        $Books->pages= $request->pages;
        $Books->copyright= $request->copyright;
        $Books->shelf= $request->shelf;
        $Books->memo= $request->memo;
        $settings=Settings::where('group_name','=','books')->where('key_value',$request->book_id)->first();
        $settings->key_name= $request->title;
        $settings->save();
        if($Books->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Books
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Books = Books::find($request->book_id);
        $settings=Settings::where('group_name','=','books')->where('key_value',$request->book_id)->first();
        $settings->group_name=NULL;
        $settings->key_value=NULL;
        $settings->key_name=NULL;
        $settings->save();
        if(!empty($Books))

                {
                  if($Books->delete()){
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
    public function manageStock()
    {
        $book=Books::select('purchase_date','quantity','title','book_id')->where('quantity','>',0)->get();
        return response()->json([
         'message'=>'success',
         'data'=>$book
        ]);
    }
    public function deleteStock(request $request)
    {
        $stock = Books::find($request->book_id);
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
    public function addStock(request $request)
    {
        $stock = Books::find($request->book_id);
            $stock->purchase_date=$request->purchase_date;
            $stock->quantity=$request->quantity;

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
    public function bookProfile(request $request)
    {
        $Books = Books::join('books_category','books.book_category_id','=','books_category.book_category_id')
        ->select('title','author','books_category.name','edition','quantity','publisher','books.book_id',
        'year_published','isbn_number','edition','pages','copyright','shelf','memo','quantity',
        'purchase_date','date as added_on')->where('book_id',$request->book_id)->first();
        return response()->json(['status' => 'Success', 'data' => $Books]);
    }
}
