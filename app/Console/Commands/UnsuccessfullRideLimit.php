<?php

namespace App\Console\Commands;

use App\Firebase;
use App\Models\AdminNotification;
use App\Models\Notification;
use App\Models\UserRide;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UnsuccessfullRideLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Check:UnsuccessfulRideLimit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $rides = UserRide::where('status', '=', 1)
            ->where('ride_option', '=', 1)
            ->where('ride_date', '<=', strtotime('-1 minutes', time()))
            ->get();

        foreach ($rides as $ride) {

            UserRide::where('id', '=', $ride->id)->update(['status' => 5]);
            //user notification

            $user = User::where('id', '=', $ride->user_id)->first();
            $firebase = Firebase::where('user_id', '=', $user->id)->select('user_id', 'firebase_token', 'platform')->get();
            Log::info($firebase);
            foreach ($firebase as $key => $item) {
                $notification = [
                    'title' => 'Ride not accepted',
                    'body' => "User posted ride by user \"" . $user->first_name . " " . $user->last_name . "\" not get accepted.",
                    'type' => RIDE_NOT_ACCEPTED,
                    'data' => [
                        'type' => RIDE_NOT_ACCEPTED,
                        'message' => 'Your ride not get accepted',
                        'ride_id' => $ride->id
                    ]

                ];
                sendFirebaseNotification($item->firebase_token, $notification, $item->platform);
            }

            $notify = new Notification();
            $notify->sender = null;
            $notify->receiver = $user->id;
            $notify->notification_message = 'Your ride not get accepted';
            $notify->type = RIDE_NOT_ACCEPTED;
            $notify->user_ride_id = $ride->id;

            $notify->save();


//admin notification
            $admin = User::where('type', '=', 3)->select('id')->first();

            $admin_fcm = Firebase::where('user_id', '=', $admin->id)->select('firebase_token', 'platform', 'user_id')->first();
            if ($admin_fcm) {

                $notification = [
                    'title' => 'Ride not accepted',
                    'body' => "User posted ride by user \"" . $user->first_name . " " . $user->last_name . "\" not get accepted.",
                    'type' => RIDE_NOT_ACCEPTED_LIMIT,
                    'data' => [
                        'type' => RIDE_NOT_ACCEPTED_LIMIT,
                        'message' => 'User posted ride not get accepted',
                        'ride_id' => $ride->id
                    ]];

                sendFirebaseNotification($admin_fcm->firebase_token, $notification, $admin_fcm->platform);

                $admin_notify = new AdminNotification();
                $admin_notify->user_id = $user->id;
                $admin_notify->type = RIDE_NOT_ACCEPTED_LIMIT;
                $admin_notify->ride_id = $ride->id;
                $admin_notify->notification_message = 'User Posted Ride Not Accepted';

                $admin_notify->save();
            }
        }
    }

}
