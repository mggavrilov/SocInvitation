<?php
	$pageTitle = "Index";
	require_once('views/header.php');
?>
<div class="text">
	Добре дошли в SocInvitation<?=(isset($_SESSION['username']) ? ", {$_SESSION['username']}" : "")?>!
</div>
<?php
	require_once('views/footer.php');
?>