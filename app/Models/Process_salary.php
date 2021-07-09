<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Process_salary extends Model
{
    
     protected $fillable = [
        'sal_month','year','processing_date','employee','comment','created_by'
    ];
    protected $table = 'process_salary';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\process_salary');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
