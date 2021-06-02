<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Items_manager_contact extends Model
{
     use HasFactory;
     use Loggable; 
     protected $fillable = [
        'address_book','contact_person','category', 'company_name', 'cell_phone','telephone','email','website','address','city','country','description'
    ];
    protected $table = 'items_manager_contact';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\items_manager_contact');
    }
}
