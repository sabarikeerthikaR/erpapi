<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class NewPlacement extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'student','date','position', 'rep_of','date_upto','description'
    ];
    protected $table = 'new_placement';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\new_placement');
    }
}
