<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayeConfig extends Model
{
    
     protected $fillable = [
        'Range_From','Range_To','Tax_Percentage','Taxable_Amount'
    ];
    protected $table = 'paye_configuration';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\paye_configuration');
    }
     
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
