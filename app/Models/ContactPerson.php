<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class ContactPerson extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'name','phone','designation', 'email','institution_id'
    ];
    protected $table = 'contact_person';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\contact_person');
    }
}
