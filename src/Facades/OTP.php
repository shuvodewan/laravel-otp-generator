<?php

namespace Eagleeye\Otp\Facades;

use Illuminate\Support\Facades\Facade;

class OTP extends Facade
{
    public static function getFacadeAccessor(){
        return 'Otp';
    }
}