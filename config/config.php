
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
     * [Description for type]
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



    'storage'=>'cache',




    /**
     * [Description for expire]
     *
     * OTP expire time
     * In seconds
     *
     */



    'expire'=>"60",

    /**
     * [Description for history]
     *
     * If true previous expired or validated otp wont delete
     * In boolean
     *
     */
    'history'=>false,


    /**
     * [Description for resend_in]
     *
     * Resend remaining time for interval mode
     * Should be smaller then expire time
     * In seconds
     *
     */
    'resend_in'=>"60",

    /**
     * [Description for duplicate]
     *
     * Send previously generated for resend till expire or validate
     * In boolean
     *
     */
    'duplicate'=>false,

    /**
     * [Description for duplicate_till]
     *
     * Duplicate resend otp till
     * Should be smaller then expire time
     * In seconds
     *
     */
    'duplicate_till'=>'60',


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
