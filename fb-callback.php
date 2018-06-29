<?php
	session_start();
	require_once('models/Facebook_model.php');
	require_once('models/Setting_model.php');
	require_once('libraries/php-graph-sdk/src/Facebook/autoload.php');

	$facebookModel = new Facebook_model();
	$fb = $facebookModel->getFacebookObject();
	 
	$helper = $fb->getRedirectLoginHelper();
	 
	try {
		$accessToken = $helper->getAccessToken();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	 
	if(!isset($accessToken)) {
		if($helper->getError()) {
			header('HTTP/1.0 401 Unauthorized');
			echo "Error: " . $helper->getError() . "\n";
			echo "Error Code: " . $helper->getErrorCode() . "\n";
			echo "Error Reason: " . $helper->getErrorReason() . "\n";
			echo "Error Description: " . $helper->getErrorDescription() . "\n";
		} else {
			header('HTTP/1.0 400 Bad Request');
			echo 'Bad request';
		}
		
		exit;
	}
	
	$oAuth2Client = $fb->getOAuth2Client();
	 
	if(!$accessToken->isLongLived()) {
		try {
			$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
			exit;
		}
	}
	
	$save = array();
	$save['name'] = 'fb_user_access_token';
	$save['value'] = (string) $accessToken;
	
	$settingModel = new Setting_model();
	$settingModel->saveSetting($save);
	 
	header('Location: process.php');
?>