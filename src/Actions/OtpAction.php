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


    protected $storage;


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
     * __construct
     *
     * @param  mixed $key
     * @return void
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage=$storage;
        $this->mood=config('otp.mood');

        return $this;
    }

    /**
     * [Description for getLength]
     *
     * @return [type]
     * 
     */
    private function getLength(){

        return config('otp.length')>$this->max?$this->max:config('otp.length');
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

    private function getRandom(){
        switch(config('otp.type'))
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

        switch(config('otp.case')){
            case 'upper':
                return strtoupper($value);
                break;

            case 'lower':  
                return strtolower($value);
                break;  
        }
    }

    private function getPrefix($value){

        return config('otp.prefix')?(string)config('otp.prefix').'-'.$value:$value;

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
    private function getValue(){
        $value=$this->extract($this->storage->get($this->key));
        return $value?$value[0]:null;
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
        return $value?$value[1]:null;
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
        date_default_timezone_set(config('app.timezone'));
        $now = date("Y-m-d H:i:s");
        return date("Y-m-d H:i:s", strtotime('+'.config('otp.expire').' seconds', strtotime($now)));
    }
        
    /**
     * GenerateOtp
     *
     * @return void
     */
    private function GenerateOtp(){

        $this->getValue()?$this->deleteValue():'';

        $otp=$this->getPrefix($this->caseChecker(substr($this->getRandom(),0,config('otp.length')))).'@'.$this->generateExpireTime();

        $this->storage->store($this->key,$otp, $this->generateExpireTime());

        return $this->getValue();
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
     * [Description for get_otp]
     *
     * @param mixed $key
     * @param Callable $smscallback=null
     * 
     * @return [type]
     * 
     */
    public function get(String $key,$smscallback=null){

        $this->setKey($key);

        $otp=$this->GenerateOtp();

        if($smscallback!=null && is_callable($smscallback)){
            call_user_func($smscallback,$otp);
        }

        return $this->getValue();
    }


    public function interval(String $key, $smscallback=null){

        $this->setKey($key);

        if($this->getTime()){
            return ['expired'=>false,'remaining'=>$this->get_remaining_time()];
        }
        $otp=$this->GenerateOtp();

        if($smscallback!=null && is_callable($smscallback)){
            call_user_func($smscallback,$otp);
        }

        return ['expired'=>true,'otp'=>$this->getValue()];
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
    public function action(String $key,Callable $smscallback){

        return $this->get($key,$smscallback);
    }


    /**
     * [Description for send_lazy_otp_sms]
     *
     * @param mixed $phone
     * 
     * @return [type]
     * 
     */
    public function intervalaction(String $key,Callable $smscallback){

        return $this->interval($key,$smscallback);
    }

    /**
     * [Description for verify]
     *
     * @return [type]
     * 
     */
    public function verify(String $key,$otp){

        $this->setKey($key);

        $value=$this->getValue();
        
        if($value){
            return $otp===$value;
        }
        return false;
    }

   
}