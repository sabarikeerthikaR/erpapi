<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Counties extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'name','description'
    ];
    protected $table = 'counties';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\counties');
    }
}
