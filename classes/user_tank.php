<?php

class user_tank {
	
	public $accountID = NULL;
	public $tankID = NULL;
	public $level = NULL;
	
	function __construct($args = array()) {
			
		$this->accountID = (isset($args['accountID']) ? $args['accountID'] : NULL);
		$this->tankID = (isset($args['tankID']) ? $args['tankID'] : NULL);
		$this->level = (isset($args['level']) ? $args['level'] : NULL);
	}
	
	
	public function get($accountID, $tankID = NULL, $level = NULL) {
		
		$sql = 'SELECT * FROM `solid_users_tanks` WHERE `accountID` = '.$accountID;
		
		if($tankID) {
			$sql .= ' AND `tankID`='.$tankID;
		}
		
		if($level) {
			$sql .= ' AND `level`='.$level;
		}
		
		$result = WOT::db()->query($sql);
		if($result->num_rows > 0) {
			while($tank = $result->fetch_assoc()) {
				$user_tanks[] = new user_tank($tank);
			}
		}
		else {
			$user_tanks[] = new user_tank();
		}
		
		return $user_tanks;
		
		
	}
	
	public function add() {
		
		$sql = 'INSERT INTO `solid_users_tanks` (`accountID`, `tankID`, `level`) VALUES '
				. '("'.$this->accountID.'", '
				. '"'.$this->tankID.'", '
				. '"'.$this->level.'")';
		WOT::db()->query($sql);
	}
	
	public function update() {
	
		$sql = 'UPDATE `solid_users_tanks` '
				. 'SET '
					. '`level` = "'.$this->level.'", '
				. 'WHERE `accountID` = "'.$this->accountID.'" AND `tankID` = "'.$this->tankID.'"';	
		WOT::db()->query($sql);
	}
	
	public function delete() {
		
	}
}