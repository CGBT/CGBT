<?php
/**
 * 管理后台菜单及权限配置文件
 */
return array(
	1 => array(
		'name' => '首页',
		'child' => array(
			//controller|action|name|is_menu
			"index|index|站点数据(实时)|1",
			"index|index2|站点数据(对比)|1",
			"index|memcache|缓存统计|1",
			"index|getcache|查看缓存|1",
			"index|clearcache|清理缓存|1",
			"index|daystats|日统计|1",
			"index|myip_stat|保种机统计|1",
			"index|phpinfo|phpinfo|1",
			"index|credits|积分日志|1",
			"index|agentinfo|客户端统计|1",
			"index|logslogin|登录日志|1",
			"torrentindex|index|批量更新索引|1",
			"torrentindex|update|批量更新索引-更新|0",
			"keyword2pinyin|index|更新关键词拼音|1",
			"keyword2pinyin|update|更新关键词拼音-更新|0"
		)
	),
	2 => array(
		'name' => '系统设置',
		'child' => array(
			//controller|action|name|is_menu
			"setting|index|系统设置|1",
			"setting|admins|管理员设置|1",
			"setting|forums|论坛设置|1",
			"setting|tracker|Tracker设置|1",

			"setting|credits|积分设置|1",
			"setting|upload|发布种子|1",

			"setting|rule|规则设置|1",
			"setting|newbie|新手考核设置|1"
		)
	),
	3 => array(
		'name' => '分类设计',
		'child' => array(
			//controller|action|name|is_menu
			"category|index|分类管理|1",
			"category|edit|分类管理-修改|0",
			"category|insert|分类管理-插入|0",
			"category|update|分类管理-更新|0",

			"category_options|index|分类表单设计|1",
			"category_options|edit|分类表单设计-修改|0",
			"category_options|insert|分类表单设计-插入|0",
			"category_options|update|分类表单设计-更新|0",

			"category|rules|分类规则|1",
			"category|rules_update|分类规则-更新|0"
		)
	),

	4 => array(
		'name' => '用户管理',
		'child' => array(
			//controller|action|name|is_menu
			"user|search|用户查询|1",
			"user|list|用户列表|1",
			"user|edit_group|修改用户组|1",
			"user|update_group|修改用户组-更新|0",
			"user|edit_credits|修改用户积分|1",

			"users_group|index|用户组列表|1",
			"users_group|edit|用户组-修改|0",
			"users_group|update|用户组-更新|0",
			"users_group|editadmin|用户组-修改管理权限|0",
			"users_group|updateadmin|用户组-更新管理权限|0",


			"privileges|index|权限管理|1",
			"privileges|edit|权限管理-修改|0",
			"privileges|insert|权限管理-插入|0",
			"privileges|update|权限管理-更新|0",

			"privileges|front|前台权限|1",
			"privileges|back|后台权限|0",
			"privileges|addfront|添加前台权限|0",
			"privileges|addback|添加后台权限|0",

			"bans|add|封禁用户权限|1",
			"bans|index|封禁列表|1",
			"bans|insert|封禁用户-插入|0",
			"bans|edit|封禁用户-修改|0",
			"bans|update|封禁用户-更新|0"
		)
	)
);
