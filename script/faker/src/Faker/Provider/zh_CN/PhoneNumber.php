<?php

namespace Faker\Provider\zh_CN;

class PhoneNumber extends \Faker\Provider\PhoneNumber
{
	protected static $formats = array(
		'(0###)-########',
		'139########',
        '152########',
        '138########',
        '189########',
	);
}