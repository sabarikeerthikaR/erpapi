<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class PaymentOptions extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'account','business_no','descripton'
    ];
    protected $table = 'payment_options';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\payment_options');
    }
}
