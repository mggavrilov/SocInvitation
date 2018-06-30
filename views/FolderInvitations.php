<div class="text">
	Ако желаете да разгледате информация за Facebook постовете на студентите, моля, посетете "<a href="ranking.php">Класация</a>".
</div>

<table id="table">
	<thead>
		<tr>
			<th>Ф.Н.</th>
			<th>Име</th>
			<th>Презентация</th>
			<th>Файл</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($studentInvitations as $entry): ?>
		<tr>
			<td><?=$entry['fn']?></td>
			<td><?=$entry['name']?></td>
			<td><?=$entry['presentation_time']?></td>
			<td><?=(isset($entry['filename']) ? $entry['filename'] : 'N/A')?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>