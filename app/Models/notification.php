<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    
     protected $fillable = [
        'action_performer','action_to','action_title','description','read_status'
    ];
    protected $table = 'notification';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\notification');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
