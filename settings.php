<?php
	$pageTitle = "Настройки";
	
	require_once('views/header.php');
	
	if(!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit;
	}
	
	require_once('models/Setting_model.php');
	
	$settingModel = new Setting_model();
	
	$settings = $settingModel->getAllSettings();
	
	if(!empty($_POST)) {
		foreach($settings as $key => $setting) {
			if(!isset($_POST[$setting['name']]) || !is_string($_POST[$setting['name']]) || empty($_POST[$setting['name']])) {
				header("Location: settings.php");
				exit;
			}
			
			if($setting['value'] != $_POST[$setting['name']]) {
				//Setting was updated
				$settings[$key]['value'] = $_POST[$setting['name']];
				
				$save = array();
				$save['name'] = $setting['name'];
				$save['value'] = $_POST[$setting['name']];
				$settingModel->saveSetting($save);
			}
		}
		
		$_SESSION['notification']['message'] = "Настройките бяха променени успешно.";
		$_SESSION['notification']['type'] = "success";
		header("Location: settings.php");
		exit;
	}
	
	require_once('views/Settings.php');
	require_once('views/footer.php');
?>