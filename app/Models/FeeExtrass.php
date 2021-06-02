<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\school;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class FeeExtrass extends Model
{
    use HasFactory;
   use Loggable; 
     protected $fillable = [
        'student_id','description','select_fee','amount', 'term','year'
    ];
    protected $table ='fee_extrass';
    protected $primaryKey ='id';


    public function categories()
    {
        return $this->belongsToMany('App\fee_extrass');
    }
}
