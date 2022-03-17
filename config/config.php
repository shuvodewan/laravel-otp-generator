<?php

return
[   /**
    * [Description for boot]
    *
    * @return [type]
    * 
    */
    'prefix'=>null,

    /**
     * [Description for boot]
     *
     * @return [type]
     * 
     */
    'type'=>'numeric',

    /**
     * [Description for boot]
     *
     * @return [type]
     * 
     */
    'length'=>'6',

    /**
     * [Description for boot]
     *
     * @return [type]
     * 
     */
    'mood'=>env('APP_ENV'),

    /**
     * [Description for boot]
     *
     * @return [type]
     * 
     */
    'storage'=>'cache',

    /**
     * [Description for boot]
     *
     * @return [type]
     * 
     */
    'expire'=>"60",

    /**
     * [Description for boot]
     *
     * @return [type]
     * 
     */
    'case'=>'lower',


    /**
     * [Description for boot]
     *
     * @return [type]
     * 
     */
    'table_name'=>'otp_table'
];