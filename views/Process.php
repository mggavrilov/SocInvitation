<script src="assets/js/process.js" type="text/javascript"></script>

<form action="process.php" method="post" enctype="multipart/form-data">
	<label for="directory">Изберете директория за сканиране на покани</label>
	<input type="file" name="directory[]" id="directory" multiple webkitdirectory>
	
	<?php if($hasStudents): ?>
	<div><input type="radio" name="scantarget" id="dbradio" value="db" checked> Сканиране на записите в базата от данни</div>
	<div><input type="radio" name="scantarget" id="fileradio" value="file"> Сканиране на записите от CSV файл</div>
	<?php endif; ?>
	
	<div class="filearea" id="filearea">
		<label for="file">Изберете CSV файл <?=(!$hasStudents) ? "*" : ""?></label>
		<input type="file" name="file" id="file" <?=(!$hasStudents) ? "required" : ""?>>
		
		<?php if($hasStudents): ?>
		<div><input type="checkbox" name="truncate"> Заместване на съществуващите записи в базата от данни</div>
		<?php endif; ?>
	</div>
	
	<div><input type="checkbox" name="scanfb"> Сканиране на Facebook групата</div>
	<div><input type="checkbox" name="invgen"> Генериране на покани за закъснелите</div>
	
	<input class="button" type="submit" value="Submit">
</form>