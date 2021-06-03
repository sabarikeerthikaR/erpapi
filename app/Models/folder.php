<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Folder extends Model
{

     protected $fillable = [
       'title', 'description',   ];
    protected $table = 'folder';
    protected $primaryKey = 'folder_id';

    public function categories()
    {
        return $this->belongsToMany('App\folder');
    }
}
