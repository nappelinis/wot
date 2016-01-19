<?php

class UserTest extends PHPUnit_Framework_TestCase
{
	
	
	/**
	 * @param string $keyIdentifier to identify against
	 * @param string $expectedResult What we expect
	 * 
	 * @dataProvider providerTestConstructor
	 */
	public function testConstructor($keyIdentifier, $expectedResult) {
		$user = new user(array($keyIdentifier => $expectedResult));
		$this->assertEquals($expectedResult, $user->$keyIdentifier);
	}
	
	
	
	public function providerTestConstructor() {
		
		return array(
			array('ID', 1),
			array('nickname', 'Nickname'),
			array('accountID', 1234),
			array('expires_at', '10-10-2010'),
			array('username', 'Username'),
			array('password', 'Password')
		);
	}
	
	
	public function testInstance() {
		$user = user::instance();
		$this->assertInstanceOf('user', $user);
	}
	
	public function testGet() {
		
		$ID = WOT::NAPP;
		$user_obj = user::instance();
		$user = $user_obj->get($ID);
		$this->assertEquals($ID, $user->accountID);
		
		
	}
}