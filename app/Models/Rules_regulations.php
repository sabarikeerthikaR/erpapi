<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Rules_regulations extends Model
{
   
     protected $fillable = [
       'title', 'content', 
    ];
    protected $table = 'rules_regulations';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\rules_regulations');
    }
}
