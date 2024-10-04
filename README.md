
# LARAVEL OTP GENERATOR 

A laravel package to generate OTP. 


- [Installation](#installation)
- [Vendor Publication](#vendorpublish)
    - [Config](#vendor_config)
    - [Migrations](#vendor_database)
- [Configuration](#config)
    - [Prefix](#config_prefix)
    - [Type](#config_type)
    - [Length](#config_length)
    - [Storage](#config_storage)
    - [Expire](#config_expire)
    - [Case](#config_case)
    - [Table Name](#config_table)
- [Usage](#usage)
    - [Get Method](#uses_get)
    - [Interval Method](#uses_interval)
    - [Action Method](#uses_action)
    - [Interval Action Method](#uses_intervalaction)
    
    

<br/>

## 🚀 <span id="installation">Installation </span>

``Composer`` will allows you to quickly  install via the command line.

```bash
  composer require eagleeye/otp
```
<br/>

## 🚀<span id="vendorpublish">Vendor Publishing</span>

---

>**NOTE**</br>
>Publishing vendors are optional only required if you are willing to change configuration and use database as ***OTP*** storage.

<br/>

<span id="vendor_config">Publish config file</span>

```bash
php artisan vendor:publish --provider="Eagleeye\Otp\OtpServiceProvider" --tag=config

```

<span id="vendor_database"> Publish database migration file</span>

```bash
php artisan vendor:publish --provider="Eagleeye\Otp\OtpServiceProvider" --tag=migrations

```
<br/>

## ✨<span id="config"> Configuration File </span>

>**NOTE**</br>
>You will find the ***OTP*** configuration file in `config` folder name as `otp.php`  .

<br/>

otp.php
```bash php

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


```

- ###  <span id="config_prefix">Prefix</span> 
    - The `prefix` default value is `null`. If you would like to add a text or trademark or a single character like `google`  registration `OTP` in every generated otp, Just add the prefix value.<br\>
    `Prefix` and Actual `OTP` will be seperated by a ( `-` )

      |Prefix|Without Prefix|With Prefix|
      |------|--------------|-----------|
      |P     |12345678      |P-12345678 |
    

- ### <span id="config_type">type</span> 
    - The `type` represent `OTP` string type .default value is `numeric`.<br\>
    `Prefix` and Actual `OTP` will be seperated by a `-`

      |      Type     |  Output  |      Character Types            |
      |---------------|----------|---------------------------------|
      |numeric        |12345678  |Number                           |
      |alphabetic     |ktylnfdgf |Alphabet                         |    
      |alphanumeric   |kt7l7fdg9 |Number,Alphabet                  |    
      |mixnumeric     |!45<45!   |Number,Special Character         |   
      |mixalphabetic  |!tad%hgr  | Alphabet,Special Character      |  
      |mixalphanumeric|!t4<7g!   |Number,Alphabet,Special Character|          


- ### <span id="config_length">Length</span> 
    - The `length` represent total number of character's in generated `OTP` .Default length is ***6***. 

      |Length|      OTP     |
      |------|--------------|
      |   6  |    123456    |
      |   8  |   12345678   |


- ### <span id="config_storage">Storage </span>
    - `Cache Storage` used as default storage to store ***OTP*** .Dont forget to publish ***Otp migration*** file before using `database` as OTP storage.
        #### storage Options:
        - `cache`
        - `session` 
        - `database`


- ### <span id="config_expire">Expire</span> 
    - `expire` - The validity period of the OTP in seconds


- ### <span id="config_case">Case </span>
    - The `case` - differentiating between capital and lower-case letters. 

      |   Case   |      OTP      |
      |----------|---------------|
      |   lower  |   generator   |
      |   upper  |   GENERATOR   |


- ### <span id="config_table">Table Name </table>
    - `table_name` - With the name OTP databse migration table will be created .``otp_table`` is default table name.

<br/> 

<br/>

## 🚀<span id="usage">Usage </span>

<br/>

Import OTP facade

```bash
  use Eagleeye\Otp\Facades\OTP;
```
<br/>

### Static Function : <span id="uses_get">get</span>
```php
//Facade accessor public function
public function get(String $key)
{
         //processing...
}
```

This will generate a OTP that valid till expire time(`configured in config file`),For every otp request the method response new otp string with new expire time:

<br/>

* `$key`: The key that will be tied to the OTP.

#### Example

```php
<?php

$otp = OTP::get('usertoken');

echo $otp;

// 20220317221648
// https://example/url

"267958"
```
<br/>

### Static Function : <span id="uses_interval">interval</span>
```php
//Facade accessor public function
public function interval(String $key)
{
        //processing...
}
```

This will generate a OTP that valid till expire time(`configured in config file`),for new request it will generate new otp if only previous otp with the `$key` expired or empty:

<br/>

* `$key`: The key that will be tied to the OTP.

#### Example if otp hasnt expired

```php
<?php

$otp = OTP::interval('usertoken');

echo $otp;

// 20220317221648
// https://example/url

array:2 [▼
  "expired" => false,
  "remaining" => "00 00:01:49" //Remaining time of expiration
]
```

#### Example if otp expired

```php
<?php

$otp = OTP::interval('usertoken');

echo $otp;

// 20220317221648
// https://example/url

array:2 [▼
  "expired" => true,
  "otp" => "282561", //New otp
  "remaining" => "00 00:01:49" //Remaining time of expiration
]
```
<br/>

### Static Function : <span id="uses_action">action</span>
```php
//Facade accessor public function
public function action(String $key,Callable $callback)
{
     //processing...
}
```

This will generate a OTP that valid till expire time(`configured in config file`),For every otp request the method response new otp string with new expire time. The function takes one extra `callable parameter` to do additional work's(`SMS,EMAIL,etc..`) before returning otp string:

<br/>

* `$key`: The key that will be tied to the OTP.
* `$callback`: Take a callable function . Specially usefull to implement sms gateway's ,Email,etc...

#### Example

```php
<?php

$otp = OTP::action('usertoken',function($otp){
    //Sms::send('+8801*******',$otp);
});

echo $otp;

// 20220317221648
// https://example/url

"267958"
```

<br/>

### Static Function : <span id="uses_intervalaction">intervalaction</span>
```php
//Facade accessor public function
public function intervalaction(String $key,Callable $callback)
{
     //processing...
}
```

Same as Interval the function will generate new otp until previous one expired:

<br/>

* `$key`: The key that will be tied to the OTP.
* `$callback`: Take a callable function . Specially usefull to implement sms gateway's ,Email,etc...

#### Example if otp hasnt expired

```php
<?php

$otp = OTP::intervalaction('usertoken',function($otp){
    //Sms::send('+8801*******',$otp);
});

echo $otp;

// 20220317221648
// https://example/url

array:2 [▼
  "expired" => false
  "remaining" => "00 00:01:49" //Remaining time of expiration
]
```
#### Example if otp expired

```php
<?php

$otp = OTP::intervalaction('usertoken',function($otp){
    //Sms::send('+8801*******',$otp);
});

echo $otp;

// 20220317221648
// https://example/url

array:2 [▼
  "expired" => true
  "otp" => "282561" //New otp
]
```
<br/>

### Static Function : <span id="uses_readonly">Readonly</span>
```php
//Facade accessor public function
public function readonly($options=nullable)
{
     //processing...
}
```

This will return  a random  generated string for other uses.

<br/>

* `$options`: It takes a array as parameter to replace [Configuration](#config) file parameters.

#### Example

```php
<?php

$result=OTP::readonly(['prefix'=>'sn','length'=>15,'case'=>'upper','type'=>'alphabetic']);
echo $result;

// 20220331221403
// http://otp.test/

"sn-ISUQXTFPQYJIMSR"
```

<br/>

<br/><br/>
## Author

---

👤 **Shuvo Dewan**

- Github: [@shuvodewan](https://github.com/shuvodewan)

<br/><br/>

## Contribution

If you find an issue with this package or you have any suggestion please help out.

---

 