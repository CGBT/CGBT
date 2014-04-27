<table cellspacing="0" cellpadding="0" class="torrenttable">
	<tr>
		<th width="38"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o8">类别</a></th>
		<th class="l">名称 (种子数目:<?=$data['torrents_count']?>)</th>
		<th width="50">操作</th>
		<th width="55"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o6">大小</a></th>
		<th width="30"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o7">文件</a></th>
		<?php if ($data['controller_name'] != 'audit_controller'): ?>
		<th width="60"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o4">点击</a></th>
		<?php endif;?>
		<th width="50"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o1">发布时间</a></th>
		<th width="26"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o2">种子</a></th>
		<?php if ($data['controller_name'] == 'audit_controller'): ?>
		<th width="50">审核结果</th>
		<?php endif;?>
		<?php if ($data['controller_name'] != 'audit_controller'): ?>
		<th width="26"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o3">下载</a></th>
		<th width="30"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o5">完成</a></th>
		<?php endif;?>
		<th width="88" align="middle">发布者</th>
	</tr>
	<?php foreach ($data['torrents'] as $key => $torrent): ?>
	<tr<?php if ($torrent['istop']): ?> class="c1"<?php endif; ?> id="t<?=$torrent['id']?>">
		<td class="icon-td"><img src="/static/images/catpic/<?=$torrent['category_icon']?>"></td>
		<td class="l">
			<?php if ($torrent['istop']): ?>
			<span class="top"><img src="/static/images/top.gif"></span>
			<?php elseif ($torrent['isrecommend']):?>
			<span class="cmd"><img src="/static/images/rec.gif"></span>
			<?php endif;?>

			<a href='/torrents/<?=$torrent['id']?>/' target='_blank' name="title"><?=$torrent['title']?></a>

			<?php if ($torrent['iscollection']): ?>
			<span class="top"><img src="/static/images/col.png"></span>
			<?php endif;?>
			<?php if ($torrent['ishd']):?>
			<span class="cmd"><img src="/static/images/hd.png"></span>
			<?php endif;?>

			<?php
			if ($torrent['isfree'] || $torrent['auto_isfree']): ?>
			<img src="/static/images/btn_free.gif">
			<?php elseif ($torrent['is30p'] || $torrent['auto_is30p']):?>
			<img src="/static/images/btn_30p.gif">
			<?php elseif ($torrent['ishalf'] || $torrent['auto_ishalf']):?>
			<img src="/static/images/btn_50p.gif">
			<?php endif;?>

			<?php if ($torrent['createtime'] > $data['user']['last_browse']): ?>
			<img src='/static/images/btn_new.gif' class="new_flag">
			<?php endif;?>
			<?php if ($torrent['upload_factor'] > 1): ?>
			<span class="factor factor<?=$torrent['upload_factor']?>">
			<a target="_blank" href="" style="color: #fff; fong-weight: bold;"><?=$torrent['upload_factor']?></a></span>
			<?php endif;?>
			</td>
		<td>
			<?php if ($data['user']['groupid'] > 5): ?>
			<?php if ( $torrent['status'] <= 0 || $torrent['istop'] || $torrent['isfree'] || $torrent['auto_isfree'] || $torrent['isrecommend'] || $torrent['uid'] == $data['uid'] || $torrent['price'] == 0 || $data['user']['is_moderator'] || $data['user']['is_admin'] ): ?>
			<a href="/torrents/<?=$torrent['id']?>/download/" class='bluelink'>下载</a>
			<?php else : ?>
			<a href="/torrents/<?=$torrent['id']?>/download/" class='bluelink' onclick="return alert_price(<?=$torrent['price']?>)">下载</a>
			<?php endif; ?>
			<?php endif; ?>
			<a tid="<?=$torrent['id']?>" src="targetBox-3" href="javascript:void(0);" class="fav_link bluelink">收藏</a><br />

			<?php if ($data['user']['is_moderator']||$data['user']['is_admin']||$data['uid']==$torrent['uid']):?>
			<a href="/torrents/<?=$torrent['id']?>/edit/" class='bluelink'>修改</a>
			<a tid="<?=$torrent['id']?>" href="javascript:void(0);" class="delete_link bluelink">删除</a>
			<?php endif;?>

			<?php if ($data['user']['is_moderator']||$data['user']['is_admin']):?>
			<span tid="<?=$torrent['id']?>" class="audit_link bluelink">审核</span>
			<?php endif;?>
		    </td>
		<td class="r"><a href='/torrents/<?=$torrent['id']?>/details/' target='_blank' class='bluelink'><?=$torrent['size_text']?></a></td>
		<td><?=$torrent['files']?></td>
		<?php if ($data['controller_name'] != 'audit_controller'): ?>
		<td><?=$torrent['view']?></td>
			<?php endif;?>
		<td><?=$torrent['simple_createtime']?></td>
		<td><?=$torrent['seeder']?></td>
		<?php if ($data['controller_name'] == 'audit_controller'): ?>
		<td class="audit-result"><?=$torrent['audit_note']?></td>
		<?php endif;?>
		<?php if ($data['controller_name'] != 'audit_controller'): ?>
		<td><?=$torrent['leecher']?></td>
		<td><?=$torrent['complete']?></td>
		<?php endif;?>
		<?php if ($torrent['anonymous']): ?>
		<td title="匿名">匿名</td>
		<?php else:?>
		<td><a href="/user/<?=$torrent['uid']?>/" data-uid="<?=$torrent['uid']?>" class="bluelink" target="_blank"><?=$torrent['user_title']?></a></td>
		<?php endif;?>
	</tr>
	<?php endforeach; ?>
</table>

<script type="text/javascript">
function alert_price(price)
{
	if (price > 0)
	{
		if (confirm('下载本种子将扣除您'+price+'保种积分,确认下载?'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
$(function(){
	$('.fav_link').click(function(){
		var tid = $(this).attr("tid");
		var url="/torrents/"+tid+"/favorite";
		$.post(url,{},function(data){
			ui.notify('提示', data).effect('slide');
		});
	});
	$("body").trigger("powerfloat");
})

</script>

<?php if ($data['user']['is_moderator']||$data['user']['is_admin']):?>
<?php include "include_auditbox.php"; ?>
<?php endif; ?>

<?php include "include_deletebox.php"; ?>