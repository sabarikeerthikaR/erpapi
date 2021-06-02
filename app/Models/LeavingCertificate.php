<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class LeavingCertificate extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'date','student','headteachr_remark', 'curricular_activity','created_by','created_on'
    ];
    protected $table = 'leaving_certificate';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\leaving_certificate');
    }
}
