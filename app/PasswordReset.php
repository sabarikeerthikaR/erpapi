<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = [
        'email', 'token'
    ];
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s.u';
    }
}
