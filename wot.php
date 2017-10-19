<?php

//$accountID = WOT::getPlayerID('Nappelinis');
//var_dump($accountID);

//$tankList = WOT::getTanksList();
//var_dump($tankList);

//$playerTanks = WOT::getPlayerTanks($accountID);

//var_dump(WOT::getPlayerTierTanks(10, $playerTanks, $tankList));

//Used to populate/add tanks to tankList table
//WOT::populateTankList();

class WOT {
	
	const APPID = '';
	const CLANID = '';
	const NAPP = '';
	const NAPP_ACCESS_TOKEN = '';
	const NAPP_T = '10257';
	const TANKLIST = NULL;
	
	//DB
	const HOST = '';
	const USER = '';
	const PASS = '';
	const DATABASE = '';
	
	//DB connection
	public function db() {
		$db = new mysqli(self::HOST, self::USER, self::PASS, self::DATABASE);
		if($db->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}
	
	return $db;
	}
	
	public function getUser($userID) {
		$db = self::db();
		$query = 'SELECT * FROM `solid_recruitment` WHERE `ID` = '.$userID;
		$result = $db->query($query);
		
		if($result->num_rows > 0) {
			$user = (object)$result->fetch_assoc();
		}
		else
			$user = NULL;
		
		return $user;
	}
	
	public function colorCoding($type, $value) {
		if($type == 'winrate') {
			if($value >= 65) return 'wr_super_unicum';
			else if($value >= 60 && $value < 65) return 'wr_unicum';
			else if($value >= 56 && $value < 60) return 'wr_great';
			else if($value >= 54 && $value < 56) return 'wr_very_good';
			else if($value >= 52 && $value < 54) return 'wr_good';
			else if($value >= 50 && $value < 52) return 'wr_above_average';
			else if($value >= 48 && $value < 50) return 'wr_average';
			else if($value >= 47 && $value < 48) return 'wr_below_average';
			else if($value >= 46 && $value < 47) return 'wr_basic';
			else return 'wr_beginner';
		}
		else if($type == 'fragsperbattle') {
			if($value >= 2) return 'fpb_unique';
			else if($value >= 1.3 && $value < 2) return 'fpb_very_good';
			else if($value >= 1.0 && $value < 1.3) return 'fpb_good';
			else if($value >= 0.8 && $value < 1.0) return 'fpb_normal';
			else if($value >= 0.6 && $value < 0.8) return 'fpb_bad';
			else return 'fpb_very_bad';
		}
		else if($type == 'wn8') {
			if($value >= 2900) return 'wn8_super_unicum';
			else if($value >=  2450 && $value <= 2899) return 'wn8_unicum';
			else if($value >= 2000 && $value <= 2449) return 'wn8_great';
			else if($value >= 1600 && $value <= 1999) return 'wn8_very_good';
			else if($value >= 1200 && $value <= 1599) return 'wn8_good';
			else if($value >= 900 && $value <= 1199) return 'wn8_above_average';
			else if($value >= 650 && $value <= 899) return 'wn8_average';
			else if($value >= 450 && $value <= 649) return 'wn8_below_average';
			else if($value >= 300 && $value <= 449) return 'wn8_bad';
			else if($value < 300) return 'wn8_very_bad';
		}
		else if($type == 'status') {
			if($value == 'Member') return 'member';
			else if($value == 'Clan denied' || $value == 'User denied') return 'denied';
			else if($value == 'Clan accepted' || $value == 'User accepted') return 'accepted';
			else if($value == 'New' || $value == 'Waiting on response') return 'new';
			else if($value == 'Joined Other') return 'joined_other';
		}
		else
			return '';

	}
	
	
	
	public function getPersonalData($account_ID) {
		
		$uri = 'api.worldoftanks.com/wot/account/info/';
		$uri .= '?application_id='.self::APPID.'&account_id='.$account_ID;
		
		$response = self::myCurl($uri);
		
		return $response;
	}
	
