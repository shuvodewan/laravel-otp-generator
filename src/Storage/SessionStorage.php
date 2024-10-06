<?php
namespace Eagleeye\Otp\Storage;

use DateTime;
use Eagleeye\Otp\StorageInterface;

class SessionStorage implements StorageInterface
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
        $data=session()->get($key);
        if($data){
            $this->isExpired($data,$key)?$data=null:'';
        }
        return $data;
    }

    private function isExpired($data,$key){
        $dataset=explode('@',$data);
        $expiry_time = new DateTime($dataset[1]);
        $current_date = new DateTime();
        if($expiry_time<$current_date){
            $this->destroy($key);
            return true;
        }
        else
        {
            return false;
        }
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
        session()->put($key,$value);
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
        session()->forget($key);
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
