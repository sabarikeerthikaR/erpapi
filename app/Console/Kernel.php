<?php

namespace App\Console;

use App\Console\Commands\LoginOtpLimit;
use App\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        LoginOtpLimit::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $users = DB::table('users')
                ->where('status', '=', 0)
                ->get();

            foreach ($users as $user) {
                if (!empty($user->activation_date)) {
                    if (time() >= $user->activation_date) {
                        User::where('id', '=', $user->id)->update(['status' => 1, 'activation_date' => null]);
                        $mail = new PHPMailer(true);

                        $html = view('emails.account_reactivation')->render();

                        $mail->setFrom('noreply@drupp.com', 'noreply');
                        $mail->addAddress($user->email);

                        $mail->Subject = 'Account Reactivation';
                        $mail->Body = $html;
                        $mail->send();
                    }
                }

            }
        })->hourly();

        $schedule->command('Notify:ScheduledRide')
            ->everyMinute();
        $schedule->command('Check:LoginOtpLimit')
            ->everyMinute();
        $schedule->command('Check:UnsuccessfulRideLimit')
            ->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
