<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Email extends Model
{
    use HasFactory;
    use Loggable; 
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
