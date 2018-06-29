<?php
	$pageTitle = "Класация";
	
	require_once('views/header.php');
	
	if(!isset($_SESSION['username'])) {
		header("Location: login.php");
		exit;
	}
	
	require_once('models/Setting_model.php');
	
	$settingModel = new Setting_model();
	$fb_last_update = $settingModel->getSetting("fb_last_update");
	
	if(empty($fb_last_update)) {
		$_SESSION['notification']['message'] = "Класацията не е достъпна преди да бъде сканирана Facebook групата. Моля, първо сканирайте.";
		$_SESSION['notification']['type'] = "error";
		header("Location: process.php");
		exit;
	}
	
	require_once('models/Student_model.php');
	$studentModel = new Student_model();
	
	$rankings = $studentModel->getStudentRankings();
	
	require_once('views/Ranking.php');
	require_once('views/footer.php');
?>