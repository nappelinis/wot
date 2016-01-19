<?php

class tank {
	
	public $ID = NULL;
	public $name = NULL;
	public $level = NULL;
	public $type = NULL;
	public $premium = NULL;
	public $contour_image = NULL;
	public $image = NULL;
	public $image_small = NULL;
	
	
	function __construct($args = array()) {
			
		$this->ID = (isset($args['ID']) ? $args['ID'] : NULL);
		$this->name = (isset($args['name']) ? $args['name'] : NULL);
		$this->level = (isset($args['level']) ? $args['level'] : NULL);
		$this->type = (isset($args['type']) ? $args['type'] : NULL);
		$this->premium = (isset($args['premium']) ? $args['premium'] : NULL);
		$this->contour_image = (isset($args['contour_image']) ? $args['contour_image'] : NULL);
		$this->image = (isset($args['image']) ? $args['image'] : NULL);
		$this->image_small = (isset($args['image_small']) ? $args['image_small'] : NULL);
	}
	
	
	public function get($ID) {
		
		$sql = 'SELECT * FROM `tankList` WHERE `ID` = '.$ID;
		
		$result = WOT::db()->query($sql);
		if($result->num_rows > 0) {
			
			$tank = new tank($result->fetch_assoc());
		}
		else {
			$tank = new tank();
		}
		
		return $tank;
		
		
	}
	
	public function add() {
		
		$sql = 'INSERT INTO `tankList` (`ID`, `name`, `level`, `type`, `premium`, `contour_image`, `image`, `image_small`) VALUES '
				. '("'.$this->ID.'", '
				. '"'.$this->name.'", '
				. '"'.$this->level.'", '
				. '"'.$this->type.'", '
				. '"'.$this->premium.'", '
				. '"'.$this->contour_image.'", '
				. '"'.$this->image.'", '
				. '"'.$this->image_small.'")';
		//pre($sql);
		WOT::db()->query($sql);
	}
	
	public function update() {
	
		$sql = 'UPDATE `tankList` '
				. 'SET '
					. '`name` = "'.$this->name.'", '
					. '`level` = "'.$this->level.'", '
					. '`type` = "'.$this->type.'", '
					. '`premium` = "'.$this->premium.'", '
					. '`contour_image` = "'.$this->contour_image.'", '
					. '`image` = "'.$this->image.'", '
					. '`image_small` = "'.$this->image_small.'" '
				. 'WHERE `ID` = "'.$this->ID.'"';	
		
		//pre($sql);
		WOT::db()->query($sql);
	}
	
	public function delete() {
		
	}
}