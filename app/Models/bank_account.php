<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Bank_account extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'bank_name','account_name','account_no', 'branch', 'description'
    ];
    protected $table = 'bank_account';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\bank_account');
    }
}
