<?php
namespace Eagleeye\Otp\Storage;

use DateTime;
use Carbon\Carbon;
use Eagleeye\Otp\StorageInterface;
use Illuminate\Support\Facades\DB;

class DatabaseStorage implements StorageInterface
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
        $data=DB::table('otps')->where('key',$key)->latest()->first();
        if($data){
            $this->isExpired($data->value,$key)?$data=null:'';
        }
        return $data?$data->value:null;
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
        $data=[
            'key'=>$key,
            'value'=>$value,
            'expire'=>$expire,
        ];
        DB::table('otps')->insert($data);
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
        $data=DB::table('otps')->where('key',$key)->delete();
    }
}