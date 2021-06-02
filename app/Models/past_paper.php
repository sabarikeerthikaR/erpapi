<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Past_paper extends Model
{
   use HasFactory;
   use Loggable; 
     protected $fillable = [
       'year', 'name','upload_paper','folder_id' ,'class'
    ];
    protected $table = 'past_papers';
    protected $primaryKey = 'past_paper_id';

    public function categories()
    {
        return $this->belongsToMany('App\past_papers');
    }
}
