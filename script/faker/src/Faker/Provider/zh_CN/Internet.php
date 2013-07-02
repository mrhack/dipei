<?php

namespace Faker\Provider\zh_CN;

class Internet extends \Faker\Provider\Internet
{

    public function __construct($generator)
    {
        $this->generator = \Faker\Factory::create('en_US');

        self::$safeEmailTld = array('org', 'com', 'net');
        self::$freeEmailDomain = array('gmail.com', 'yahoo.com', 'hotmail.com', 'sina.com', 'sohu.com', '163.com');
        self::$tld = array('com', 'com', 'com', 'com', 'com', 'com', 'biz', 'info', 'net', 'org');
        self::$userNameFormats = array(
            '{{lastName}}{{firstName}}',
            '{{firstName}}####',
            '?{{lastName}}',
        );
        self::$emailFormats = array(
            '{{userName}}@{{domainName}}',
            '{{userName}}@{{freeEmailDomain}}',
        );
        self::$urlFormats = array(
            'http://www.{{domainName}}/',
            'http://{{domainName}}/',
        );
    }

}