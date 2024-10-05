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
        $data=DB::table(config('otp.table_name','otps'))->where('key',$key)->latest()->first();
        return $data?$data->value:null;
    }


    /**
     * isExpired
     *
     * @param  mixed $data
     * @param  mixed $key
     * @return void
     */
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
            'created_at'=>now(),
        ];
        DB::table(config('otp.table_name','otps'))->insert($data);
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
        $data=DB::table(config('otp.table_name','otps'))->where('key',$key)->latest()->delete();
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
        $data = DB::table(config('otp.table_name','otps'))->where('key',$key)->latest()->first();
        if($data){
            $value = DB::table(config('otp.table_name','otps'))
            ->where('key',$key)
            ->latest()
            ->update([
                'value'=>$value,
                'expire'=>$expire,
                'updated_at'=>date("Y-m-d H:i:s"),
            ]);
        }
    }


    /**
     * deleteAllExpiredOtp
     *
     * @return void
     */
    public function deleteAllExpiredOtp(){
        $data=DB::table(config('otp.table_name','otps'))->where('expire','<',now())->delete();
    }
}
