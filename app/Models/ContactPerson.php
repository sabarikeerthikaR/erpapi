<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class ContactPerson extends Model
{

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
