<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class InstitutionSetup extends Model
{
    use HasFactory;
    use Loggable; 
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
}
