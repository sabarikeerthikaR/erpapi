<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Assignment extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
       'title','start_date','end_date','class','upload_document','assignment','comment','created_on','created_by'
    ];
    protected $table = 'assignment';
    protected $primaryKey = 'assignment_id';

    public function categories()
    {
        return $this->belongsToMany('App\assignment');
    }
}
