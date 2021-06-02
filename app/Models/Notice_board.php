<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Notice_board extends Model
{
    use HasFactory; use HasFactory;
    use Loggable; 
     protected $fillable = [
       'date','title','description',
    ];
    protected $table = 'notice_board';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\notice_board');
    }
}
