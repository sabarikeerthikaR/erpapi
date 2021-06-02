<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class SettingsPositions extends Model
{
    
     protected $fillable = [
        'name','description'
    ];
    protected $table = 'setting_postition';
    protected $primaryKey ='id';

    public function categories()
    {
        return $this->belongsToMany('App\setting_postition');
    }
}
