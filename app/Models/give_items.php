<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Give_items extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'date','item','quantity', 'given_to', 'comment'
    ];
    protected $table = 'give_items';
    protected $primaryKey = 'give_item_id';

    public function categories()
    {
        return $this->belongsToMany('App\give_items');
    }
}
