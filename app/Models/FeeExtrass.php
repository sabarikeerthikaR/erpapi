<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FeeExtrass extends Model
{

     protected $fillable = [
        'student_id','description','select_fee','amount', 'term','year','created_by'
        ,'description'
    ];
    protected $table ='fee_extrass';
    protected $primaryKey ='id';


    public function categories()
    {
        return $this->belongsToMany('App\fee_extrass');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
