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
		<?php
			$valid = true;
			
			if($entry['auto_generated']) {
				$valid = false;
			}
			
			if(empty($entry['created_time'])) {
				$valid = false;
			}
			
			$midnight = date("Y-m-d 00:00:00", strtotime($entry['presentation_time']));
			
			if(!empty($entry['created_time']) && strtotime($entry['created_time']) > strtotime($midnight)) {
				$valid = false;
			}
		?>
		<tr>
			<td><?=$entry['fn']?></td>
			<td><?=$entry['name']?></td>
			<td><?=$entry['presentation_time']?></td>
			<td>
				<?php if($entry['post_id']): ?>
				<a target="_blank" href="https://facebook.com/groups/<?=$fb_group_id?>/permalink/<?=$entry['post_id']?>"><img src="assets/img/fb.png" width="20px"></a>
				<?php else: ?>
				N/A
				<?php endif; ?>
			</td>
			<td<?=($valid ? '' : ' class="invalidcell"')?>><?=($entry['created_time'] ? $entry['created_time'] : 'N/A')?></td>
			<td><?=($entry['message'] ? $entry['message'] : 'N/A')?></td>
			<td>
				<?php if($entry['picture']): ?>
				<img class="invitationimage" src="<?=$entry['picture']?>" width="50px">
				<?php else: ?>
				N/A
				<?php endif; ?>
			</td>
			<td><?=($entry['likes'] != null ? $entry['likes'] : 'N/A')?></td>
			<td><?=($entry['comments'] != null ? $entry['comments'] : 'N/A')?></td>
			<td>
				<?php if($entry['auto_generated'] != null): ?>
				<?=($entry['auto_generated'] ? "Да" : "Не")?>
				<?php else: ?>
				N/A
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>