<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionDetails extends Model
{

    protected $fillable = [
       'School_Name','Registration_Number','School_Code', 'Registration_Date', 'Institution_Category',
       'Institution_Cluster','County','Sub_County', 'Ward', 'Institution_Type',
       'Education_System','Education_Level','KNEC_Code', 'Institution_Accommodation', 'Scholars_Gender',
       'Locality','KRA_PIN'
   ];
   protected $table = 'institution_details';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\institution_details');
   }
}
