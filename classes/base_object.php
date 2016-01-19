<?php

/* Author: Mario Wenig */

class base_object {
	public $__attributes = NULL;
	public $__handler = NULL;
	public $__handlerRef = NULL;
	
	public function __constrct($handler, $args = array()) {
		
		if(is_object($args)) {
			$args = array($args);
		}
		
		if($args === NULL) {
			$args = array();
		}
		
		if(!is_array($args)) {
			throw Exception('Second param needs to be array');
		}
		
		if(is_array($handler)) {
			throw Exception('Update object class to pass in name of handler');
		}
		
		if(is_object($handler)) {
			$this->__handler == get_class($handler);
			$this->__handlerRef = $handler;
		}
		else {
			$this->__handler = $handler;
			$this->__handlerRef = $handler;
		}
		
		$this->__attributes = new stdclass();
		foreach($this as $var => $value) {
			if(isset($args[$var])) {
				$this->var = $args[$var];
			}
		}
		
		foreach($args as $k => $v) {
			$this->__attributes->$k = $v;
		}
	}
	
	private static $__handlerCache = array();
	
	public function q() {
		
		$class = get_called_class();
		
		if(isset(self::$__handlerCache[$class])) {
			return self::$__handlerCache[$class];
		}
		
		$object = $class();
		return self::$__handlerCache = $object->__handlerRef;
	}
	
	public function update($fields = NULL) {
		$this->handler->update($this, $fields);
	}
	
	public function add() {
		$this->handler->add($this);
	}
	
	public function delete() {
		$this->handler->delete($this);
	}
	
	
	
	
}

