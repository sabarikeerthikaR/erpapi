<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Grants extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'grant_type','date','amount', 'payment_method','bank_deposited','purpose','school_representative','add_file','contact_person','contact_details'
    ];
    protected $table = 'grants';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\grants');
    }
}
