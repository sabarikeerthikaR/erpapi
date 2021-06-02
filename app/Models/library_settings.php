<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Library_settings extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
       'fine_per_day', 'book_duration','borrow_limit', 
    ];
    protected $table = 'library_settings';
    protected $primaryKey = 'library_setting_id';

    public function categories()
    {
        return $this->belongsToMany('App\library_settings');
    }
}
