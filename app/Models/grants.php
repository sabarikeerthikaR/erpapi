<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Grants extends Model
{

     protected $fillable = [
        'grant_type','date','amount', 'payment_method','bank_deposited','purpose','school_representative','add_file','contact_person','contact_details'
    ];
    protected $table = 'grants';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\grants');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
