<?php
	require_once('models/Setting_model.php');
	
	$settingModel = new Setting_model();
	
	$settings = $settingModel->getAllSettings();
	
	require_once('views/Settings.php');
	
	if(!empty($_POST)) {
		foreach($settings as $setting) {
			if($setting['value'] != $_POST[$setting['name']]) {
				//Setting was updated
				$save = array();
				$save['name'] = $setting['name'];
				$save['value'] = $_POST[$setting['name']];
				$settingModel->saveSetting($save);
			}
		}
		
		header("Location: " . $_SERVER['PHP_SELF']);
		exit;
	}
?>