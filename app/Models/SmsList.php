<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class SmsList extends Model
{
   
    protected $fillable = [
       'student_id','teacher_id','admin_id','updated_by','last_message','users','number'
   ];
   protected $table = 'sms_list';
   protected $primaryKey = 'id';

   public function categories()
   {
       return $this->belongsToMany('App\sms_list');
   }
    
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s';
    }
}
