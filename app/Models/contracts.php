<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Contracts extends Model
{
    use HasFactory;
    use Loggable; 
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
