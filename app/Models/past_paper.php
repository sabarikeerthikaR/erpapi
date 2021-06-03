<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Past_paper extends Model
{

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