	public function getClanMembers($rank = '', $clanID = NULL, $raw = false) {
		
		$selectedClanID = '';
		if(!empty($clanID)) $selectedClanID = $clanID;
		else $selectedClanID = self::CLANID;
		
		$uri = 'api.worldoftanks.com/wgn/clans/info/';
		$uri .= '?application_id='.self::APPID.'&clan_id='.$selectedClanID;
		
		$response = self::myCurl($uri);
		
		$clanMembers = array();
		$clanRecruits = array();
		$clanPrivates = array();
		$clanJuniorOfficers = array();
		$clanRecruitmentOfficers = array();
		$clanPersonnelOfficers = array();
		$clanCombatOfficers = array();
		$clanExecutiveOfficers = array();
		$clanCommander = array();
		
		$clanMembersRaw = $response['data'][$selectedClanID]['members'];
				
		foreach($clanMembersRaw as $index => $clanMember) {
			
			$clanMember = (object)$clanMember;
			//var_dump($clanMember);
			switch($clanMember->role_i18n) {
				
				case 'Recruit':
					$clanRecruits[$clanMember->account_id] = $clanMember->account_name;
					break;
				case 'Private':
					$clanPrivates[$clanMember->account_id] = $clanMember->account_name;
					break;
				case 'Junior Officer':
					$clanJuniorOfficers[$clanMember->account_id] = $clanMember->account_name;
					break;
				case 'Recruitment Officer':
					$clanRecruitmentOfficers[$clanMember->account_id] = $clanMember->account_name;
					break;
				case 'Personnel Officer':
					$clanPersonnelOfficers[$clanMember->account_id] = $clanMember->account_name;
					break;
				case 'Combat Officer':
					$clanCombatOfficers[$clanMember->account_id] = $clanMember->account_name;
					break;
				case 'Executive Officer':
					$clanExecutiveOfficers[$clanMember->account_id] = $clanMember->account_name;
					break;
				case 'Commander':
					$clanCommander[$clanMember->account_id] = $clanMember->account_name;
					break;
			}
		}
		
		/*
		 * overwrites account_id
		$sortBy = SORT_FLAG_CASE;
		sort($clanCommander, $sortBy);
		sort($clanExecutiveOfficers, $sortBy);
		sort($clanCombatOfficers, $sortBy);
		sort($clanPersonnelOfficers, $sortBy);
		sort($clanRecruitmentOfficers, $sortBy);
		sort($clanJuniorOfficers, $sortBy);
		sort($clanPrivates, $sortBy);
		sort($clanRecruits, $sortBy);
		 */
		if(!empty($rank)) {
			switch($rank) {
				case 'Commander': return $clanCommander; break;
				case 'Executive Officer': return $clanExecutiveOfficers; break;
				case 'Combat Officer': return $clanCombatOfficers; break;
				case 'Personnel Officer': return $clanPersonnelOfficers; break;
				case 'Recruitment Officer': return $clanRecruitmentOfficers; break;
				case 'Junior Officer': return $clanJuniorOfficers; break;
				case 'Private': return $clanPrivates; break;
				case 'Recruit': return $clanRecruits; break;
			}
		}	
		
		$clanMembers = $clanCommander + $clanExecutiveOfficers + $clanCombatOfficers + $clanPersonnelOfficers + $clanRecruitmentOfficers + $clanJuniorOfficers +$clanPrivates +$clanRecruits;
			
		return $clanMembers;		
	}
	
	
	
