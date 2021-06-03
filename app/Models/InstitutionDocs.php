<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class InstitutionDocs extends Model
{

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
