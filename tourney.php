<?php
require_once('core.php');

$rq = (object)$_POST;
?>

<form action="tourney.php" method="POST">
	<textarea cols="200" name="player_names" id="player_names" class="player_names"><?= (isset($rq->player_names) ? $rq->player_names : '') ?></textarea><br /><br />
	10<input type="checkbox" name="tier10" value="tier10" <?= (isset($rq->tier10) ? 'checked' : 'checked') ?> />
	9<input type="checkbox" name="tier9" value="tier9"  <?= (isset($rq->tier9) ? 'checked' : '') ?> />
	8<input type="checkbox" name="tier8" value="tier8"  <?= (isset($rq->tier8) ? 'checked' : '') ?> />
	
	<input type="submit" name="submit" id="submit" class="submit" value="Submit" />
</form>


<?php

if(isset($rq->player_names)) {
	$player_names = $rq->player_names;
	$player_names = str_replace(' ', '', $player_names);
	$player_names = explode(',', $player_names);
	
	foreach($player_names as $player_name) {
		$player_ids[$player_name] = WOT::getPlayerID($player_name);
	}
	
	pre($player_ids);

	
	
	foreach($player_ids as $name => $accountID) {
		
		/*
		if($name == 'Nappelinis') {
			$tanks = WOT::scrapeWotlabsTanks($name);
			exit();
		}
		 */
		
		
		$wn8s = WOT::updateWN8(NULL, $name, true);
		
		pre('Player Name: '.$name. ' -- WN8: '.$wn8s['wn8'].' -- RWN8: '.$wn8s['rwn8']);
		
		$all_player_tanks = WOT::getPlayerTanks($accountID);
		$player_tanks = WOT::getPlayerTierTanks(10, $all_player_tanks);
		pre($player_tanks);
		if(isset($rq->tier9)) {
			$player_tanks = WOT::getPlayerTierTanks(9, $all_player_tanks);
			pre($player_tanks);
		}
		if(isset($rq->tier8)) {
			$player_tanks = WOT::getPlayerTierTanks(8, $all_player_tanks);
			pre($player_tanks);	
		}
		
		
	}	
}
	
?>