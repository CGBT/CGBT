<script type="text/javascript">
$(function(){
	$(".delete_link").powerFloat({
		position: "2-3",
		eventType: "click",
		showCall:function(){
				var softsiteid = $(this).attr("softsiteid");
				var action="/softsite/"+softsiteid+"/delete";
				$("#frm_delete").attr("action", action);
				$("select[name=selectreason] option:first-child").prop("selected",true);
				$("input[name=reason]").val('');
				$("#softsiteid").val(softsiteid);	
				var tr = $("#t"+softsiteid);
				var text = tr.find('a[name=title]').html()||$(".torrent-title h1").text();
				$('#delete_torrent_name').text(text);
		},
		target: "#delete_box"
	});

	$('#delete_button').click(function(){
		submit_form('frm_delete', '', '', delete_success);
	});

	$('#delete_close_button').click(function(){
		$.powerFloat.hide();
	});

	function delete_success()
	{
		var softsiteid = $("#softsiteid").val();
		$("#t"+softsiteid).remove();
		$("select[name=selectreason] option:first-child").prop("selected",true);
		$("input[name=reason]").val('');
		$.powerFloat.hide();
	}
})
</script>

<div id="delete_box" class="shadow target_box dn">
<form action="" method="post" enctype="multipart/form-data" id="frm_delete">
	<div class="target_list">
	<span style="color:red;font-weight:bold">删除</span>软件名称：<span id="delete_torrent_name" style="display:block;width:320px;overflow:hidden;height:20px;"></span>
	</div>
	<div class="target_list">
    	操作原因
    	<input type="text" name="reason" value="">
    	<select name="selectreason" style="width:100px;" onchange="this.form.reason.value=this.value">
			<option value="">--------</option>
			<option value="有新版本了">有新版本了</option>
			<option value="文件有问题">文件有问题</option>
		</select>
    </div>	

    <div class="target_list" style="border-bottom:none;">
        <input id="softsiteid" type="hidden" value="">
		<?php if (isset($data['action']) && ($data['action'] == 'edit'||$data['action'] == 'details')): ?>
		<input type="hidden" value="" name="edit_page">
		<?php endif; ?>
        <button id="delete_button" type="button" class="pn pnc"><strong>提交</strong></button>
		<button id="delete_close_button" type="button" class="pn pnc"><strong>关闭</strong></button>
    </div>
	</form>
</div>