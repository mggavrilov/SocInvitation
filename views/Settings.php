<form method="post" action="" id="settingsform">
	<?php foreach($settings as $setting): ?>
	<label for="<?=$setting['name']?>"><?=$setting['name']?></label>
	<input type="text" name="<?=$setting['name']?>" value="<?=$setting['value']?>" placeholder="<?=$setting['name']?>" required>
	<?php endforeach; ?>
	
	<input class="button" type="submit" value="Submit">
</form>