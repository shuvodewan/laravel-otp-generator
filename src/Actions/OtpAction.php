<?php

namespace Eagleeye\Otp\Actions;

use DateTime;
use Eagleeye\Otp\StorageInterface;

class OtpAction{


    /**
     * key
     *
     * @var mixed
     */
    protected $key;

    /**
     * [Description for $mood]
     *
     * @var [type]
     */
    protected $mood;


    /**
     * [Description for $storage]
     *
     * @var [type]
     */
    protected $storage;


    /**
     * [Description for $timezone]
     *
     * @var [type]
     */
    protected $timezone;

    /**
     * [Description for $length]
     *
     * @var [type]
     */
    protected $length;

    /**
     * [Description for $case]
     *
     * @var [type]
     */
    protected $case;
    /**
     * [Description for $type]
     *
     * @var [type]
     */
    protected $type;

    /**
     * [Description for $prefix]
     *
     * @var [type]
     */
    protected $prefix;

    /**
     * [Description for $expire]
     *
     * @var [type]
     */
    protected $expire;

    /**
     * [Description for $max]
     *
     * @var int
     */
    protected $max=20;

    /**
     * [Description for $digits]
     *
     * @var string
     */
    protected $digits="0123456789";
    /**
     * [Description for $charecters]
     *
     * @var string
     */
    protected $charecters='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * [Description for $specialCarecters]
     *
     * @var string
     */
    protected $specialCarecters="!#$%^&*()";


    /**
     * resendIn
     *
     * @var string
     */
    protected $resendIn="60";


    /**
     * history
     *
     * @var bool
     */
    protected $history=false;

    /**
     * duplicate
     *
     * @var bool
     */
    protected $duplicate=false;


    /**
     * __construct
     *
     * @param  mixed $key
     * @return void
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage=$storage;

        $this->mood=config('otp.mood');

        $this->timezone=config('app.timezone');

        $this->length=config('otp.length');

        $this->case=config('otp.case');

        $this->type=config('otp.type');

        $this->prefix=config('otp.prefix');

        $this->expire=config('otp.expire');

        $this->history=config('otp.history');

        $this->resendIn=config('otp.resend_in');

        $this->duplicate=config('otp.duplicate');


        return $this;
    }

    /**
     * [Description for getLength]
     *
     * @return [type]
     *
     */
    private function getLength(){

        return $this->length>$this->max?$this->max:$this->length;
    }

    /**
     * [Description for getRandomDigits]
     *
     * @return [type]
     *
     */
    /**
     * [Description for getRandomDigits]
     *
     * @return [type]
     *
     */
    private function getRandomDigits(){
        return substr(number_format(time() * rand(),0,'',''),0);
    }

    /**
     * [Description for getRandomString]
     *
     * @return [type]
     *
     */
    private function getRandomString(){
        return substr(str_shuffle($this->charecters), 0);
    }

    /**
     * [Description for getAlphaNumeric]
     *
     * @return [type]
     *
     */
    private function getAlphaNumeric(){
        return substr(md5(microtime(true).mt_Rand()),0);
    }

    /**
     * [Description for getStrongDigit]
     *
     * @return [type]
     *
     */
    private function getStrongDigit(){
        return substr(str_shuffle($this->specialCarecters.substr($this->getRandomDigits(),0,8)).$this->getRandomDigits(),0);
    }

    /**
     * [Description for getStrongString]
     *
     * @return [type]
     *
     */
    private function getStrongString(){
        return substr(str_shuffle($this->specialCarecters.substr($this->getRandomString(),0,10)).$this->getRandomString(),0);
    }
    /**
     * [Description for getStrongAlphaNumeric]
     *
     * @return [type]
     *
     */
    private function getStrongAlphaNumeric(){

        return substr(str_shuffle($this->specialCarecters.substr($this->getAlphaNumeric(),0,10)).$this->getAlphaNumeric(),0);
    }

    /**
     * [Description for getRandom]
     *
     * @return [type]
     *
     */
    private function getRandom(){
        switch($this->type)
        {
            case 'numeric':
                return $this->getRandomDigits();
                break;

            case 'alphabetic':
                return $this->getRandomString();
                break;

            case 'alphanumeric':
                return $this->getAlphaNumeric();
                break;

            case 'mixnumeric':
                return $this->getStrongDigit();
                break;

            case 'mixalphabetic':
                return $this->getStrongString();
                break;

            case 'mixalphanumeric':
                return $this->getStrongAlphaNumeric();
                break;
        }
    }


    /**
     * [Description for caseChecker]
     *
     * @param mixed $value
     *
     * @return [type]
     *
     */
    private function caseChecker($value){

        switch($this->case){
            case 'upper':
                return strtoupper($value);
                break;

            case 'lower':
                return strtolower($value);
                break;
        }
    }

    private function getPrefix($value){

        return $this->prefix?(string)$this->prefix.'-'.$value:$value;

    }

    /**
     * [Description for setKey]
     *
     * @param mixed $key
     *
     * @return [type]
     *
     */
    private function setKey($key){
        $this->key=$key;
    }

    /**
     * extract
     *
     * @return void
     */
    private function extract($value){

        return $value?explode('@',$value):null;
    }

    /**
     * getValue
     *
     * @return void
     */
    private function getValue($value=null){

        $value=$this->extract($value??$this->storage->get($this->key));
        return $value?$value[0]:null;
    }

    /**
     * getValue
     *
     * @return void
     */
    private function isDuplicateable($value){

        if($this->duplicate){
            $value=$this->extract($value);
            $expire_time = new DateTime($value[1]);
            $current_date = new DateTime();
            return $expire_time>$current_date;
        }
    }


