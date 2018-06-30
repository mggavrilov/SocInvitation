<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		
		<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
		
		<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
		
		<script src="assets/js/script.js" type="text/javascript"></script>
		
		<link href="assets/css/vanilla-dataTables.min.css" rel="stylesheet" type="text/css">
		<script src="assets/js/vanilla-dataTables.min.js" type="text/javascript"></script>
		
		<title>SocInvitation<?=(isset($pageTitle) ? " | $pageTitle" : "")?></title>
	</head>
	<body>
		<div class="content">
			<a href="index.php">
				<div class="top">
					<img class="logo" src="assets/img/logo.png">
					<span class="sitetitle">SocInvitation</span>
				</div>
			</a>
			<nav>
				<a href="index.php">Начало</a>
				<?php if(isset($_SESSION['username'])): ?>
				<a href="process.php">Сканиране</a>
				<a href="ranking.php">Класация</a>
				<a href="settings.php">Настройки</a>
				<a href="logout.php">Изход</a>
				<?php else: ?>
				<a href="login.php">Вход</a>
				<?php endif; ?>
			</nav>
			<div id="notification"<?=(isset($_SESSION['notification']) ? ' class="' . $_SESSION['notification']['type'] . '"' : '')?>>
				<?=(isset($_SESSION['notification']) ? $_SESSION['notification']['message'] : '')?>
			</div>
			
			<?php unset($_SESSION['notification']); ?>
			<div class="page">
			