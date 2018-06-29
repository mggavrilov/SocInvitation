<?php
	require_once('Setting_model.php');
	require_once('libraries/php-graph-sdk/src/Facebook/autoload.php');
	
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
		public function isFacebookTokenValid() {
			$fb_user_access_token = $this->settingModel->getSetting('fb_user_access_token');
			
			if(empty($fb_user_access_token)) {
				return false;
			}
			
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
			
			return ($result['data']['is_valid'] == 1);
		}
		
		public function getFacebookGroupPosts() {
			$fb_group_id = $this->settingModel->getSetting('fb_group_id');
			$fb_user_access_token = $this->settingModel->getSetting('fb_user_access_token');
			
			try {
				$response = $this->fb->get(
					"/$fb_group_id/feed?fields=reactions.summary(1).limit(0),comments.summary(1).limit(0),full_picture,message,created_time",
					$fb_user_access_token
				);
			} catch(Exception $e) {
				return 'Facebook SDK returned an error: ' . $e->getMessage();
			}
			
			return $response->getDecodedBody();
		}
		
		public function generatePost($student) {
			$fb_group_id = $this->settingModel->getSetting('fb_group_id');
			$fb_user_access_token = $this->settingModel->getSetting('fb_user_access_token');

			$postMessage = "Здравейте! Аз съм робот, който е по-добър в създаването на покани от {$student['name']}, ф.н. {$student['fn']}. Заповядайте на презентацията на този човек на {$student['presentation_time']}.";
			
			$postArray = array('message' => $postMessage,
							   'link' => "https://i.imgur.com/YLvb8Z0.jpg");
			
			try {
				$response = $this->fb->post(
					"/$fb_group_id/feed?fields=full_picture,message,created_time",
					$postArray,
					$fb_user_access_token);
			} catch(Exception $e) {
				return 'Facebook SDK returned an error: ' . $e->getMessage();
			}
			
			return $response->getDecodedBody();
		}
		
		//This doesn't work; says there were insufficient permissioons provided even if ALL of them are provided; bug report opened
		public function deletePost($postID) {
			$fb_group_id = $this->settingModel->getSetting('fb_group_id');
			$fb_user_access_token = $this->settingModel->getSetting('fb_user_access_token');
			
			try {
				$response = $this->fb->delete(
					"/" . $fb_group_id . "_" . $postID,
					array(),
					$fb_user_access_token);
			} catch(Exception $e) {
				return 'Facebook SDK returned an error: ' . $e->getMessage();
			}
			
			return $response->getDecodedBody();
		}
	}
?>