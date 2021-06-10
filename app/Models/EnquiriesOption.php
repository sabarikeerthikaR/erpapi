<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class EnquiriesOption extends Model
{

    protected $fillable = [
        'name'
    ];
    protected $table = 'enquiries_option';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\enquiries_option');
    } 
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
