<?php
require_once('core.php');

//Update tank listing table
WOT::populateTankList();

//Update registered users tanks
$clanMembers = WOT::getClanMembers();

/*
 * Update global clan user list by removing db_users that are no longer 
 * part of the clan and just take up space.
 */
$user = new user();
$all_users = $user->get_all();
foreach($all_users as $dummy_index => $db_user) {
	if(!array_key_exists($db_user->accountID, $clanMembers)) {
		$db_user->delete();
		echo 'Removed '.$db_user->nickname.' ('.$db_user->accountID.')'.'<br />';
	}
}
unset($user);


/*
 * Updating users tanks 
 * BUG: With expired token this fails
 */
foreach($clanMembers as $accountID => $clanMemberName) {
	
	//Get the user if exists
	$user = new user();
	$user = $user->get($accountID);
	
	if(!empty($user->ID)) { //if user exists
		WOT::updateRegisteredUsersTanks($user);
		pre('User '.$clanMemberName.' updated! ('.$accountID.')');
	}
	else {
		$user = new user();
		$user->nickname = $clanMemberName;
		$user->accountID = $accountID;
		$user->username = $clanMemberName;
		$user->password = $clanMemberName;
		$user->add();
		
		pre('User '.$clanMemberName.' is not registered! ('.$accountID.') was added.');
	}
}

pre('Done populating registered users tanks!');