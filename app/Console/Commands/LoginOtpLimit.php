<?php

namespace App\Console\Commands;

use App\Models\LoginOtp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LoginOtpLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Check:LoginOtpLimit';

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
        $loginOtp = LoginOtp::where('expiry_time', '<=', time())->get();

        foreach ($loginOtp as $otp) {
            LoginOtp::where('id', '=', $otp->id)->delete();
        }
    }
}
