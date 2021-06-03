<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Hostel extends Model
{

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
