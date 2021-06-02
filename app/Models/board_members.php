<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class Board_members extends Model
{
    use HasFactory;
    use Loggable; 
     protected $fillable = [
        'disable','title','first_name','other_name', 'gender', 'phone','email','position', 'date_joined','work_place','profile_details', 'passport_photo','copy_id'
    ];
    protected $table = 'board_members';
    protected $primaryKey = 'board_mem_id';

    public function categories()
    {
        return $this->belongsToMany('App\board_members');
    }
}
