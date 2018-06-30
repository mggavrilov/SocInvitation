<div class="text">
	Последно обновяване: <?=$fb_last_update?>
</div>

<table id="table">
	<thead>
		<tr>
			<th>Ф.Н.</th>
			<th>Име</th>
			<th>Презентация</th>
			<th>Пост</th>
			<th>Създаден</th>
			<th>Съобщение</th>
			<th>Снимка</th>
			<th>Харесвания</th>
			<th>Коментари</th>
			<th>Автоматичен</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($rankings as $entry): ?>
		<tr>
			<td><?=$entry['fn']?></td>
			<td><?=$entry['name']?></td>
			<td><?=$entry['presentation_time']?></td>
			<td><a target="_blank" href="https://facebook.com/groups/<?=$fb_group_id?>/permalink/<?=$entry['post_id']?>">Линк</a></td>
			<td><?=$entry['created_time']?></td>
			<td><?=$entry['message']?></td>
			<td><img class="invitationimage" src="<?=$entry['picture']?>" width="50px"></td>
			<td><?=$entry['likes']?></td>
			<td><?=$entry['comments']?></td>
			<td><?=($entry['auto_generated'] ? "Да" : "Не")?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>