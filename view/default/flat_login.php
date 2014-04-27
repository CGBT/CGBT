<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>登入 - <?=$data['setting']['site_name']?></title>
    <link href="/static/css/flat-ui.css" rel="stylesheet">
    <script type="text/javascript" src="/static/js/jquery.js"></script>
    <style>
	body,body a,.login-form input,button{
		font-family: "microsoft jhenghei","microsoft yahei",tahoma;
	}
	.container{
		width: 940px;
		margin: 0 auto;
	}
	.ip-tips{
		margin-top:-2em;
		color:red;
	}
	input:focus{
		outline:none;
	}
	.login-icon {
		top: 110px;
		width: 150px;
		left: 170px;
	}
	.login-screen {
		height: 327px;
		padding: 63px 199px 83px 306px;
	}
	.btn-block{
		width: 100%;
		margin-bottom: 20px;
	}
	.hack-placeholder{
		display: none;
	}
	#home-btn{
		display: block;
		width: 39px;
		height: 39px;
		background: transparent;
		position: absolute;
		z-index: 999;
		border-radius: 100%;
		top: 574px;
		left: 452px;
	}
	</style>
	<!--[if lt IE 10]>
	<style>
	.hack-placeholder{
		right: auto;
		left: 10px;
		display: block;
	}
	</style>
	<script>
	$(function(){
		$("input[placeholder]").focus(function(){
			$(this).parent().find(".hack-placeholder").hide();
		})
		$("input[placeholder]").blur(function(){
			if(!$(this).val()){
				$(this).parent().find(".hack-placeholder").show();
			}
		})
	})
	</script>
	<![endif]-->
</head>
<body>
<div class="container">
	<?php if (!empty($data['user'])):?>
	<script>
	location.href = "/"
	</script>
	<?php endif;?>
	<div class="login mtl">
		<a href="javascript:;" id="home-btn"></a>
		<div class="login-screen">
			<?php if ($data['remain_login_fail_count'] == 0): ?>
			<p style="ip-tips">
			您的ip  <?=$data['ip']?> 在 <?=$data['setting']['login_fail_time']?> 分钟内连续 <?=$data['setting']['login_fail_count']?> 次登录失败，已被封禁登录权限，请<?=$data['setting']['login_fail_time']?> 分钟后再试。
			</p>
			<?php endif; ?>
			<div class="login-icon">
				<img src="http://zhixing.bjtu.edu.cn/static/image/common/logo/logo-115.png" alt="Welcome to ZXBT">
				<?php if ($data['remain_login_fail_count'] < 10): ?>
				<p style="margin-top:2em;"><?=$data['setting']['login_fail_time']?>分钟内连续<?=$data['setting']['login_fail_count']?>次登录失败将会被封禁IP。还有<span style="font-weight:bold;color:red;font-size:14px;"> <?=$data['remain_login_fail_count']?> </span>次机会。 </p>
				<?php endif; ?>
			</div>
			<div class="login-form">
				<form method="post" name="login" id="loginform" class="cl" onsubmit="" action="/user/login">
					<input type="hidden" name="formhash" value="fab5aaa7" />
					<div class="control-group">
						<input type="text" class="login-field" name="username" id="username" placeholder="用户名" onblur="check_user_exist();">
						<label class="login-field-icon fui-user" for="username"></label>
						<label class="login-field-icon hack-placeholder" for="username">用户名</label>
					</div>
					<div class="control-group">
						<input type="password" id="password" name="password" class="login-field" value="" placeholder="密码" autocomplete="off">
						<label class="login-field-icon fui-lock" for="password"></label>
						<label class="login-field-icon hack-placeholder" for="password">密码</label>
					</div>
					<?php if ($data['remain_login_fail_count'] != 10): ?>
					<div class="control-group">
						<input type="text" id="captcha" name="captcha" class="login-field" placeholder="验证码" autocomplete="off">
						<label class="login-field-icon" for="captcha"><img onclick="refresh_captcha();" id="captcha_img" src="" style="margin-top:-1px;" /></label>
						<label class="login-field-icon hack-placeholder" for="captcha">验证码</label>
					</div>
					<?php endif; ?>
					<div class="control-group" id="invite_tr" style="display:none">
						<input type="text" class="login-field" value="" placeholder="邀请码" id="invitecode" name="invitecode" autocomplete="off">
						<label class="login-field-icon fui-check-inverted" for="invitecode"></label>
						<label class="login-field-icon hack-placeholder" for="invitecode">邀请码</label>
					</div>
					<button class="btn btn-primary btn-large btn-block" type="submit" name="submit" value="submit" tabindex="1"><strong>Login</strong></button>
					<a title="忘记密码" href="/user/lostpassword/" style="float:right;">Get lost?</a>
					<a title="注册帐号" href="/user/register/">注册</a>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function refresh_captcha()
{
	var captcha_img = $('#captcha_img');
	now = new Date();
	captcha_img.attr('src', "/user/captcha?t=" + now.getTime());
}
$(function(){
	refresh_captcha();
})



function check_user_exist()
{
	<?php if (!$data['setting']['check_invite_code']): ?>
	return false;
	<?php endif;?>		

	var username = $("#username").val();
	if(username == '' )
	{
		return false;
	}
	$.post("/api/user/exists",{
		username: username
		},function(data){
			if (data == '0')
			{
				need_invite = true;
				$("#invite_tr").show();
			}
			else
			{
				need_invite = false;
				$("#invite_tr").hide();
			}
		});
}
$(function(){
	$("#home-btn").toggle(function(){
		$(".login-form").fadeOut(300)
		$(".login-icon").fadeOut(300)
		setTimeout($(".login-screen").attr("style","background: #000;"),300)
	},function(){
		$(".login-form").fadeIn(300)
		$(".login-icon").fadeIn(300)
		$(".login-screen").attr("style","");
	})
})
</script>

</body>
</html>