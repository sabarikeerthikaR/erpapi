<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Ownership extends Model
{
    use HasFactory;
    use Loggable; 
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
