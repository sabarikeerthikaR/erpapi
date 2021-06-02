<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Add_item extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'name','category_id','reorder_level', 'description', 'created_by'
    ];
    protected $table = 'add_item';
    protected $primaryKey = 'item_id';

    public function categories()
    {
        return $this->belongsToMany('App\add_item');
    }
}
