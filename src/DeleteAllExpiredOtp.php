<?php

namespace Eagleeye\Otp;

use Eagleeye\Otp\Actions\OtpAction;
use Illuminate\Console\Command;

class DeleteAllExpiredOtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all expired otp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $action = app()->make(OtpAction::class);

       if(!config('otp.history')){
        $action->deleteAllExpiredOtp();
       }
    }
}
