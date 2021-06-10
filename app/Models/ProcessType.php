<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ProcessType extends Model
{
    
    protected $fillable = [
        'name'
    ];
    protected $table = 'process_type';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\process_type');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
