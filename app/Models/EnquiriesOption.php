<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class EnquiriesOption extends Model
{
     use HasFactory;
     use Loggable; 
    protected $fillable = [
        'name'
    ];
    protected $table = 'enquiries_option';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\enquiries_option');
    }
}
