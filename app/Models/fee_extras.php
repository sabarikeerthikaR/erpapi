<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Fee_extras extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'title','fee_type','amount','charged','description'
    ];
    protected $table = 'fee_extras';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\fee_extras');
    }
}