	public function updateTanks($userID, $accountID = null, $return = false) {
		
		$user = NULL;
		
		if(!empty($accountID) || is_numeric($accountID)) {
			$user = (object)array('accountID' => $accountID);
		}
		
		if(is_numeric($userID)) {
			$user = self::getUser($userID);
		}
			
		if($user != NULL) {
			//Tierten post fix
			$player_tierten_count = (isset($user->tierten) ? $user->tierten : '');
			$player_tierten_string = (isset($user->tierten_names) ? $user->tierten_names : '');
			if(empty($player_tierten_string)) {
				$player_tierten = WOT::getPlayerTierTanks(10, WOT::getPlayerTanks($user->accountID));
				$player_tierten_string = implode(', ', $player_tierten);
				$player_tierten_count = count($player_tierten);
			}

			//Tiereight post fix
			$player_tiereight_count = (isset($user->tiereight) ? $user->tiereight : '');
			$player_tiereight_string = (isset($user->tiereight_names) ? $user->tiereight : '');
			if(empty($player_tiereight_string)) {
				$player_tiereight = WOT::getPlayerTierTanks(8, WOT::getPlayerTanks($user->accountID));
				$player_tiereight_string = implode(', ', $player_tiereight);
				$player_tiereight_count = count($player_tiereight);
			}

			//Tiersixpost fix
			$player_tiersix_count = (isset($user->tiersix) ? $user->tiersix : '');
			$player_tiersix_string = (isset($user->tiersix_names) ? $user->tiersix_names : '');
			if(empty($player_tiersix_string)) {
				$player_tiersix = WOT::getPlayerTierTanks(6, WOT::getPlayerTanks($user->accountID));
				$player_tiersix_string = implode(', ', $player_tiersix);
				$player_tiersix_count = count($player_tiersix);
			}
		}
		
		if(!$return) { //update
			$update_query = 'UPDATE `solid_recruitment` SET `tierten` = '.$player_tierten_count.', '
					. '`tiereight` = '.$player_tiereight_count.', '
					. '`tiersix` = '.$player_tiersix_count.','
					. '`tierten_names` = '.$player_tierten_string.','
					. '`tiereight_names` = '.$player_tiereight_string.','
					. '`tiersix_names` = '.$player_tiersix_string.' WHERE `ID` = '.$user->ID;
			self::db()->query($update_query);
		}
		else {
			return array('tierten' => $player_tierten_count, 
				'tiereight' => $player_tiereight_count, 
				'tiersix' => $player_tiersix_count, 
				'tierten_names' => $player_tierten_string, 
				'tiereight_names' => $player_tiereight_string, 
				'tiersix_names' => $player_tiersix_string);
		}
		
	}
	
	
	public function scrapeWotlabsTanks($userName) {
		if(!empty($userName)) {
			$user = (object)array('username' => $userName);
		}
		
		$url = 'http://www.wotlabs.net/na/player/'.$user->username;
		$wotlabs_data = WOT::myCurl($url, false);
		
		//doesnt work
		preg_match("#<table\sid=\"tankList\*</table>#", $wotlabs_data, $tanksTable);
		
		
		$return = $tanksTable;
		return $return;
		
		
		
	}
	
	
	public function updateWN8($userID, $userName = null, $return = false) {
		
		if(!empty($userName)) {
			$user = (object)array('username' => $userName);
		}
	
		if(is_numeric($userID)) {
			$user = self::getUser($userID);
		}
		
		if($user != NULL) {
					
			//Scrape wotlabs for wn8/recent wn8
			$url = 'http://www.wotlabs.net/na/player/'.$user->username;
			$wotlabs_data = WOT::myCurl($url, false);
			//var_dump($wotlabs_data);

			preg_match("#>WN8</span>*\s*\d*\s</div>#", $wotlabs_data, $wn8_matches);
			if(count($wn8_matches) == 1) {
				$wn8 = preg_replace('#\s#', '', $wn8_matches[0]);
				$wn8 = preg_replace('#>WN8</span>#', '', $wn8);
				$wn8 = preg_replace('#</div>#', '', $wn8);
				$wn8 = (int)$wn8;
				//var_dump($wn8);
			}
			
			preg_match("#>Recent WN8</span>*\s*\d*\s</div>#", $wotlabs_data, $recent_wn8_matches);
			if(count($recent_wn8_matches) == 1) {
				$rwn8 = preg_replace('#\s#', '', $recent_wn8_matches[0]);
				$rwn8 = preg_replace('#>RecentWN8</span>#', '', $rwn8);
				$rwn8 = preg_replace('#</div>#', '', $rwn8);
				$rwn8 = (int)$rwn8;
				//var_dump($rwn8);
			}
			
			if(!$return) {
				$update_query = 'UPDATE `solid_recruitment` SET `wn8`='.$wn8.', `recent_wn8`='.$rwn8.' WHERE `ID` = '. $user->ID;
				self::db()->query($update_query);
			}
			else {
				return array('wn8' => $wn8, 'rwn8' => $rwn8);
			}
		}
	}
	
	
	public function getRecruiters() {
		return array(0 => 'Nappelinis', 5 => 'Looxgood', 6 => 'Xanxyma', 7 => 'Sylvester053', 8 => 'Titan North', 9 => 'Markhamwaxers', 10 => 'Goodsnpr', 11 => 'Man_Up_W_Gold_Bond');
	}
	
