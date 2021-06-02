<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Hostel extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'title','rooms','janitor','description','created_on'
    ];
    protected $table = 'hostel';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\hostel');
    }
}
