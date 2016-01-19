<?php

class base_handerl {

	public function __construct() {
		
	}
	
	protected $table = 'set by handlers';
	protected $object = 'set by handlers';
	protected $primary_key = 'ID';
	protected $has_ID = true;
	protected $field_labels = array();
	protected $auto_increment = true;
	protected $last_query = '';
	
	public function add(&$obj) {
		if(!is_object($obj)) {
			throw new Exception('Parameter must be an object');
		}
		
		$allvars = $obj->all_vars();
		
		if($this->auto_incremnt && isset($allvars[$this->primary_key])) {
			unset($allvars[$this->primary_key]);
		}
		
		$fields = implode("`, `", array_keys($allvars));
		$values = implode(", ", array_map(function($a) { return '?' ; }, $allvars));
		
		try {
			$result = $this->db->insert("INSERT INTO `" .$this->table ."` (`" . $fields . "`) VALUES (" . $values . ");", array_values($allvars));
		} catch (Exception $ex) {
			throw new Exception('Base insert failed on '.get_class($obj));
		}
		
		$pkey = $this->primary_key;
		if($this->has_ID) {
			if(!$result) {
				throw new Exception('Failed to insert item into table '.$this->table.': '.mysql_error());
			}
			
			$obj->$pkey = $result;
			return $result;
		}
		else {
			return true;
		}
	}
	
	
	
	
	
	
}