	public function getStatuses() {
		return array(0 => 'New', 1 => 'Waiting on response', 2 => 'User denied', 3 => 'Clan denied', 4 => 'User accepted', 5 => 'Clan accepted', 6 => 'Member', 7 => 'Joined Other', 8 => 'No response');
	}
	
	public function getSources() {
		return array(0 => 'In-Game', 1 => 'Recruitment Station', 2 => 'Friend', 9 => 'Other');
	}
	
	public function getGameModes($input = NULL) {
		$modes = array(0 => 'Standard', 1 => 'Encounter', 2 => 'Assault', 3 => 'Att/Def');
		if($input != NULL) return $modes[$input];
		return $modes;
	}
	
	public function getTiers($input = NULL) {
		$tiers = array(10 => '10', 9 => '9', 8 => '8', 7 => '7', 6 => '6', 5 => '5', 4 => '4', 3 => '3', 2 => '2', 1 => '1');
		if(!empty($input)) return $tiers[$input];
		return $tiers;
	}
	
	public function getCardinal($input = NULL) {
		$cardinals = array(0 => 'North', 1 => 'South', 2 => 'Both');
		if($input != NULL) return $cardinals[$input];
		return $cardinals;
	}
	
	//Populates and updates tank list table
	public function populateTankList() {
			
		$added = 0;
		$updated = 0;
		
		$tankList = self::getTanksList();	
		foreach($tankList as $tankID => $tank_item) {
			
			$tank = new tank();
			$tank = $tank->get($tankID);
				
			//Check if exists
			if(empty($tank->ID)) { //INSERT
				
				$tank->ID = $tankID;
				$tank->name = $tank_item['short_name_i18n'];
				$tank->level = $tank_item['level'];
				$tank->type = $tank_item['type_i18n'];
				$tank->premium = ($tank_item['is_premium'] == true ? 1 : 0);
				$tank->contour_image = $tank_item['contour_image'];
				$tank->image = $tank_item['image'];
				$tank->image_small = $tank_item['image_small'];
				$tank->add();
				$added++;
			}
			else { //Update
				
				$tank->name = $tank_item['short_name_i18n'];
				$tank->level = $tank_item['level'];
				$tank->type = $tank_item['type_i18n'];
				$tank->premium = ($tank_item['is_premium'] == true ? 1 : 0);
				$tank->contour_image = $tank_item['contour_image'];
				$tank->image = $tank_item['image'];
				$tank->image_small = $tank_item['image_small'];
				$tank->update();
				$updated++;
			}
		}
		
		echo 'Checked '.count($tankList).' Added: '.$added.' Updated: '.$updated.'<br />';
	}
	
	public function updateRegisteredUsersTanks($user) {
	
		$playerTanks = self::getPlayerTanks($user->accountID, $user->access_token);
		
		if($playerTanks) {
			foreach($playerTanks as $playerTank) {
				$user_tank = new user_tank();
				$tank = new tank();
				//check existing
				$user_tank = $user_tank->get($user->accountID, $playerTank['tank_id']);
				
				//index 0
				$user_tank = $user_tank[0];
				$tank = $tank->get($playerTank['tank_id']);
				
				if(empty($user_tank->accountID)) {
					$user_tank->accountID = $user->accountID;
					$user_tank->tankID = $playerTank['tank_id'];
					$user_tank->level = $tank->level;
					$user_tank->add();
				} 
				else {
					//pre('Tank '.$user_tank->tankID.' already exists for '.$user->nickname.' with '.$user->accountID);
					continue;
				}
			}
		}
		else {
			pre('updateRegisteredUsersTanks for '.$user->nickname.' failed!');
		}
		
	}
	
