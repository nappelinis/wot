<?php
require_once('core.php');

echo '<!DOCTYPE html><html><head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css">
</head><body>';

$rq = (object)$_GET;



$tankList = WOT::getTanksList();

$archive = 0;
if(isset($rq->archive))
	$archive = $rq->archive;

$recruit = new recruit();
$recruits = $recruit->get_recruits($archive);

$wotlabs = 'http://wotlabs.net/na/player/';
$clantools = 'https://clantools.us/servers/na/players?id=';

echo '<div class="container-fluid"><br />';

echo '<form action="solid_recruitment.php" action="POST">';
	echo '<a href="updateUser.php"><input class="btn btn-primary" type="button" value="Add User" /></a>&nbsp;&nbsp;&nbsp;';
	echo '<a href="solid_recruitment.php?archive=0"><input class="btn btn-'.($archive == 0 ? 'primary' : 'default').'" type="button" value="Active Users" /></a>&nbsp;';
	echo '<a href="solid_recruitment.php?archive=2"><input class="btn btn-'.($archive == 2 ? 'primary' : 'default').'" type="button" value="All Users" /></a>&nbsp;';
	echo '<a href="solid_recruitment.php?archive=1"><input class="btn btn-'.($archive == 1 ? 'primary' : 'default').'" type="button" value="Archived Users" /></a>&nbsp;';	
echo '</form>';

echo '<table class="table table-striped" style="max-width: 98%;">';
echo '<thead><th>Username</th><th>Source</th><th>WN8</th><th>RWN8</th><th>T10</th><th>T8</th><th>T6</th><th>Wotlabs</th><th>Clantools</th><th>Status</th><th>Comments</th><th>Recruiter</th><th>Created || Updated</th><th>Options</th></thead>';
foreach($recruits as $recruit) {
	
	//$playerTanksList = WOT::getPlayerTanks($player_accountID);
	//$tier10 = WOT::getPlayerTierTanks(10, $playerTanksList, $tankList);
	//var_dump($tier10);
	
	//pre($recruit);
	
	echo '<tr>'
	. '<td title="'.$recruit->accountID.'">'.$recruit->username . '</td>'
	. '<td>'.$recruit->source . '</td>'
	. '<td class="'.WOT::colorCoding('wn8', $recruit->wn8).'">'.$recruit->wn8 .'</td>'
	. '<td class="'.WOT::colorCoding('wn8', $recruit->recent_wn8).'">'.$recruit->recent_wn8.'</td>'
	. '<td title="'.$recruit->tierten_names.'">'.(empty($recruit->tierten) ? '-' : $recruit->tierten).'</td>'
	. '<td title="'.$recruit->tiereight_names.'">'.(empty($recruit->tiereight) ? '-' : $recruit->tiereight).'</td>'
	. '<td title="'.$recruit->tiersix_names.'">'.(empty($recruit->tiersix) ? '-' : $recruit->tiersix).'</td>'
    . '<td><a target="_blank" href="'.$wotlabs.$recruit->username.'">Wotlabs</a></td>'
	. '<td><a target="_blank" href="'.$clantools.$recruit->accountID.'">Clantools</a></td>'
	. '<td class="'.WOT::colorCoding('status', $recruit->status).'">'.$recruit->status .'</td>'
	. '<td>'.$recruit->comments .'</td>'
	. '<td>'.(is_numeric($recruit->recruiter) ? WOT::getRecruiters()[$recruit->recruiter] : $recruit->recruiter).'</td>'
	. '<td title="'.$recruit->created.' '.$recruit->updated.'">'.date('Y-m-d', strtotime($recruit->created)).'&nbsp;&nbsp;<span class="bold">||</span>&nbsp;&nbsp;'.date('Y-m-d', strtotime($recruit->updated)).'</td>'
	. '<td><a href="updateUser.php?id='.$recruit->ID.'"><input type="button" class="btn btn-primary btn-xs" value="Edit" /></a>&nbsp;&nbsp;<a href="updateUser.php?id='.$recruit->ID.'&delete"/><input type="submit" class="btn btn-primary btn-xs" value="Archive" /></a></td>';
}
echo '</tbody></table>';
echo '</div>';
echo '</body></html>';


