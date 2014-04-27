<table cellspacing="0" cellpadding="0" class="torrenttable">
	<tr>
		<th width="60"><a href="javascript:void(0);" type="link" val="o8">分类</a></th>
		<th class="l">名称 (数目:<?=$data['book_count']?>)</th>
		<th width="150"><a href="javascript:void(0);" type="link" val="o6">教材适合学院</a></th>
		<th width="80"><a href="javascript:void(0);" type="link" val="o6">所在位置</a></th>
		<th width="55"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o2">价格</a></th>
		<th width="55"><a href="javascript:void(0);" type="link" val="o6">已售出</a></th>
		<th width="50">操作</th>
		<th width="50"><a href="javascript:void(0);" onclick="check(this);" type="link" val="o1">发布时间</a></th>
		<th width="88" align="middle">发布者</th>
	</tr>
	<?php foreach ($data['books'] as $key => $book): ?>
	<tr<?php if ($book['istop']): ?> class="c1"<?php endif; ?> id="t<?=$book['id']?>">
		<td class="icon-td"><?=$book['category']?></td>
		<td class="l">
			<?php if ($book['istop']): ?>
			<span class="top"><img src="/static/images/top.gif"></span>
			<?php elseif ($book['isrecommend']):?>
			<span class="cmd"><img src="/static/images/rec.gif"></span>
			<?php endif;?>

			<a href='/book/<?=$book['id']?>/' target='_blank' name="title"><?=$book['title']?></a>

			<?php
			if ($book['isfree']): ?>
			<img src="/static/images/btn_free.gif">
			<?php endif;?>

			</td>

		<td><?=$book['school']?></td>
		<td><?=$book['building']?></td>
		<td><?=$book['price']?> 元</td>
		<td><?=$book['sold']?></td>
		<td>
			<?php if (isset($data['user']) && ($data['user']['is_moderator']||$data['user']['is_admin']||$data['uid']==$book['uid'])):?>
			<a href="/book/<?=$book['id']?>/edit/" class='bluelink'>修改</a>			
			<?php endif;?>
	    </td>
		<td><?=$book['simple_createtime']?></td>
		<td title="<?=$book['username']?>"><a href="/user/<?=$book['uid']?>/" data-uid="<?=$book['uid']?>" class="bluelink" target="_blank"><?=$book['username']?></a></td>
	</tr>
	<?php endforeach; ?>
</table>


