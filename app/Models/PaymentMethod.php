<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class PaymentMethod extends Model
{
   use HasFactory;
   use Loggable; 
    protected $fillable = [
        'name'
    ];
    protected $table = 'payment_method';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\payment_method');
    }
}
