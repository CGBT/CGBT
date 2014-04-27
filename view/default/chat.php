<?php
include 'header.php';
?>
<link rel="stylesheet" type="text/css" href="/static/css/search.css" >

<div id="wp" class="wp">
	<div id="ct" class="ct2_a wp cl">
		<div id="container">
			<div id="mainContent">
				<!--search-->
				<div style="clear:both"></div>
				<div class="pagecontent">
					<ul class="nav pull-left">
						<li class="active"><a>聊天室</a></li>
					</ul>
					<div class="pager">						
					</div>
				</div>
				<div style="clear:both"></div>


				<div id="chat" style="width: 600px; height: 200px; border: 1px solid #ccc; overflow: scroll; overflow-x: hidden; margin: 0 auto; padding: 2px;"></div>
				<div style="width: 606px; margin: 0 auto; margin-top: 5px;">
				<input type="checkbox" name="scroll" checked="checked" id="autoscroll"> 自动滚屏
				&nbsp;&nbsp;&nbsp;<span onclick="clear_chat();" style="cursor: pointer;">清空记录</span>
				<br />
				<input type="text" name="txt" class="frminput" style="width: 550px;" id="txt" maxlength="250">
				<input type="hidden" name="start" id="start">
				<input type="button" value="发 言" class="button button-blue" onclick="sendchat();" id="btnsubmit">
				<br />
				<br />
				<br />
				<br />
				</div>

			</div>
			<!-- end #mainContent -->

		
		</div>
	</div>
	<!--wp-->



<script type="text/javascript">
var $chat = $("#chat");
var $txt = $("#txt");
var $start = $("#start");
var emptytimes = 1;
var sendtimes = 0;
var timeout = 0;
var interval_time = 10000;

$txt.keypress(function (e){
	if (e.which==13)
	{
		sendchat();
	}
});
function sendchat()
{
	if($txt.val()=='')
	{
		alert('请输入内容!');
		return false;
	}	
	clearTimeout(timeout);
    $('#btnsubmit').prop('disabled', true);
	$.post('/chat/say/', { start: $start.val(), txt: $txt.val()}, display_chat, 'json');
	$txt.val('');
	sendtimes++;
}
function getchat()
{
	$.post('/chat/get/', { start: $start.val()}, display_chat, 'json');	
}
function clear_chat()
{
	if(confirm("确定要清空聊天记录?"))
	{
		$chat.html('');
	}
}
function display_chat(data)
{
	var txt = data.txt;
	if(data.action == "ban")
	{
		$txt.val('你已经被封禁发言权限!');
		$txt.attr('readonly', true);
		$('#btnsubmit').hide();
		return;
	}
	else
	{
	    $('#btnsubmit').prop('disabled', false);
	}
	if(data.action == "refresh")
	{
		$chat.html('');
	}
	if (txt == '')
	{		
		clearTimeout(timeout);
		emptytimes ++;
		if(emptytimes > 6)
		{
			emptytimes = 6;
		}
		timeout = setTimeout(getchat, interval_time*emptytimes);		
	}
	else
	{
		clearTimeout(timeout);
		emptytimes --;
		emptytimes --;
		if(emptytimes < 2)
		{
			emptytimes = 1;
		}
		timeout = setTimeout(getchat, interval_time*emptytimes);		
	} 	
	$chat.html($chat.html() + data.txt);
//	$chat.text($chat.text() + data.txt);
	$start.val(data.start);
	if ($("#autoscroll").attr("checked"))
	{	
		$chat.scrollTop($chat[0].scrollHeight);
	}
}
function insert_smilies($smilies)
{
	$txt.val($txt.val() + $smilies);
}
function reply_user($username)
{
	$newtxt = $txt.val() + '@' + $username + ': ';
	$txt.val($newtxt);
}
<?php if ($data['user']['is_admin'] || $data['user']['is_moderator']) : ?>
function del(i)
{
	if(confirm('确定删除？'))
	{
		$.post('/chat/del/', { id: i }, display_chat, 'json');
	}
}
function ban(username)
{
	if(confirm('确定封禁用户'+username+'?'))
	{
		$.post('/chat/ban/', { ban_user: username }, display_chat, 'json');
	}
}
<?php endif; ?>

getchat();
timeout = setTimeout(getchat, interval_time);
</script>

<?php
include 'footer.php';
?>