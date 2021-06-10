<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class InstitutionSetup extends Model
{

    protected $fillable = [
       'Address','Email','Telephone', 'CellNumber', 'Fax',
       'EmployeesTimeIn','EmployeesTimeOut','UseRemarks', 'AdmissionNo', 'TaxRelief',
       'Website','SocialMediaLink','SchoolMotto', 'Vision', 'Mission',
       'DefaultListsSize','DefaultMessage','DefaultCurrency','MobilePaymentInfo','DefaultSMSSender','MapLocation','institution_id'
   ];
   protected $table = 'institution_setup';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\institution_setup');
   }
    
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
