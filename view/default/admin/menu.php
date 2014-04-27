<?php 
foreach($data['user_menu'] as $key => $menu): 
$first_child = array_shift($menu['child']);
?>
<li <?php if($key == $data['ctr_id']): ?>class="active"<?php endif;?>><a href="/admin/<?=$first_child['controller'].'/'.$first_child['action'].'/'?>"><?=$menu['name']?></a></li>
<?php endforeach; ?>