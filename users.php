<?php
require_once('core.php');

$images = false;

echo '<!DOCTYPE html><html lang="en"><head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head><body>';

$clanMembers = WOT::getClanMembers();

echo '<div class="container-fluid">';
echo '<table class="table table-condensed">';
echo '<thead><tr><th>Nickname</th><th>T10</th><th width="100px">Expires At</th><th>Actions<?th></tr></thead><tbody>';
foreach($clanMembers as $accountID => $clanMember) {

	$user_obj = user::instance();
	$user = $user_obj->get($accountID);
				
	$tanks = new user_tank();
	$tanks = $tanks->get($user->accountID, NULL, 10);

	//pre($clanMember);
	//pre($tanks);
	
	
	$image_string = '';
	$button_string = '';
	if(is_numeric($tanks[0]->tankID)) {
		foreach($tanks as $index => $tankObject) {
			$tank = new tank();
			$tank = $tank->get($tankObject->tankID);
			$image_string .= '<img src="'.$tank->image_small.'" alt="'.$tank->name.'" title="'.$tank->name.'" />';
			$button_string .= '<input class="btn btn-xs btn-primary" type="button" style="margin: 1px;" alt="'.$tank->name.'" title="'.$tank->name.'" value="'.$tank->name.'"/>';
		}		
	}
	
	//Access token check
	$color = 'danger';
	$expired = 'Expired';
	$actions = '<a class="btn btn-default btn-xs" role="button" href="auth.php?myAccountID='.$user->accountID.'">Auth</a>';
	if(!empty($user->access_token)) {
		if(time() < $user->expires_at) { 
			$color = 'success';
			$expired = date('d-m-Y', $user->expires_at);
			$actions = '<a class="btn btn-default btn-xs" role="button" href="auth.php?prolong&myAccountID='.$user->accountID.'">Extend Auth</a>';
		}
		else {
			$color = 'warning'; 
		}
	}
		
	echo '<tr class="'.$color.'"><td>'.$user->nickname.'</td><td>'.($images ? $image_string : $button_string).'</td><td>'.$expired.'</td><td>'.$actions.'</td></tr>';
	
}
echo '</tbody><table>';
echo '</div>';
echo '<body>';
echo '</html>';
