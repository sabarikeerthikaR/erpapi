<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class SubCounty extends Model
{
   
     protected $fillable = [
        'county','sub_county'
    ];
    protected $table = 'sub_county';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\sub_county');
    }
}
