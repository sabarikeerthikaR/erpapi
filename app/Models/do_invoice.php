<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Do_invoice extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'term'
    ];
    protected $table = 'do_invoice';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\do_invoice');
    }
}
