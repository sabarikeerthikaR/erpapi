<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Email_template extends Model
{

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
