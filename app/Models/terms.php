<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Terms extends Model
{
    
     protected $fillable = [
       'name' 
    ];
    protected $table = 'terms';
    protected $primaryKey = 'term_id';

    public function categories()
    {
        return $this->belongsToMany('App\terms');
    }
}
