<?php

return
[   /**
    * [Description for prefix]
    *
    * Example: G  | Output G-12345678
    * 
    */


    
    'prefix'=>null,



    /**
     * [Description for boot]
     *
     * Example: numeric         | Output  12345678
     * Example: alphabetic      | Output  ktylnfdgf
     * Example: alphanumeric    | Output  kt7l7fdg9
     * Example: mixnumeric      | Output  !45<45!)
     * Example: mixalphabetic   | Output  !ta<hg!)
     * Example: mixalphanumeric | Output  !t4<7g!)
     */


    'type'=>'numeric',


    /**
     * [Description for length]
     *
     * Example: 6    | Output 123456
     * Example: 8    | Output 12345678
     * 
     */



    'length'=>'6',




    /**
     * [Description for Mood]
     *
     * @return [type]
     * 
     */



    'mood'=>env('APP_ENV'),




    /**
     * [Description for storage]
     *
     * Example: databse     | OTP will store in databse
     * Example: cache       | OTP will store in cache
     * Example: session     | OTP will store in session
     */



    'storage'=>'database',




    /**
     * [Description for expire]
     *
     * OTP expire time 
     * In seconds
     * 
     */



    'expire'=>"60",




    /**
     * [Description for case]
     *
     * Example: lower    | Output werfghyt
     * Example: upper    | Output AFKUTDFG
     */



    'case'=>'lower',



    /**
     * [Description for table_name]
     *
     * Table name to create table in databse
     * 
     */



    'table_name'=>'otp_table'
];