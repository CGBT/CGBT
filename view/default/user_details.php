<?php
include 'header.php';
?>
<!-- add animation background -->
<script src="/static/js/animatedbg.js"></script>

<link rel="stylesheet" type="text/css" href="/static/css/search.css" >
<div id="wp" class="wp">
<div id="ct" class="ct2_a wp cl box-inside-detail">
<div class="user-box" style="background-color:<?=$data['u']['group_color']?>">
	<div class="user-avatar">
		<img src="<?=$data['setting']['forums_url']?>uc_server/avatar.php?uid=<?=$data['u']['forums_uid']?>&size=middle" alt="头像">
	</div>
	<div class="clearfix"></div>
</div>
<div class="username">
	<h2><?=$data['u']['username']?><span class="fade small">(<?=$data['u']['title']?>)</span></h2>
<ul class="nav">

<li class="active"><a href="javascript:void(0);" class="action-tab-btn" id="info"><img src="/static/images/user-s.png" align="absmiddle"> 用户信息</a></li>
<li><a href="javascript:void(0);" class="action-tab-btn" id="uploaded"><img src="/static/images/cloud-upload.png" align="absmiddle"> 发布的种子(<?=$data['u']['total_upload_count']?>)</a></li>
<li><a href="javascript:void(0);" class="action-tab-btn" id="seeding"><img src="/static/images/arrow-up.png" align="absmiddle"> 正在做种(<?=$data['u']['seed_count']?>)</a></li>
<li><a href="javascript:void(0);" class="action-tab-btn" id="leeching"><img src="/static/images/arrow-down.png" align="absmiddle"> 正在下载(<?=$data['u']['leech_count']?>)</a></li>
<li><a href="javascript:void(0);" class="action-tab-btn" id="completed"><img src="/static/images/cloud-download.png" align="absmiddle"> 下载完成</a></li>
<li><a href="<?=$data['setting']['forums_url']?>home.php?mod=spacecp&ac=pm&op=showmsg&touid=<?=$data['u']['forums_uid']?>" target="_blank"><img src="/static/images/mail.png" align="absmiddle">  私信</a></li>

</ul>
</div>
<div class="user-info-field clearfix">
	<div class="comment-item" style="display:none;">
		<span class="button-group pull-right">
		    <a href="javascript:void(0);" class="button button-icon icon-prev">&lt;</a>
		    <a href="javascript:void(0);" class="button button-icon icon-next">&gt;</a>
		</span>
		<span class="pull-left header"></span>
		<div class="clearfix"></div>
	</div>
	<div class="tab-box info">
		<div class="tabs-group">
			<div class="user-details-tabs">
				<div>
					<h2>用户信息</h2>
					<p>UID：<?=$data['u']['uid']?></p>
					<p>用户名：<?=$data['u']['username']?></p>
					<p>昵称：<?=$data['u']['title']?></p>
					<?php if ($data['user']['is_admin']) :?>
					<p>用户识别码：<?=$data['u']['passkey']?></p>
					<?php endif;?>
					<p>上次访问IP：<?=$data['u']['last_ip']?></p>
					<p>上次访问IPv6：<?=$data['u']['last_ipv6']?></p>
					<p>上次访问时间：<?=date("Y-m-d H:i:s", $data['u']['last_access'])?></p>
					<p>上次IPv6访问时间：<?=date("Y-m-d H:i:s", $data['u']['last_access_ipv6'])?></p>
					<p>注册时间：<?=date("Y-m-d H:i:s", $data['u']['createtime'])?></p>
				</div>
			</div>
			<div class="user-details-tabs purple">
				<div>
					<h2>积分信息</h2>
					<p style="color:<?=$data['u']['group_color']?>">用户组：<?=$data['u']['group_name']?> <a href="/user/group/" class="bluelink" target="_blank">查看权限</a></p>
					<p>共享率: <?=$data['u']['ratio']?></p>
					<p>上传: <?=$data['u']['uploaded_sum_text']?></p>
					<p>下载: <?=$data['u']['downloaded_text']?></p>
					<p>保种积分: <?=$data['u']['extcredits1_text']?></p>
					<p>总积分：<?=$data['u']['total_credits']?></p>
				</div>
			</div>
		</div>
		<div class="tabs-group">
			<div class="user-details-tabs orange">
				<div>
					<h2>用户种子信息</h2>
					<p>发布种子总数：<?=$data['u']['total_upload_count']?></p>
					<p>下载种子总数：<?=$data['u']['total_completed_count']?></p>
					<p>当前做种数量：<?=$data['u']['seed_count']?></p>
					<p>当前下载数量：<?=$data['u']['leech_count']?></p>
					<p>以下几项数据暂未更新：</p>
					<p>发种次数：<?=$data['u']['total_upload_times']?></p>
					<p>发布种子容量：<?=$data['u']['total_torrent_size']?></p>
					<p>当前做种容量：<?=$data['u']['total_torrent_size']?></p>
					<p>下载次数：<?=$data['u']['total_download_times']?></p>
				</div>
			</div>
			<div class="user-details-tabs green">
				<div>
					<h2>排名信息（本数据暂未更新）</h2>
					<p>上传排名：0</p>
					<p>共享率排名：0</p>
					<p>下载排名：0</p>
					<p>积分排名：0</p>
					<p>种子容量排名：0</p>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-box seeding"></div>
	<div class="tab-box uploaded"></div>
	<div class="tab-box leeching"></div>
	<div class="tab-box completed"></div>
	<div class="comment-item" style="display:none;">
		<span class="button-group pull-right">
		    <a href="javascript:void(0);" class="button button-icon icon-prev">&lt;</a>
		    <a href="javascript:void(0);" class="button button-icon icon-next">&gt;</a>
		</span>
		<span class="pull-left header"></span>
		<div class="clearfix"></div>
	</div>
</div>

</div>
</div><!--wp-->
<script>
$(function(){
	var target,pagenum=1;
	$(".action-tab-btn").click(function(){
		target = $(this).attr("id");
		$(".nav li.active").removeClass("active");
		$(this).parent().addClass("active");
		if (target=="info") {
			$(".tab-box").hide();
			$(".comment-item").hide();
			$("."+target).show();
		}
		else{
			$(".tab-box").hide();
			$(".comment-item").show();
			$("."+target).show();
			loading("start");
			$("."+target).load("/user/<?=$data['u']['uid']?>/"+target,function(){loading("over");});
			pagenum=1;
			return pagenum;
		}
		return target;
	});
	$(".icon-next").click(function(){
		pagenum++;
		pageturn(pagenum);
		return pagenum;
	});
	$(".icon-prev").click(function(){
		if (pagenum>1) {
			pagenum--;
			pageturn(pagenum);
			return pagenum;
		}
		else{
			ui.notify('提示',"已经到第一页了！").effect('slide');
		}
	});
	function pageturn(num){
		loading("start");
		$("."+target).load("/user/<?=$data['u']['uid']?>/"+target+"/p"+num,function(){loading("over");});
	};
})
</script>
<?php
include 'footer.php';
?>