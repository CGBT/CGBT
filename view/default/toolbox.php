<?php
include 'header.php';
?>

<div id="wp" class="wp">
<div id="ct" class="ct2_a wp cl box-inside">

<div class="wp98">
	<div class="box">
		<div class="box-header">
			<span class="box-title">修改个人信息</span>
			<ul class="box-toolbar">
				<li></li>
			</ul>
		</div>
		<div class="box-content">
			<span class="button-group">
	        	<a target="_blank" href="<?=$data['setting']['forums_url']?>home.php?mod=spacecp&ac=profile&op=password" class="button button-blue">修改密码</a>
	        	<a target="_blank" href="<?=$data['setting']['forums_url']?>home.php?mod=spacecp&ac=avatar" class="button">修改头像</a>
	        	<a target="_blank" href="<?=$data['setting']['forums_url']?>home.php?mod=spacecp" class="button">修改论坛信息</a>
	        	<a target="_blank" href="/user/<?=$data['uid']?>/" class="button">查看个人详情</a>
	        	<a target="_blank" href="/list/logslogin/" class="button">查看我的登录日志</a>
	        	<a target="_blank" href="/list/credits/" class="button">查看我的积分日志</a>
	        </span>
		</div>
	</div>
</div>
<div class="box-group">
	<div class="box">
		<div class="box-header">
			<span class="box-title">重置用户识别码(passkey)</span>
			<ul class="box-toolbar">
				<li></li>
			</ul>
		</div>
		<div class="box-content">
			<form id="reset-passkey-form" onsubmit="return false;">
				<div class="page-header">
					<h4>用户识别码(Passkey)相当于你的账号和密码，请不要泄露给其他人!</h4>
				</div>
				<div class="form-field">
					<div class="form-item"><a class="bluelink" target='_blank' href='http://zhixing.bjtu.edu.cn/thread-547684-1-1.html'>点击此处查看关于用户识别码的说明</a></div>
				</div>
				<div class="form-field">
					<label>
						现有用户识别码:
					</label>
					<div class="form-item"><b><?=$data['user']['passkey'];?></b></div>
				</div>
				<div class="form-field">
					<label>
						个人Tracker地址:
					</label>
					<div class="form-item">
						<textarea onmouseover="this.select()" onfocus="this.select()" class="trackerlink" readonly><?=str_replace('{$passkey}', $data['user']['passkey'], $data['setting']['tracker_url']);?></textarea>
					</div>
				</div>
				<div class="form-field">
					<label>请输入登录密码:</label>
					<div class="form-item">
						<input type="password" name="password" id="password" class="px p_fre" onkeypress="if(event.keyCode==13){post_form('reset-passkey','/user/reset_passkey/');}">
					</div>
				</div>
					
				<div class="form-field">
					<label>&nbsp;</label>
					<div class="form-item">
						<input type="hidden" name="inajax" id="inajax" value="1">
						<input type="button" id="reset-passkey" class="button button-blue" value="提交">
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="box">
		<div class="box-header">
			<span class="box-title">保种积分兑换邀请</span>
			<ul class="box-toolbar">
				<li></li>
			</ul>
		</div>
		<div class="box-content">
			<form id="buy-invite-form" method="post" action="/invite/buy" onsubmit="return confirm('是否购买邀请码？');">
				<div class="form-field">
					<label>
						兑换比例:
					</label>
					<div class="form-item">当前兑换比率为 <b><?=$data['current_invite_price'];?></b> 保种积分可兑换一个邀请码。</div>
				</div>
				<div class="form-field">
					<label>兑换邀请个数:</label>
					<div class="form-item">
						<select name="invitecount" id="invitecount" style="width:135px;">
							<option value="1">1</option>
						</select>
					</div>
				</div>
				<div class="form-field">
					<label>&nbsp;</label>
					<div class="form-item">
						<input type="hidden" name="issubmit" value="1">
						<input type="submit" id="buy-invite" class="button button-blue" value="提交">
						<input type="button" onclick="window.location.href='/invite/index/'" class="button" value="查看我的邀请">
					</div>
				</div>
			</form>
		</div>
	</div>

