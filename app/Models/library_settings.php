<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Library_settings extends Model
{

     protected $fillable = [
       'fine_per_day', 'book_duration','borrow_limit', 
    ];
    protected $table = 'library_settings';
    protected $primaryKey = 'library_setting_id';

    public function categories()
    {
        return $this->belongsToMany('App\library_settings');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
