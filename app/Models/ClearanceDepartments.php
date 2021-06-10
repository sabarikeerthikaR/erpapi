<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClearanceDepartments extends Model
{

     protected $fillable = [
        'name','description'
    ];
    protected $table = 'department';
    protected $primaryKey = 'department_id';

    public function categories()
    {
        return $this->belongsToMany('App\department');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
