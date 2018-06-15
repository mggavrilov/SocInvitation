<form method="post" action="">
<?php foreach($settings as $setting): ?>
	<label for="<?=$setting['name']?>"><?=$setting['name']?></label>
	<input type="text" name="<?=$setting['name']?>" value="<?=$setting['value']?>">
<?php endforeach; ?>
	<input type="submit" value="Submit">
</form>