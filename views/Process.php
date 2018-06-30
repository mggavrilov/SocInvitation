<script src="assets/js/process.js" type="text/javascript"></script>

<form id="processform" action="process.php" method="post" enctype="multipart/form-data">
	<label for="directory">Изберете директория за сканиране на покани</label>
	<input type="file" name="directory[]" id="directory" multiple webkitdirectory>
	
	<?php if($hasStudents): ?>
	<div><label><input type="radio" name="scantarget" id="dbradio" value="db" checked> Сканиране на записите в базата от данни<label></div>
	<div><label><input type="radio" name="scantarget" id="fileradio" value="file"> Сканиране на записите от CSV файл</label></div>
	<?php endif; ?>
	
	<div class="filearea" id="filearea">
		<label for="file">Изберете CSV файл <?=(!$hasStudents) ? "*" : ""?></label>
		<input type="file" name="file" id="file" <?=(!$hasStudents) ? "required" : ""?>>
		
		<?php if($hasStudents): ?>
		<div><label><input type="checkbox" name="truncate"> Заместване на съществуващите записи в базата от данни</label></div>
		<?php endif; ?>
	</div>
	
	<div><label><input type="checkbox" name="scanfb" id="scanfb"> Сканиране на Facebook групата</label></div>
	<div id="invgendiv"><label><input type="checkbox" name="invgen" id="invgen"> Генериране на покани за закъснелите</label></div>
	
	<input class="button" type="submit" value="Submit">
</form>