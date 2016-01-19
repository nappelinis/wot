<?php
require_once('core.php');

ini_set('xdebug.var_display_max_data', -1);

$rq = (object)$_REQUEST;

if(isset($rq->submit)) {
	$db = WOT::db();
		
	$user = '';
	if(isset($rq->id)) {
		
		$user = WOT::getUser($rq->id);
		
		if($user != NULL) { //UPDATE
					
			$this_user = $user;
			$accountID = $this_user->accountID;
			
			//AccountID post fix
			if($this_user->accountID == 0) {
				$accountID = WOT::getPlayerID($this_user->username);
			}
			
			WOT::updateWN8($this_user->ID);
			
			WOT::updateTanks($this_user->ID);
			//if($this_user->accountID == 1005084408)
			//	exit();
			
			$query = 'UPDATE `solid_recruitment` SET '
					. '`username`="'.mres($rq->username).'", '
					. '`accountID`='.mres(($accountID != 0 ? $accountID : 0)).', '
					. '`source`="'.mres($rq->source).'", '				
					. '`status` = "'.mres($rq->status).'", '
					. '`comments` = "'.mres($rq->comments).'", '
					. '`recruiter` = "'.mres($rq->recruiter).'", '
					. '`updated` = "'.mres(date('Y-m-d h:i:s', strtotime('now'))).'" '
					. 'WHERE `ID` = '.mres($rq->id);
					
			$db->query($query);	

		}
		
	}
	else {
		//Pull accountID from WG
		$accountID = WOT::getPlayerID($rq->username);
		
		//Pull WN8 and RECENT WN8 from wotlabs
		$wn8_stuff = WOT::updateWN8(NULL, $rq->username, true);
		
		//Pull Player Tanks from WG
		$tank_stuff = WOT::updateTanks(NULL, $accountID, true);
		
		$rq->tierten = $tank_stuff['tierten'];
		$rq->tiereight = $tank_stuff['tiereight'];
		$rq->tiersix = $tank_stuff['tiersix'];
		$rq->tierten_names = $tank_stuff['tierten_names'];
		$rq->tiereight_names = $tank_stuff['tiereight_names'];
		$rq->tiersix_names = $tank_stuff['tiersix_names'];
		$rq->wn8 = $wn8_stuff['wn8'];
		$rq->recent_wn8 = $wn8_stuff['rwn8'];
			
		$query = 'INSERT INTO `solid_recruitment` (username, accountID, source, wn8, recent_wn8, tierten, tierten_names, tiereight, tiereight_names, tiersix, tiersix_names, status, comments, recruiter, created, updated) VALUES '
				. '("'.$rq->username.'", '.$accountID.', "'.$rq->source.'", "'.$rq->wn8.'", "'.$rq->recent_wn8.'", "'.$rq->tierten.'", "'.$rq->tierten_names.'",  "'.$rq->tiereight.'",  "'.$rq->tiereight_names.'", "'.$rq->tiersix.'", "'.$rq->tiersix_names.'", "'.$rq->status.'", "'.$rq->comments.'", "'.$rq->recruiter.'", "'.date('Y-m-d h:i:s', strtotime('now')).'", "'.date('Y-m-d h:i:s', strtotime('now')).'")';
		
		//var_dump($query);
		//exit();
		$db->query($query);
	}
	header('Location: http://mario.906.io/wc/wot/solid_recruitment.php');	
}

//userData if passed
if(isset($rq->id)) {
	
	$user = WOT::getUser($rq->id);
	
	$db = WOT::db();
	if(isset($_REQUEST['delete'])) {
		$query = "UPDATE `solid_recruitment` SET `archive` = 1 WHERE `ID` = ".$user->ID;
		$db->query($query);
		header('Location: http://mario.906.io/wc/wot/solid_recruitment.php');
	}	
	
}
else {
	$user = NULL;
}

function mres($input) {
	return $input;
}

function select($name, $data, $selected = NULL) {
	
	$select = '<select name="'.$name.'" id="'.$name.'">';
	
	foreach($data as $id => $value) {
		$select .= '<option id="'.$id.'" '.($selected && $selected == $value ? ' selected="selected"' : "").'>'.$value.'</option>';
	}
	$select .= '</select>';
	return $select;
	
}

echo '<!DOCTYPE html><html><head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css">
</head></body>';
?>

<div class="container-fluid"><br />
<a href="solid_recruitment.php"><input class="btn btn-primary" type="button" value="Back" /></a><br /><br />
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
	<table class="table table-striped" style="max-width: 98%;">
		<thead>
			<th>Username</th>
			<th>Source</th>
			<th style="width: 50px;">WN8</th>
			<th>Recent WN8</th>
			<th>T10</th>
			<th>T8</th>
			<th>T6</th>
			<th>Link</th>
			<th>Status</th>
			<th>Comments</th>
			<th>Recruiter</th>
			<th>Options</th>
		<tbody>
			<td><input type="text" class="username" name="username" id="username" value="<?= ($user ? $user->username : '') ?>" /></td>
			<td><?= select('source', WOT::getSources(), ($user ? $user->source : NULL)); ?></td>
			<td><input type="text" size="5" disabled="disabled" class="wn8" name="wn8" id="wn8" value="<?= ($user ? $user->wn8 : '') ?>" /></td>
			<td><input type="text" size="5" disabled="disabled" class="recent_wn8" name="recent_wn8" id="recent_wn8" value="<?= ($user ? $user->recent_wn8 : '') ?>"/></td>
			<td><input type="text" size="3" disabled="disabled" class="tierten" name="tierten" id="tierten" value="<?= ($user ? $user->tierten : '') ?>"/></td>
			<td><input type="text" size="3" disabled="disabled" class="tiereight" name="tiereight" id="tiereight" value="<?= ($user ? $user->tiereight : '') ?>"/></td>
			<td><input type="text" size="3" disabled="disabled" class="tiersix" name="tiersix" id="tiersix" value="<?= ($user ? $user->tiersix : '') ?>"/></td>
			<td><?= ($user ? '<a target="_blank" href="http://wotlabs.net/na/player/'.$user->username.'">Wotlabs</a>' : '')?></td>
			<td><?= select('status', WOT::getStatuses(), ($user ? $user->status : NULL)); ?></td>
			<td><textarea name="comments" id="comments" class="comments" cols="50" rows="1"><?= ($user ? $user->comments : '') ?></textarea></td>
			<td><?= select('recruiter', WOT::getRecruiters(), ($user ? $user->recruiter : NULL)); ?></td>
			<td><input type="submit" name="submit" class="btn btn-primary btn-xs" value="<?= ($user ? 'Update' : 'Add') ?>" /></td>
		</tbody>
	</table>
	<?= ($user ? '<input type="hidden" name="id" value="'.$user->ID.'" />' : '')?>
</form>
</div>
</body>
</html>


