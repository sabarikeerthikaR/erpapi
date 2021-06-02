<?php

namespace App\Console\Commands;

use App\Firebase;
use App\Models\Notification;
use App\Models\RideOtp;
use App\Models\UserRide;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class ScheduledRideNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Notify:ScheduledRide';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification for ride later and driver posted ride before the ride started';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rides = UserRide::where('ride_option', '=', 2)
            ->whereBetween('ride_date', [strtotime("+9 minutes", strtotime("now")), strtotime("+10 minutes", strtotime("now"))])
            ->where('status', '=', 2)
            ->get();

        Log::info(time());
        foreach ($rides as $ride) {
            Log::info($ride);


            $otp = new RideOtp();

            $otp->ride_id = $ride->id;
            $otp->type = 2;
            $otp->otp = mt_rand(100000, 999999);

            $otp->save();

            $users = Firebase::where('user_id', '=', $ride->user_id)->select('user_id', 'firebase_token', 'platform')->get();

            foreach ($users as $user) {
                $notification = [
                    'title' => 'Your scheduled ride start soon',
                    'body' => "Your Scheduled ride is going to be start in 10 minutes. OTP : " . $otp->otp,
                    'type' => 18,
                    'data' => [
                        'type' => 18,
                        'message' => "OTP : " . $otp->otp,
                        'otp' => $otp->otp
                    ]

                ];
                sendFirebaseNotification($user->firebase_token, $notification, $user->platform);

            }


            $notify = new Notification();
            $notify->sender = $ride->driver_id;
            $notify->receiver = $ride->user_id;
            $notify->notification_message = 'Your Scheduled Ride started soon';
            $notify->type = 18;
            $notify->otp = $otp->otp;
            $notify->user_ride_id = $ride->id;

            $notify->save();

            $driver = Firebase::where('user_id', '=', $ride->driver_id)->select('user_id', 'firebase_token', 'platform')->first();

            $notification = [
                'title' => "You have a scheduled ride " . date('H:i A', $ride->ride_date),
                'body' => "You have a scheduled ride at " . date('H:i A', $ride->ride_date),
                'type' => 19,
                'data' => [
                    'type' => 19,
                    'message' => "You have a scheduled ride at " . date('H:i A', $ride->ride_date),
                    'time' => $ride->ride_date
                ]

            ];
            sendFirebaseNotification($driver->firebase_token, $notification, $driver->platform);


            $notify = new Notification();
            $notify->sender = $ride->driver_id;
            $notify->receiver = $ride->driver_id;
            $notify->notification_message = 'You have a Scheduled ride at ' . date('Y-m-d', $ride->ride_date);
            $notify->type = 19;
            $notify->user_ride_id = $ride->id;

            $notify->save();


        }
    }

}
