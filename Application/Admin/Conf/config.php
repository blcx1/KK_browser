<?php

return array(
	//权限验证设置
	'AUTH_CONFIG'=>array(
			'AUTH_ON' => true, //认证开关
			'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
			'AUTH_GROUP' => 'tb_auth_group', //用户组数据表名
			'AUTH_GROUP_ACCESS' => 'tb_auth_group_access', //用户组明细表
			'AUTH_RULE' => 'tb_auth_rule', //权限规则表
			'AUTH_USER' => 'tb_master'//用户信息表
	),
	//超级管理员id,拥有全部权限,只要用户uid在这个角色组里的,就跳出认证.可以设置多个值,如array('1','2','3')
	'ADMINISTRATOR'=>array(),

	//多语言配置
	'LANG_SWITCH_ON'     =>     true,    //开启语言包功能
	'LANG_AUTO_DETECT'   =>     true, // 自动侦测语言
	'DEFAULT_LANG'       =>    'zh-cn', // 默认语言
	'LANG_LIST'          =>    'en-us,zh-cn', //必须写可允许的语言列表
	'VAR_LANGUAGE'       => 'l', // 默认语言切换变量
	'SHOW_PAGE_TRACE' =>false,
	'FIRE_SHOW_PAGE_TRACE' => false,
	'TMPL_ACTION_SUCCESS'=> 'Index/dispatch_jump',
	'TMPL_ACTION_ERROR'=> 'Index/dispatch_jump',
        'DEFAULT_MODULE'        =>  'Admin',  // 默认模块
        'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
        'DEFAULT_ACTION'        =>  'Index', // 默认操作名称
);