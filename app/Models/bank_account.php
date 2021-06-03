<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Bank_account extends Model
{

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
