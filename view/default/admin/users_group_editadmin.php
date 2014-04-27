<?php
include 'header.php';
?>
<body>
<?php
include 'top.php';
?>
<!--top-->
<div id="container">
	<div id="sidebar">
		<?php
			include 'sidebar.php';
		?>
	</div><!--sidebar-->
	<div id="content">		
		<div class="form_title w100">修改用户组</div>		
		<div class="form_box w1020">
			<form action="/admin/users_group/updateadmin/" method="post" name='form1' id="form1">
			<input type="hidden" name="editid" value="<?=$data['current_row']['id'];?>">
			<table class="table_form">
			<?php foreach($data['all_admin_privileges'] as $key => $menu): ?>
				<tr>					
					<th class="w150">					
					<input type="checkbox" id="select_all_<?=$key?>">
					<label for="select_all_<?=$key?>"><?=$menu['name']?></label>
					</th>
					<td>
					<?php foreach ($menu['child'] as $row):?>
					<div style="width:200px;float:left;">
					<input type="checkbox" value="<?=$row['controller']?>/<?=$row['action']?>" name="chk_privileges[]" id="<?=$key?>_/<?=$row['controller']?>/<?=$row['action']?>"
					<?php if ($data['current_row']['admin_privileges'][$row['controller'].'/'.$row['action']]):?> checked="checked"<?php endif;?>>
					<label style="cursor:pointer;" for="<?=$key?>_/<?=$row['controller']?>/<?=$row['action']?>"><?=$row['name']?></label>
					</div>
					<?php endforeach;?>
					</td>
				</tr>
			<?php endforeach; ?>
				<tr>
					<th></th>
					<td>
					<a class="btn btn-success" id="submit" href="javascript:document.form1.submit()">保存</a>
					</td>
					<td></td>
				</tr>				
			</table>
			</form>
		</div><!--form_box-->
	</div>
</div><!--container-->
<script type="text/javascript">
$(function(){
	$("input[id^=select_all_]").change(function(){$(this).parent().next().find("input[type=checkbox]").attr("checked",this.checked)});
})
</script>

<?php
include 'footer.php';
?>
