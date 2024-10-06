<?php

namespace Eagleeye\Otp;

interface StorageInterface
{
    /**
     * [Description for get]
     *
     * @param String $key
     *
     * @return [type]
     *
     */
    public function get(String $key);

    /**
     * [Description for store]
     *
     * @param String $key
     *
     * @return [type]
     *
     */
    public function store(String $key,$value,$expire);

    /**
     * [Description for destroy]
     *
     * @param String $key
     *
     * @return [type]
     *
     */
    public function destroy(String $key);


    /**
     * expire
     *
     * @param  mixed $key
     * @param  mixed $value
     * @param  mixed $expire
     * @return void
     */
    public function expire($key,$value,$expire);


    /**
     * deleteAllExpiredOtp
     *
     * @return void
     */
    public function deleteAllExpiredOtp();
}