    /**
     * [Description for getTime]
     *
     * @return [type]
     *
     */
    private function getTime(){
        // dd($this->key,Cache::get($this->key));
        $value=$this->extract($this->storage->get($this->key));
        if($value){
            $resend_time = new DateTime($value[2]);
            $current_date = new DateTime();
            if($resend_time>$current_date){
                return $value[2];
            }
        }
    }

    /**
     * isExpired
     *
     * @return void
     */
    private function isExpired($value){
        $value = $this->extract($value);
        $expire_time = new DateTime($value[1]);
        $current_date = new DateTime();
        return $expire_time<$current_date;
    }



    /**
     * deleteValue
     *
     * @return void
     */
    private function deleteValue(){
        $this->storage->destroy($this->key);
    }


    /**
     * [Description for generateExpireTime]
     *
     * @return [type]
     *
     */
    private function generateExpireTime(){
        date_default_timezone_set($this->timezone);
        $now = date("Y-m-d H:i:s");
        return date("Y-m-d H:i:s", strtotime('+'.$this->expire.' seconds', strtotime($now)));
    }

    /**
     * generateExpireTime
     *
     * @return void
     */
    private function generateResendTime(){
        date_default_timezone_set($this->timezone);
        $now = date("Y-m-d H:i:s");
        return date("Y-m-d H:i:s", strtotime('+'.$this->resendIn.' seconds', strtotime($now)));
    }

    /**
     * [Description for getGeneratedValue]
     *
     * @return [type]
     *
     */
    private function getGeneratedValue(){
        return $this->getPrefix($this->caseChecker(substr($this->getRandom(),0,$this->length)));
    }

    /**
     * GenerateOtp
     *
     * @return void
     */
    private function GenerateOtp(){

        $value = $this->storage->get($this->key);

        if($value){
            $duplicateable = $this->isDuplicateable($value);
            $expired=$this->isExpired($value);

            if(!$this->history){
                $this->deleteValue();
            }

            if($expired || !$duplicateable){
                $otp=$this->getGeneratedValue();
                $expireTime = $this->generateExpireTime();
                $resentTime = $this->generateResendTime();
            }else{
                $otp=$this->getValue($value);
                $expireTime = $this->extract($value)[1];
                $resentTime = $this->generateResendTime();
            }
        }else{
            $otp=$this->getGeneratedValue();
            $expireTime = $this->generateExpireTime();
            $resentTime = $this->generateResendTime();
        }

        $payload=$otp.'@'.$expireTime.'@'.$resentTime; // OTP@Expired@Resend

        $this->storage->store($this->key,$payload, $expireTime);

        return $otp;
    }


    /**
     * [Description for get_remaining_time]
     *
     * @return [type]
     *
     */
    private function get_remaining_time(){
        $expiry_time = new DateTime($this->getTime());
        $current_date = new DateTime();
        $diff = $expiry_time->diff($current_date);
        return $diff->format('%D %H:%i:%s');
    }

    /**
     * setConfig
     *
     * @param  mixed $options
     * @return void
     */
    private function setConfig($options){
        $configs=['length','case','prefix','type'];
        foreach($options as $key=>$option){
            if(in_array($key,$configs)){
                $this->$key=$option;
            }
        }
    }

    /**
     * [Description for readonly]
     *
     * @return [type]
     *
     */
    public function readonly($options=null){
        if(is_array($options) && count($options)>0){
            $this->setConfig($options);
        }
        return $this->getGeneratedValue();
    }

    /**
     * [Description for get_otp]
     *
     * @param mixed $key
     * @param Callable $smscallback=null
     *
     * @return [type]
     *
     */
    public function get(String $key,$callback=null){

        $this->setKey($key);

        $otp=$this->GenerateOtp();

        if($callback!=null && is_callable($callback)){
            call_user_func($callback,$otp);
        }

        return $this->getValue();
    }


    /**
     * [Description for interval]
     *
     * @param String $key
     * @param mixed $callback=null
     *
     * @return [type]
     *
     */
    public function interval(String $key, $callback=null){

        $this->setKey($key);

        if($this->getTime()){
            return ['expired'=>false,'remaining'=>$this->get_remaining_time()];
        }
        $otp=$this->GenerateOtp();

        if($callback!=null && is_callable($callback)){
            call_user_func($callback,$otp);
        }

        return ['expired'=>true,'otp'=>$this->getValue(),'remaining'=>$this->get_remaining_time()];
    }


    /**
     * [Description for send_otp_sms]
     *
     * @param mixed $key
     * @param mixed $phone
     *
     * @return [type]
     *
     */
    public function action(String $key,Callable $callback){

        return $this->get($key,$callback);
    }


    /**
     * [Description for send_lazy_otp_sms]
     *
     * @param mixed $phone
     *
     * @return [type]
     *
     */
    public function intervalaction(String $key,Callable $callback){

        return $this->interval($key,$callback);
    }

    /**
     * [Description for verify]
     *
     * @return [type]
     *
     */
    public function verify(String $key,$otp){

        $this->setKey($key);

        $value = $this->storage->get($this->key);

        $result =  $value && !$this->isExpired($value) && $otp===$this->getValue($value);

        if($result){
            $value = $this->extract($value);
            if($this->history){
                $expired = date("Y-m-d H:i:s");
                $this->storage->expire($key,$value[0].'@'.$expired.'@'.$value[2],$expired);
            }else{
                $this->storage->destroy($key);
            }
        }

        return $result;
    }


    /**
     * deleteAllExpiredOtp
     *
     * @return void
     */
    public function deleteAllExpiredOtp(){

        if(!$this->history){
            $this->storage->deleteAllExpiredOtp();
        }
    }


}
