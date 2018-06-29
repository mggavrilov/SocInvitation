<?php
	$pageTitle = "Login";

	require_once('views/header.php');
	require_once('views/Login.php');
	require_once('views/footer.php');
	
	if(!empty($_POST)) {
		if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] === "admin" && $_POST['password'] === "socinvitation") {
			session_start();
			$_SESSION['username'] = "admin";
			header("Location: index.php");
			exit;
		}
	}
?>