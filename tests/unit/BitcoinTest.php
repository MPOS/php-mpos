<?php

require_once(CLASS_DIR . '/bitcoin.class.php');

class BitcoinTest extends \Codeception\TestCase\Test
{
   
	public function _before()
	{

	}

	public function _after()
	{
		
	}

    public function testAddressToHash160()
    {
    	$bitcoin = new Bitcoin();
    	$this->assertEquals('82839EADFB204C8D22ED122A0868C6F52B5BDFC9',$bitcoin->addressToHash160('1Cu6X3c716CCKU3Bi2jfHv8kZ2QCor8uXm'));
    }
}