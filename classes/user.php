<?php

class user extends base_object {
	
	public $ID = NULL;
	public $nickname = NULL;
	public $accountID = NULL;
	public $access_token = NULL;
	public $expires_at = NULL;
	public $username = NULL;
	public $password = NULL;
	
	private static $instance = NULL;
	
	public static function instance() {
		if(!self::$instance)
			self::$instance = new user();
		
		return self::$instance;
	}
	
	public function __construct($args = array()) {
			
		parent::__constrct('user_handler', $args);
		
		$this->ID = (isset($args['ID']) ? $args['ID'] : NULL);
		$this->nickname = (isset($args['nickname']) ? $args['nickname'] : NULL);
		$this->accountID = (isset($args['accountID']) ? $args['accountID'] : NULL);
		$this->access_token = (isset($args['access_token']) ? $args['access_token'] : NULL);
		$this->expires_at = (isset($args['expires_at']) ? $args['expires_at'] : NULL);
		$this->username = (isset($args['username']) ? $args['username'] : NULL);
		$this->password = (isset($args['password']) ? $args['password'] : NULL);
	}
	
	
	public function get_all() {
		$users = array();
		$sql = 'SELECT * FROM `solid_users`';
		
		$result = WOT::db()->query($sql);
		while($user_entry = $result->fetch_assoc()) {
			$users[] = new user($user_entry);
		}
		
		return $users;
	}
	
	
	public function get($accountID) {
		
		$sql = 'SELECT * FROM `solid_users` WHERE `accountID` = '.$accountID;
		
		$result = WOT::db()->query($sql);
		if($result->num_rows > 0) {
			
			$user = new user($result->fetch_assoc());
		}
		else {
			$user = new user();
		}
		
		return $user;
		
		
	}
	
	public function add() {
		
		$sql = 'INSERT INTO `solid_users` (`nickname`, `accountID`, `access_token`, `expires_at`, `username`, `password`) VALUES '
				. '("'.$this->nickname.'", '
				. '"'.$this->accountID.'", '
				. '"'.$this->access_token.'", '
				. '"'.$this->expires_at.'", '
				. '"'.$this->nickname.'", '
				. '"'.$this->nickname.'")';
		WOT::db()->query($sql);
	}
	
	public function update() {
	
		$sql = 'UPDATE `solid_users` '
				. 'SET '
					. '`nickname` = "'.$this->nickname.'", '
					. '`accountID` = "'.$this->accountID.'", '
					. '`access_token` = "'.$this->access_token.'", '
					. '`expires_at` = "'.$this->expires_at.'", '
					. '`username` = "'.$this->nickname.'", '
					. '`password` = "'.$this->nickname.'"'
				. 'WHERE `accountID` = "'.$this->accountID.'"';	
		WOT::db()->query($sql);
	}
	
	public function delete() {
		//Delete users tanks
		$delete_users_tanks_sql = 'DELETE FROM `solid_users_tanks WHERE `accountID` = '.$this->accountID;
		WOT::db()->query($delete_users_tanks_sql);
		
		//Delete user
		$delete_user_sql = 'DELETE FROM `solid_users` WHERE `accountID` = '.$this->accountID;
		WOT::db()->query($delete_user_sql);
	}
	
	
	
}

