<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Folder extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
       'title', 'description',   ];
    protected $table = 'folder';
    protected $primaryKey = 'folder_id';

    public function categories()
    {
        return $this->belongsToMany('App\folder');
    }
}
