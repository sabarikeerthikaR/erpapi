<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Firebase extends Model
{
    protected $fillable = ['user_id', 'firebase_token', 'platform'];
    protected $table = 'firebase';
}
