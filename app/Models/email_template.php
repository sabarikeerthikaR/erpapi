<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Email_template extends Model
{
   use HasFactory;
   use Loggable; 
     protected $fillable = [
       'title','slug','description','email_body','status'
    ];
    protected $table = 'email_template';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\email_template');
    }
}
