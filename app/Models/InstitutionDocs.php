<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class InstitutionDocs extends Model
{
     use HasFactory;
     use Loggable; 
     protected $fillable = [
        'ownership_doc','institution_certificate','incorporation_certificate', 'ministry_approval', 'title_deed','institution_id'
    ];
    protected $table = 'institution_docs';
    protected $primaryKey = 'id';

    public function categories()
    {
        return $this->belongsToMany('App\institution_docs');
    }
}
