<?php
require_once(dirname(__FILE__).'/wot.php');

echo '<!DOCTYPE html><html><head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css">';

$AO = '1000000070';

$clanMembers = WOT::getClanMembers('', $AO);

$timeZone = 'America/Detroit';


echo '<table>';
echo '<tr><th>Nickname #acc</th><th>Last Battle</th><th>Last Updated</th><th>Battle Inactive / Inactive</th></tr>';
foreach($clanMembers as $account_ID => $account_name) {
	$personal_data = WOT::getPersonalData($account_ID);
	$personal_data = $personal_data['data'];
	//var_dump($personal_data[$account_ID]);
	
	$nickname = $personal_data[$account_ID]['nickname'];
	
	//Last Battle Time
	$last_battle_time = $personal_data[$account_ID]['last_battle_time'];
	$battle_time = new DateTime('@'.$last_battle_time);
	$battle_time->setTimeZone(new DateTimeZone($timeZone));
	$battle_time = $battle_time->format('H:i:s Y-m-d');
			
	$updated_at = $personal_data[$account_ID]['updated_at'];
	$update_time = new DateTime('@'.$updated_at);
	$update_time->setTimeZone(new DateTimeZone($timeZone));
	$update_time = $update_time->format('H:i:s Y-m-d');
	
	$battle_inactive = recent_activity($battle_time);
	$inactive = recent_activity($update_time);

	echo '<tr>';
	echo '<td>'.$nickname.' #'.$account_ID.'</td>';
	echo '<td>'.$battle_time.'</td>';
	echo '<td>'.$update_time.'</td>';
	echo '<td>'.$battle_inactive.' / '.$inactive.'</td>';
	echo '</tr>';
}

echo '</table>';

function recent_activity($date) {
	
	$supplied_date = new DateTime(date('Y-m-d', strtotime($date)));
	$today = new DateTime(date('Y-m-d', strtotime('now')));
	
	$diff = date_diff($supplied_date, $today);

	return $diff->days;
	
}

function activity_color($count) {
	if($count >= 28) return 'red';
	elseif($count >= 21) return 'red';
	elseif($count >= 14) return 'red';
	elseif($count >= 7) return 'orange';
	elseif($count >= 3) return 'yellow';
	else return 'green';
	
}


//var_dump($clanMembers);