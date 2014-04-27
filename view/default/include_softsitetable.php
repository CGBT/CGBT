<table cellspacing="0" cellpadding="0" class="torrenttable">
	<tr>
		<th width="80"><a href="javascript:void(0);" type="link" val="o8">平台</a></th>
		<th width="60"><a href="javascript:void(0);" type="link" val="o8">分类</a></th>
		<th class="l">名称 (数目:<?=$data['softsite_count']?>)</th>
		<th width="50">操作</th>
		<th width="55"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o2">价格</a></th>
		<th width="55"><a href="javascript:void(0);" type="link" val="o6">大小</a></th>
		<th width="26"><a href="javascript:void(0);" type="link" val="o3">下载</a></th>
		<th width="50"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o1">发布时间</a></th>
		<th width="88" align="middle">发布者</th>
	</tr>
	<?php foreach ($data['softsites'] as $key => $softsite): ?>
	<tr<?php if ($softsite['istop']): ?> class="c1"<?php endif; ?> id="t<?=$softsite['id']?>">
		<td class="icon-td"><?=$softsite['district']?></td>
		<td class="icon-td"><?=$softsite['category']?></td>
		<td class="l">
			<?php if ($softsite['istop']): ?>
			<span class="top"><img src="/static/images/top.gif"></span>
			<?php elseif ($softsite['isrecommend']):?>
			<span class="cmd"><img src="/static/images/rec.gif"></span>
			<?php endif;?>

			<a href='/softsite/<?=$softsite['id']?>/' target='_blank' name="title"><?=$softsite['title']?></a>


			</td>
		<td>
			<?php if ($data['user']['is_moderator']||$data['user']['is_admin']||$data['uid']==$softsite['uid']):?>
			<a href="/softsite/<?=$softsite['id']?>/edit/" class='bluelink'>修改</a>
			<a softsiteid="<?=$softsite['id']?>" href="javascript:void(0);" class="delete_link bluelink">删除</a>
			<?php endif;?>
			<a href="/softsite/<?=$softsite['id']?>/download/" class='bluelink' onclick="return alert_price(<?=$softsite['price']?>)">下载</a>
	    </td>
		<td><?=$softsite['price']?></td>
		<td><?=$softsite['size_text']?></td>
		<td><?=$softsite['download']?></td>
		<td><?=$softsite['simple_createtime']?></td>
		<td title="<?=$softsite['username']?>"><a href="/user/<?=$softsite['uid']?>/" data-uid="<?=$softsite['uid']?>" class="bluelink" target="_blank"><?=$softsite['username']?></a></td>
	</tr>
	<?php endforeach; ?>
</table>