</div>
<div class="box-group">
	<?php if (!empty($data['setting']['money2uploaded_need_money'])): ?>
	<div class="box">
		<div class="box-header">
			<span class="box-title">论坛金币兑换虚拟上传流量</span>
			<ul class="box-toolbar">
				<li></li>
			</ul>
		</div>
		<div class="box-content">
			<form id="money-to-uploaded-form" onsubmit="return false;">
				<div class="page-header">
					<h4>仅共享率过低的受限用户可以兑换!</h4>
				</div>
				<div class="form-field">
					<label>
						论坛金币数:
					</label>
					<div class="form-item"><b><?=$data['user_money'];?></b>个</div>
				</div>
				<div class="form-field">
					<label>
						兑换上限:
					</label>
					<div class="form-item">
						一次最多兑换<b><?=$data['setting']['money2uploaded_max'];?></b>个论坛金币
					</div>
				</div>
				<div class="form-field">
					<label>
						兑换比例:
					</label>
					<div class="form-item">
						<b><?=$data['setting']['money2uploaded_need_money'];?></b>个论坛金币可兑换1G虚拟上传流量
					</div>
				</div>
				<div class="form-field">
					<label>
						兑换时间间隔:
					</label>
					<div class="form-item">
						每<b><?=$data['setting']['money2uploaded_days_interval'];?></b>天可以兑换一次
					</div>
				</div>
				<div class="form-field">
					<label>请输入论坛金币数:</label>
					<div class="form-item">
						<input type="text" name="money" id="money" class="px p_fre" onkeypress="if(event.keyCode==13){post_form('money-to-uploaded','/toolbox/money2uploaded/');}">
					</div>
				</div>
				<div class="form-field">
					<label>&nbsp;</label>
					<div class="form-item">
						<input type="hidden" name="inajax" id="inajax" value="1">
						<input type="button" id="money-to-uploaded" class="button button-blue" value="提交">
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php endif;?>

	<?php if (!empty($data['setting']['extcredits12uploaded_need_extcredits1'])): ?>
	<div class="box">
		<div class="box-header">
			<span class="box-title">保种积分兑换虚拟上传流量</span>
			<ul class="box-toolbar">
				<li></li>
			</ul>
		</div>
		<div class="box-content">
			<form id="extcredits1-to-uploaded-form" onsubmit="return false;">
				<div class="page-header">
					<h4>仅共享率过低的受限用户可以兑换!</h4>
				</div>
				<div class="form-field">
					<label>
						现有保种积分:
					</label>
					<div class="form-item"><b><?=$data['user_extcredits1'];?></b></div>
				</div>
				<div class="form-field">
					<label>
						兑换上限:
					</label>
					<div class="form-item">
						一次最多兑换<b><?=$data['setting']['extcredits12uploaded_max'];?></b>保种积分
					</div>
				</div>
				<div class="form-field">
					<label>
						兑换比例:
					</label>
					<div class="form-item">
						<b><?=$data['setting']['extcredits12uploaded_need_extcredits1'];?></b>保种积分可兑换1G虚拟上传流量
					</div>
				</div>
				<div class="form-field">
					<label>
						兑换时间间隔:
					</label>
					<div class="form-item">
						每<b><?=$data['setting']['extcredits12uploaded_days_interval'];?></b>天可以兑换一次
					</div>
				</div>
				<div class="form-field">
					<label>请输入保种积分数:</label>
					<div class="form-item">
						<input type="text" name="extcredits1" id="extcredits1" class="px p_fre" onkeypress="if(event.keyCode==13){post_form('extcredits1-to-uploaded','/toolbox/extcredits12uploaded/');}">
					</div>
				</div>
				<div class="form-field">
					<label>&nbsp;</label>
					<div class="form-item">
						<input type="hidden" name="inajax" id="inajax" value="1">
						<input type="button" id="extcredits1-to-uploaded" class="button button-blue" value="提交">
					</div>
				</div>
			</form>
		</div>
	</div>
	<?php endif;?>

</div>

</div>
</div><!--wp-->

<script>
var post_form = function(obj,url){
	$.post(url,$("#"+obj+"-form").serialize(),function(data){
		ui.notify('提示', data.msg).effect('slide');
		if (data.error==false) {
			window.location.href = location.href;
		};
	},"json")
}
$(function(){
	$("#reset-passkey").click(function(){
		post_form("reset-passkey","/user/reset_passkey/");
	});
	$("#money-to-uploaded").click(function(){
		post_form("money-to-uploaded","/toolbox/money2uploaded/");
	});
	$("#extcredits1-to-uploaded").click(function(){
		post_form("extcredits1-to-uploaded","/toolbox/extcredits12uploaded/");
	});
})
</script>
<?php
include 'footer.php';
?>