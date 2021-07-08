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
use App\Models\ExpenseDetails;

class ExpenseDetailsController extends Controller
{
    public function store(Request $request)
    {
       
       
        $expense=$request->expense;
        $errors=[];
        foreach($expense as $g)
        {
            if( $files=$request->file('receipt'))
            {
                        foreach($files as $file)
                    {
                     
                        if($file){
                        $imgName = time() . '.' . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                        Storage::disk('public_uploads')->put('/expense_receipt/' . $imgName, file_get_contents($receipt));
                        $receipt=config('app.url').'/public/uploads/expense_receipt/' . $imgName;
                        }
                    }

            }
                 $receipt='';
                $ExpenseDetails = new ExpenseDetails(array(
                  'date'   =>$g['date'],
                  'title'=>$g['title'],
                  'category'=>$g['category'],  
                  'amount'  =>$g['amount'],
                  'person_responsible'   =>$g['person_responsible'],
                  'receipt'=>$receipt,
                  'description'   =>$g['description'],
                 ));
                  if(!$ExpenseDetails->save())
                  {
                    $errors[]=$g;
                  }
        } 
    
    
             
              if(count($errors)==0)
              {
              return response()->json([
                'data'=>$expense,
              'message'  => 'ExpenseDetails saved successfully',
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

    $ExpenseDetails = ExpenseDetails::find($request->id);

             if(!empty($ExpenseDetails)){
                    return response()->json([
                    'data'  => $ExpenseDetails      
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
        $ExpenseDetails = ExpenseDetails::leftjoin('staff','expensedetails.person_responsible','=','staff.employee_id')
        ->leftjoin('expense_item','expensedetails.title','=','expense_item.id')
        ->leftjoin('expense_category','expensedetails.category','=','expense_category.id')
        ->select('expensedetails.date','amount','receipt','expensedetails.description','expense_item.name as title',
                 'expense_category.name as category',db::raw("CONCAT(first_name,' ',COALESCE(middle_name,''),' ',last_name)as person_responsible"))
        ->get();
        return response()->json(['status' => 'Success', 'data' => $ExpenseDetails]);
    }


public function update(Request $request)

   {
   
    $ExpenseDetails = ExpenseDetails::find($request->id);
        $ExpenseDetails->date = $request->date ;
        $ExpenseDetails->title = $request->title ;
         $ExpenseDetails->category = $request->category ;
        $ExpenseDetails->amount = $request->amount ;
         $ExpenseDetails->person_responsible = $request->person_responsible ;
         $ExpenseDetails->receipt = $request->receipt ;
         $ExpenseDetails->description = $request->description ;
        if($ExpenseDetails->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $ExpenseDetails
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $ExpenseDetails = ExpenseDetails::find($request->id);
        if(!empty($ExpenseDetails))

                {
                  if($ExpenseDetails->delete()){
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
