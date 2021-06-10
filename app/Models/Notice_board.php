<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Notice_board extends Model
{
 
     protected $fillable = [
       'date','title','description',
    ];
    protected $table = 'notice_board';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\notice_board');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
