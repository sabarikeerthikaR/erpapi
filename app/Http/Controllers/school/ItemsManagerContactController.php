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
use App\Models\Items_manager_contact;
use App\Models\Address_book_category;
use App\Models\Address_book;
use App\Models\Counties;

class ItemsManagerContactController extends Controller
{
    public function store(Request $Items_manager_contact)
    {
      $validator =  Validator::make($Items_manager_contact->all(), [
            'address_book'=> ['required'],
            'contact_person'       => ['required'],
            
            'cell_phone'   => ['required'],
           
          ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
        $Items_manager_contact=Items_manager_contact::create([
          'address_book'  =>$Items_manager_contact->address_book,
        'contact_person'  =>$Items_manager_contact->contact_person,
        'category'          =>$Items_manager_contact->category,
        'company_name'         =>$Items_manager_contact->company_name,
        'cell_phone'        =>$Items_manager_contact->cell_phone,
        'telephone'        =>$Items_manager_contact->telephone,
        'email'        =>$Items_manager_contact->email,
        'website'        =>$Items_manager_contact->website,
        'address'        =>$Items_manager_contact->address,
        'city'        =>$Items_manager_contact->city,
        'country'        =>$Items_manager_contact->country,
        'description' =>$Items_manager_contact->description,
         ]);
        if($Items_manager_contact->save()){
                  return response()->json([
                 'message'  => 'Items_manager_contact saved successfully',
                 'data'  => $Items_manager_contact 
                  ]);
              }else {
                  return response()->json([
                 'message'  => 'failed'
                 ]);
          }
    }
public function show(request $request)
    { 
    	       $Items_manager_contact = Items_manager_contact::find($request->id);
             if(!empty($Items_manager_contact)){
                    return response()->json([
                    'data'  => $Items_manager_contact      
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
        $Items_manager_contact = Items_manager_contact::join('address_book','items_manager_contact.address_book','=','address_book.id')
        ->join('address_book_category','items_manager_contact.category','=','address_book_category.id')
        ->join('counties','items_manager_contact.country','=','counties.id')
        ->select('contact_person','items_manager_contact.email','cell_phone','telephone','website','address','counties.name','city','email',
        'items_manager_contact.id','address_book.name as address_book','company_name',
    'address_book_category.name as category','items_manager_contact.description')->get();
        return response()->json(['status' => 'Success', 'data' => $Items_manager_contact]);
    }


public function update(Request $request)

   {
    $validator =  Validator::make($request->all(), [
        'address_book'=> ['required'],
        'contact_person'       => ['required'],
        
        'cell_phone'   => ['required'],
       
        ]); 
          if ($validator->fails()) {
            return response()->json(apiResponseHandler([], $validator->errors()->first(),400), 400);
        }
    $Items_manager_contact = Items_manager_contact::find($request->id);
         $Items_manager_contact->address_book= $request->address_book;
        $Items_manager_contact->contact_person= $request->contact_person;
        $Items_manager_contact->category= $request->category;
        $Items_manager_contact->company_name= $request->company_name;
        $Items_manager_contact->cell_phone= $request->cell_phone;
        $Items_manager_contact->telephone= $request->telephone;
        $Items_manager_contact->email= $request->email;
        $Items_manager_contact->website= $request->website;
        $Items_manager_contact->address= $request->address;
        $Items_manager_contact->city= $request->city;
        $Items_manager_contact->country= $request->country;
        $Items_manager_contact->description= $request->description;
        if($Items_manager_contact->save()){
            return response()->json([
                 'message'  => 'updated successfully',
                 'data'  => $Items_manager_contact
            ]);
        }else {
            return response()->json([
                 'message'  => 'failed'
                 ]);
        }
    }
public function destroy(Request $request)
    {
        $Items_manager_contact = Items_manager_contact::find($request->id );
        if(!empty($Items_manager_contact))

                {
                  if($Items_manager_contact->delete()){
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
    public function customers()
        {
            $Items_manager_contact = Items_manager_contact::select('contact_person as title','email','company_name','website','address','items_manager_contact.id',
            'cell_phone','telephone','city')->where('address_book',2)->get();
            return response()->json(['status' => 'Success', 'data' => $Items_manager_contact]);
        }
        public function supplier()
        {
            $Items_manager_contact = Items_manager_contact::select('contact_person as title','email','company_name','website','address','items_manager_contact.id',
            'cell_phone','telephone','city')->where('address_book',1)->get();
            return response()->json(['status' => 'Success', 'data' => $Items_manager_contact]);
    
        }
        public function others()
        {
            $Items_manager_contact = Items_manager_contact::select('contact_person as title','email','company_name','website','address','items_manager_contact.id',
            'cell_phone','telephone','city')->where('address_book',3)->get();
            return response()->json(['status' => 'Success', 'data' => $Items_manager_contact]);
    
        }
}
