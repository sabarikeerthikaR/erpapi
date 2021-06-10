<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Items_manager_contact extends Model
{


     protected $fillable = [
        'address_book','contact_person','category', 'company_name', 'cell_phone','telephone','email','website','address','city','country','description'
    ];
    protected $table = 'items_manager_contact';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\items_manager_contact');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
