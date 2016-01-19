<?php

class recruit {
	
	const TABLE = 'solid_recruitment';
	
	public $ID = NULL;
	public $username = NULL;
	public $accountID = NULL;
	public $source = NULL;
	public $wn8 = NULL;
	public $recent_wn8 = NULL;
	public $tierten = NULL;
	public $tierten_names = NULL;
	public $tiereight = NULL;
	public $tiereight_names = NULL;
	public $tiersix = NULL;
	public $tiersix_names = NULL;
	public $status = NULL;
	public $comments = NULL;
	public $recruiter = NULL;
	public $created = NULL;
	public $updated = NULL;
	public $archive = NULL;
	
	function __construct($args = array()) {
			
		$this->ID = (isset($args['ID']) ? $args['ID'] : NULL);
		$this->username = (isset($args['username']) ? $args['username'] : NULL);
		$this->accountID = (isset($args['accountID']) ? $args['accountID'] : NULL);
		$this->source = (isset($args['source']) ? $args['source'] : NULL);
		$this->wn8 = (isset($args['wn8']) ? $args['wn8'] : NULL);
		$this->recent_wn8 = (isset($args['recent_wn8']) ? $args['recent_wn8'] : NULL);
		$this->tierten = (isset($args['tierten']) ? $args['tierten'] : NULL);
		$this->tierten_names = (isset($args['tierten_names']) ? $args['tierten_names'] : NULL);
		$this->tiereight = (isset($args['tiereight']) ? $args['tiereight'] : NULL);
		$this->tiereight_names = (isset($args['tiereight_names']) ? $args['tiereight_names'] : NULL);
		$this->tiersix = (isset($args['tiersix']) ? $args['tiersix'] : NULL);
		$this->tiersix_names = (isset($args['tiersix_names']) ? $args['tiersix_names'] : NULL);
		$this->status = (isset($args['status']) ? $args['status'] : NULL);
		$this->comments = (isset($args['comments']) ? $args['comments'] : NULL);
		$this->recruiter = (isset($args['recruiter']) ? $args['recruiter'] : NULL);
		$this->created = (isset($args['created']) ? $args['created'] : NULL);
		$this->updated = (isset($args['updated']) ? $args['updated'] : NULL);
		$this->archive = (isset($args['archive']) ? $args['archive'] : NULL);
	}
	
	public function get_recruits($archive = 0) {
		
		$recruits = array();
		
		$sql = 'SELECT * FROM `'.self::TABLE.'`';
		$where = '';
		$order = 'ORDER BY `ID` DESC';
		
		if($archive == 0 || $archive == 1) {
			$where = ' WHERE `archive`='.$archive.' ';
		}
		else
			$where = '';
		
		$sql .= $where.$order;
				
		$result = WOT::db()->query($sql);
		while($recruit_result = $result->fetch_assoc()) {
			$recruits[] = new recruit($recruit_result);	
		}
		return $recruits;	
	}
	

	
	public function get($ID) {
		
		$sql = 'SELECT * FROM `'.self::TABLE.'` WHERE `ID` = '.$ID;
		$result = WOT::db()->query($sql);
		if($result->num_rows > 0) {
			$item = new recruit($result->fetch_assoc());
		}
		else {
			$item = new recruit();
		}	
		return $item;		
	}
	
	public function add() {
		
		$sql = 'INSERT INTO `'.self::TABLE.'` (`ID`, `username`, `accountID`, `source`, `wn8`, `recent_wn8`, `tierten`, `tierten_names`, `tiereight`, '
				. '`tiereight_names`, `tiersix`, `tiersix_names`, `status`, `comments`, `recruiter`, `created`, `updated`, `archive`) VALUES '
				. '("'.$this->ID.'", '
				. '"'.$this->username.'", '
				. '"'.$this->accountID.'", '
				. '"'.$this->source.'", '
				. '"'.$this->wn8.'", '
				. '"'.$this->recent_wn8.'", '
				. '"'.$this->tierten.'", '
				. '"'.$this->tierten_names.', '
				. '"'.$this->tiereight.', '
				. '"'.$this->tiereight_names.', '
				. '"'.$this->tiersix.', '
				. '"'.$this->tiersix_names.', '
				. '"'.$this->status.', '
				. '"'.$this->comments.', '
				. '"'.$this->created.', '
				. '"'.$this->updated.', '
				. '"'.$this->archive.'")';
		WOT::db()->query($sql);
	}
	
	public function update() {
	
		$sql = 'UPDATE `'.self::TABLE.'` '
				. 'SET '
					. '`username` = "'.$this->username.'", '
					. '`accountID` = "'.$this->accountID.'", '
					. '`source` = "'.$this->source.'", '
					. '`wn8` = "'.$this->wn8.'", '
					. '`recent_wn8` = "'.$this->recent_wn8.'", '
					. '`tierten` = "'.$this->tierten.'", '
					. '`tierten_names` = "'.$this->tierten_names.'" '
					. '`tiereight` = "'.$this->tiereight.'" '
					. '`tiereight_names` = "'.$this->tiereight_names.'" '
					. '`tiersix` = "'.$this->tiersix.'" '
					. '`tiersix_names` = "'.$this->tiersix_names.'" '
					. '`status` = "'.$this->status.'" '
					. '`comments` = "'.$this->comments.'" '
					. '`created` = "'.$this->created.'" '
					. '`updated` = "'.$this->updated.'" '
					. '`archive` = "'.$this->archive.'" '
				. 'WHERE `ID` = "'.$this->ID.'"';	
		WOT::db()->query($sql);
	}
	
	public function delete() {
		
	}
}