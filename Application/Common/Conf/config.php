<?php
return array (
		// '配置项'=>'配置值'
		// 数据库配置信息
		'DB_TYPE' => 'mysqli', // 数据库类型
		'DB_HOST' => '192.168.16.245', // 服务器地址
		'DB_NAME' => 'db_browser', // 数据库名
		'DB_USER' => 'root', // 用户名
		'DB_PWD' => 'Ydsh201407', // 密码
		'DB_PORT' => 3306, // 端口
		'DB_PREFIX' => 'tb_',  // 数据库表前缀
		'DB_FIELDS_CACHE'=>true,
		'DEFAULT_CHARSET'       =>  'utf-8', // 默认输出编码
		'DEFAULT_TIMEZONE'      =>  'PRC',
//		'DB_SQL_BUILD_CACHE' => true,
		'SHOW_PAGE_TRACE' =>false,
		'FIRE_SHOW_PAGE_TRACE' => false,
		
		'TOKEN_ON'=>true,  // 是否开启令牌验证
		'TOKEN_NAME'=>'__hash__',    // 令牌验证的表单隐藏字段名称
		'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则 默认为MD5
		'TOKEN_RESET'=>true,  //令牌验证出错后是否重置令牌 默认为true
		'VAR_FILTERS'=>'htmlspecialchars,stripslashes,strip_tags',//I方法过滤,防止xss攻击,sql注入
		'DEFAULT_FILTER'=>'htmlspecialchars,stripslashes,strip_tags',
    
                'DEFAULT_MODULE'        =>  'Home',  // 默认模块
                'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
                'DEFAULT_ACTION'        =>  'recom', // 默认操作名称
                'URL_MODEL'             =>  2,

		'ERROR_PAGE'      => __ROOT__.'/404.html',	// 错误定向页面
		'AWS_BUCKET'    => 'browser.kenxinda.com',
		'AWS_BASE_PATH' => '/Public/Upload/',
		'IS_AWS_URL'     => false,
		'IS_REDIS_CACHE' => false,
		'REDIS_CACHE_PREFIX' => 'browser_',
		'REDIS_CACHE_HOST' => '127.0.0.1',
		'REDIS_CACHE_PORT' => 6379,
		'REDIS_CACHE_EXPIRE' => 86400,
		'REDIS_PASSWORD' => '2015kxdp&.1026',	
                
);
