<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Gender extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'name'
     ];
    protected $table = 'gender';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\gender');
    }
}
