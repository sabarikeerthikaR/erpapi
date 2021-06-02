<?php
namespace App\Mail;

use App\Model\Admission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class BirthdayRemainder extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public function __construct()
    {
         $this->Admission = $user;
    }
    public function build()
    {
        $users = Admission::whereMonth('dob', '=', date('m'))->whereDay('dob', '=', date('d'))->get(); 
         foreach($users as $user) {    
          return $this->from('info@bandali.co.tz')
        ->with(['user' => $user]);
    }

    }
}
