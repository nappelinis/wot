<?php

class strat {
	
	const TABLE = 'stratbook';
	
	public $ID = NULL;
	public $name = NULL;
	public $description = NULL;
	public $creator = NULL;
	public $map = NULL;
	public $link = NULL;
	public $stratsketchID = NULL;
	public $type = NULL;
	public $format = NULL;
	public $tier = NULL;
	public $cardinal = NULL;
	
	function __construct($args = array()) {
			
		$this->ID = (isset($args['ID']) ? $args['ID'] : NULL);
		$this->name = (isset($args['name']) ? $args['name'] : NULL);
		$this->description = (isset($args['description']) ? $args['description'] : NULL);
		$this->creator = (isset($args['creator']) ? $args['creator'] : NULL);
		$this->map = (isset($args['map']) ? $args['map'] : NULL);
		$this->link = (isset($args['link']) ? $args['link'] : NULL);
		$this->stratsketchID = (isset($args['stratsketchID']) ? $args['stratsketchID'] : NULL);
		$this->type = (isset($args['type']) ? $args['type'] : NULL);
		$this->format = (isset($args['format']) ? $args['format'] : NULL);
		$this->tier = (isset($args['tier']) ? $args['tier'] : NULL);
		$this->cardinal = (isset($args['cardinal']) ? $args['cardinal'] : NULL);
	}
		
	public function get($ID) {
		
		$sql = 'SELECT * FROM `'.self::TABLE.'` WHERE `ID` = '.$ID;
		$result = WOT::db()->query($sql);
		if($result->num_rows > 0) {
			$object = new strat($result->fetch_assoc());
		}
		else {
			$object = new strat();
		}
		return $object;	
	}
	
	public function get_maps_by_name($name) {
		$sql = 'SELECT * FROM `'.self::TABLE.'` WHERE `map` = "'.$name.'" ORDER BY `tier` DESC';
		$object_array = array();
		
		$result = WOT::db()->query($sql);
		if($result->num_rows > 0) {
			while($map_object = $result->fetch_assoc()) {
				$object_array[] = new strat($map_object);
			}
		}
		else {
			$object_array[] = new strat();
		}
		
		return $object_array;
	}
	
	public function add() {
		
		$sql = 'INSERT INTO `'.self::TABLE.'` (`ID`, `name`, `description`, `creator`, `map`, `link`, `stratsketchID`, `type`, `format`, `tier`, `cardinal`) VALUES '
				. '("'.$this->ID.'", '
				. '"'.$this->name.'", '
				. '"'.$this->description.'", '
				. '"'.$this->creator.'", '
				. '"'.$this->map.'", '
				. '"'.$this->link.'", '
				. '"'.$this->stratsketchID.'", '
				. '"'.$this->type.'", '
				. '"'.$this->format.'", '
				. '"'.$this->tier.'", '
				. '"'.$this->cardinal.'")';
		
		//pre($sql);
		//exit();
		
		WOT::db()->query($sql);
	}
	
	public function update() {
	
		$sql = 'UPDATE `'.self::TABLE.'` '
				. 'SET '
					. '`name` = "'.$this->name.'", '
					. '`description` = "'.$this->description.'", '
					. '`creator` = "'.$this->creator.'", '
					. '`map` = "'.$this->map.'", '
					. '`link` = "'.$this->link.'", '
					. '`stratsketchID` = "'.$this->stratsketchID.'", '
					. '`type` = "'.$this->type.'", '
					. '`format` = "'.$this->format.'", '
					. '`tier` = "'.$this->tier.'", '
					. '`cardinal` = "'.$this->cardinal.'" '
				. 'WHERE `ID` = "'.$this->ID.'"';	
		
		WOT::db()->query($sql);
	}
	
	public function delete() {
		
	}
}