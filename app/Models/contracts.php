<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Contracts extends Model
{
 
    protected $fillable = [
        'name','description',
    ];
    protected $table = 'contracts';
    protected $primaryKey = 'contract_id';

    public function categories()
    {
        return $this->belongsToMany('App\contracts');
    }
}
