<?php

namespace Eagleeye\Otp;

use Illuminate\Support\ServiceProvider;
use Eagleeye\Otp\Actions\OtpAction;
use Eagleeye\Otp\Storage\SessionStorage;
use Eagleeye\Otp\Storage\CacheStorage;
use Eagleeye\Otp\Storage\DatabaseStorage;

class OtpServiceProvider extends ServiceProvider
{
    /**
     * [Description for boot]
     *
     * @return [type]
     *
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
              __DIR__.'/../config/config.php' => config_path('otp.php'),
            ], 'config');

            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/create_otps_table.txt' => database_path("/migrations/{$timestamp}_create_otps_table.php"),
            ], 'migrations');

            $this->commands([
                DeleteAllExpiredOtp::class
            ]);

        }

    }

    /**
     * [Description for register]
     *
     * @return [type]
     *
     */
    public function register()
    {
        $this->app->singleton('Otp',function($app){
            return new OtpAction($app->make(StorageInterface::class));
        });

        $this->app->bind(StorageInterface::class,function($app){
            if(config('otp.storage')=='cache'){
                return new CacheStorage();
            }
            else if(config('otp.storage')=='session')
            {
                return new SessionStorage();
            }
            else if(config('otp.storage')=='database')
            {
                return new DatabaseStorage();
            }

        });

        ///Register
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'otp');

    }
}
