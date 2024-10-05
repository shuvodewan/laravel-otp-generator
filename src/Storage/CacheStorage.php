<?php
namespace Eagleeye\Otp\Storage;

use Carbon\Carbon;
use Eagleeye\Otp\StorageInterface;
use Illuminate\Support\Facades\Cache;

class CacheStorage implements StorageInterface
{
    /**
     * [Description for get]
     *
     * @param mixed $key
     *
     * @return [type]
     *
     */
    public function get($key){
        return Cache::get($key);
    }
    /**
     * [Description for store]
     *
     * @param mixed $key
     *
     * @return [type]
     *
     */
    public function store($key,$value,$expire){
        Cache::put($key,$value ,new Carbon($expire));
    }
    /**
     * [Description for destroy]
     *
     * @param mixed $key
     *
     * @return [type]
     *
     */
    public function destroy($key){
        Cache::forget($key);
    }

    /**
     * expire
     *
     * @param  mixed $key
     * @param  mixed $value
     * @param  mixed $expire
     * @return void
     */
    public function expire($key,$value,$expire){
        $this->destroy($key);
    }

    /**
     * deleteAllExpiredOtp
     *
     * @return void
     */
    public function deleteAllExpiredOtp(){

    }
}
