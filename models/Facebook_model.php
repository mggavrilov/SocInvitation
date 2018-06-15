<?php
	require_once('Setting_model.php');
	require_once('../libraries/php-graph-sdk/src/Facebook/autoload.php');
	
	class Facebook_model {
		private $settingModel;
		private $fb;
		
		public function __construct() {
            $this->settingModel = new Setting_model();
			$this->fb = $this->initFacebookObject();
        }
		
		private function initFacebookObject() {
			$app_id = $this->settingModel->getSetting('app_id');
			$app_secret = $this->settingModel->getSetting('app_secret');
			$default_graph_version = $this->settingModel->getSetting('default_graph_version');
			
			return new Facebook\Facebook([
				'app_id' => $app_id,
				'app_secret' => $app_secret,
				'default_graph_version' => $default_graph_version
			]);
		}
		
		public function getFacebookObject() {
			return $this->fb;
		}
		
		//https://developers.facebook.com/docs/facebook-login/manually-build-a-login-flow#checktoken
		//https://developers.facebook.com/docs/facebook-login/access-tokens/#apptokens
		//https://developers.facebook.com/docs/graph-api/reference/v3.0/debug_token
		public function testFacebookToken() {
			$fb_user_access_token = $this->settingModel->getSetting('fb_user_access_token');
			$app_id = $this->settingModel->getSetting('app_id');
			$app_secret = $this->settingModel->getSetting('app_secret');
			
			$app_access_token = $app_id . "|" . $app_secret;
			
			try {
				$response = $this->fb->get(
					"/debug_token?input_token=$fb_user_access_token",
					$app_access_token
				);
			} catch(Exception $e) {
				return 'Facebook SDK returned an error: ' . $e->getMessage();
			}
			
			$result = $response->getDecodedBody();
			
			if($result['data']['is_valid'] == 1) {
				return true;
			}
			else {
				return false;
			}
		}
		
		public function getFacebookGroupPosts() {
			$fb_group_id = $this->settingModel->getSetting('fb_group_id');
			$fb_user_access_token = $this->settingModel->getSetting('fb_user_access_token');
			
			try {
				$response = $fb->get(
					"/$fb_group_id/feed?fields=likes.summary(1).limit(0),comments.summary(1).limit(0),full_picture,message,created_time",
					$fb_user_access_token
				);
			} catch(Exception $e) {
				return 'Facebook SDK returned an error: ' . $e->getMessage();
			}
			
			return $response->getDecodedBody();
		}
	}
?>