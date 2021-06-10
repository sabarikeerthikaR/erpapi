<?php

namespace App;

use App\Models\DriverProfile;
use App\Models\Message;
use App\Models\Subadmin_modules;
use App\Models\VehicleImage;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'previous_charge',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function vehicleImage()
    {
        return $this->hasManyThrough(VehicleImage::class, DriverProfile::class, 'user_id', 'vehicle_id', 'id', 'id');
    }

    public function modules()
    {
        return $this->hasMany(Subadmin_modules::class, 'sub_admin_id', 'id');
    }


    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */


    public function hasPermission($module)
    {
        if ($this->type != 3) {
            $check = Subadmin_modules::where('module_id', '=', $module)->where('sub_admin_id', '=', $this->id)->first();

            if (empty($check)) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }

    }
    public function getDateFormat()
    {
      return 'Y-m-d H:i:s.u';
    }
}