	/* CURL HELPER FUNCTION */
	public function myCurl($uri, $json = true, $data = NULL) {
		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		if($data) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		
		$response = curl_exec($ch);	
		if($json) {
			$response = json_decode($response, true);
		}
		return $response;
	}
	
	
	
	public function getMaps() {
		$uri = "api.worldoftanks.com/wot/encyclopedia/arenas/";
		$uri .= "?application_id=".self::APPID;
		
		$response = self::myCurl($uri);
		
		$maps = $response['data'];
		
		//Added some sanitatin (preventing ') within map strings (ex: Fisherman's Bay)
		foreach($maps as $arena_id => $map) {
			$maps[$arena_id]['name_i18n'] = str_replace("'", "", $map['name_i18n']);
		}
		return $maps;
	}
	
	/*
	 * Uses string username to get wot account_id
	 * @param string playerName
	 * @return int accountID
	 */
	public function getPlayerID($playerName) {

		$uri = "api.worldoftanks.com/wot/account/list/";
		$uri .= "?application_id=".self::APPID."&search=".$playerName;

		$response = self::myCurl($uri);
		return $response['data'][0]['account_id'];
	}
	
	
	public function getPlayerTanks($accountID, $access_token = NULL) {
		
		$user = new user();
		$user = $user->get($accountID);
		
		$today = time();
		
		$uri = "api.worldoftanks.com/wot/account/tanks/";
		$uri .= "?application_id=".wot::APPID."&account_id=".$accountID.(isset($access_token) && $today < $user->expires_at ? '&access_token='.$access_token : '');
		
		//pre($uri);
		
		$response = self::myCurl($uri);
		return $response['data'][$accountID];
	}
	
	public function getTanksList() {
		
		$uri = "api.worldoftanks.com/wot/encyclopedia/tanks/";
		$uri .= "?application_id=".self::APPID;
		
		$response = self::myCurl($uri);
		return $response['data'];
		
	}
	
	public function getEncyclopediaVehicles($tankID) {
		$uri = 'https://api.worldoftanks.com/wot/encyclopedia/vehicles/?application_id='.self::APPID.'&tank_id='.$tankID;
		$response = self::myCurl($uri);
		return $response['data'][$tankID];
	}
	
	
	public function getTank($tankID) {
	
		$db = self::db();
		$query = "SELECT * FROM `tankList` WHERE `ID`=".$tankID;
		$result = $db->query($query);
		$return = (object)$result->fetch_assoc();
		return $return;
	}
	
	public function getPlayerTierTanks($tier, $playerTanksList) {
		$tier_array = array();
		foreach($playerTanksList as $playerTank) {
			
			$initialTank = $playerTank;
			
			//Overwrite to convert from short list to actutal tank listing
			$playerTank = self::getTank($playerTank['tank_id']);
			if(!isset($playerTank->level)) {
				pre('Maintenance required (tankList out of date)');
			}
			else {
				if($playerTank->level == $tier) {
					$tier_array[$playerTank->ID] = $playerTank->name;
				}
			}
		}
		return $tier_array;
	}
	
	public function select($name, $data, $selected_value = NULL, $selected_id = NULL, $id_is_value = false) {

		$select = '<select name="'.$name.'" id="'.$name.'">';

		foreach($data as $id => $value) {
			$select .= '<option value="'.(!$id_is_value ? $id : $value).'" '
					.($selected_value && $selected_value == $value ? ' selected="selected"' : "")
					.($selected_id && $selected_id == $id ? ' selected="selected"' : "").
					'>'.$value.'</option>';
		}
		$select .= '</select>';
		return $select;

	}	
	
	
}



