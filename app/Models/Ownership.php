<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Ownership extends Model
{

     protected $fillable = [
        'ownership','proprietor','ownership_type', 'certificate_no', 'town','police_station','health_facility','institution_id'
    ];
    protected $table = 'ownership';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\ownership');
    }
}
