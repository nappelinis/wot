<?php
require_once('core.php');

$rq = (object)$_GET;

if(!empty($rq)) {
	
	if($rq->status == 'ok' && isset($rq->myAccountID) && $rq->myAccountID == $rq->account_id) {

		$user = new user();
		$user->nickname = $rq->nickname;
		$user->accountID = $rq->account_id;
		$user->access_token = $rq->access_token;
		$user->expires_at = $rq->expires_at;

		$user->add();
	
		header('Location: users.php');
	}
	elseif($rq->status == 'ok') {
		$user = new user();
		
		//Check if user exists and update rather than create
		$user = $user->get($rq->account_id);
		
		//Change user data
		$user->nickname = $rq->nickname;
		$user->accountID = $rq->account_id;
		$user->access_token = $rq->access_token;
		$user->expires_at = $rq->expires_at;
		
		if(empty($user->ID)) {
			$user->add();
		}
		else {
			$user->update();
		}
		
		if(isset($rq->clantools)) {
			header('Location: https://clantools.us/attendance/submit');
		}
		
	}
	else {
		if($rq->status != 'ok') {
			echo 'Error: '.$rq->status.' '.$rq->message.'<br /><br />';
		}
		if($rq->myAccountID != $rq->account_id) {
			echo 'Incorrect player auth<br /><br />';
		}
		echo '<a class="btn btn-default" href="users.php">Back to users</a>';
	}
}
