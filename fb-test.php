<?php
	session_start();
	require_once('libraries/php-graph-sdk/src/Facebook/autoload.php');
	
	$fb = new \Facebook\Facebook([
			  'app_id' => '740700432987141',
			  'app_secret' => '8d6a4cf24ff2c7867a052b0e72bdda61',
			  'default_graph_version' => 'v3.0'
			]);
			
	if(empty($_SESSION['fb_access_token'])) {			
		$helper = $fb->getRedirectLoginHelper();
		 
		$permissions = ['user_managed_groups', 'groups_access_member_info', 'publish_to_groups'];
		$loginUrl = $helper->getLoginUrl('http://localhost/web/project/fb-callback.php', $permissions);
		 
		echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
		exit;
	}
	
	try {
	  $response = $fb->get(
		'/1695063093904330/feed?fields=likes.summary(1).limit(0),comments.summary(1).limit(0),full_picture,message,created_time',
		$_SESSION['fb_access_token']
	  );
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}
	
	print_r($response->getDecodedBody());
?>