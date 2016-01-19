<?php
require_once('core.php');

$rq = (object)$_GET;

if(isset($rq->myAccountID)) {
	
	if(isset($rq->prolong)) {
		$user = new user();
		$user = $user->get($rq->myAccountID);

		$auth_url = 'https://api.worldoftanks.com/wot/auth/prolongate/';
		$redirect_url = '&redirect_uri=mario.906.io/wc/wot/auth_capture.php&prolong&myAccountID='.$user->accountID;
		$uri = $auth_url.$redirect_url;
	
		$result = WOT::myCurl($uri, true, 'application_id='.WOT::APPID.'&access_token='.$user->access_token);
		
		$user->access_token = $result['data']['access_token'];
		$user->expires_at = $result['data']['expires_at'];
		$user->update();
		
		header('Location: users.php');
	}
	elseif(isset($rq->clantools)) { //currently NOT used
		$auth_url = 'https://api.worldoftanks.com/wot/auth/login/?application_id='.WOT::APPID;
		$redirect_url = '&redirect_uri=mario.906.io/wc/wot/auth_capture.php?myAccountID='.$rq->myAccountID.'&send_clantools';
		$uri = $auth_url.$redirect_url;
		header('Location: '.$uri);
	}
	else {
		//If not clantools and not prolong, run regular
		$auth_url = 'https://api.worldoftanks.com/wot/auth/login/?application_id='.WOT::APPID;
		$redirect_url = '&redirect_uri=mario.906.io/wc/wot/auth_capture.php'.(isset($rq->myAccountID) ? '?myAccountID='.$rq->myAccountID : '').(isset($rq->clantools) ? '?clantools' : '');
		$uri = $auth_url.$redirect_url;	
	
		header('Location: '.$uri);
	}
	
}
else {
	$auth_url = 'https://api.worldoftanks.com/wot/auth/login/?application_id='.WOT::APPID;
	$redirect_url = '&redirect_uri=mario.906.io/wc/wot/auth_capture.php'.(isset($rq->myAccountID) ? '?myAccountID='.$rq->myAccountID : '').(isset($rq->clantools) ? '?clantools' : '');
	$uri = $auth_url.$redirect_url;
	
	
	header('Location: '.$uri);
}

