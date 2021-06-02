<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Gradings extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
       'grade','min_mark','max_mark','remark','grading_system_id','created_by','created_on'
    ];
    protected $table = 'gradings';
    protected $primaryKey = 'grading_id';

    public function categories()
    {
        return $this->belongsToMany('App\gradings');
    }
}
