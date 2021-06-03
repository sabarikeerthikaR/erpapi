<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Email extends Model
{

     protected $fillable = [
        'send_to','subject','cc','file','description','date','status'
    ];
    protected $table = 'email';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\email');
    }
}
