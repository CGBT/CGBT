<ul>
	<?php foreach ($data['user_menu'][$data['ctr_id']]['child'] as $key => $value): ?>
		<li
		<?php 
		if (isset($data['act_id']))
		{
			if ($data['act_id'] == $key)
			{
				echo "class='current'";
			}
		}
		else
		{
			if ($value['controller'] == $data['current_ctr'])
			{
				echo "class='current'";
			}
		}
		?>>
		<a href="/admin/<?=$value['controller']?>/<?=$value['action']?>/"><?=$value['name']?></a>
		</li>
	<?php endforeach; ?>
</ul>