<?php



/**
 * 获取服务器资源文件base url
 * @return string
 */
function getBaseURL($default_server_name = 'localhost'){
	
	if(!defined('WEBSITE_BASE_URL')){
		
		$http_protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
		$baseURL = $http_protocol . getServerName($default_server_name) . __ROOT__ . '/';
		define('WEBSITE_BASE_URL',$baseURL);
	}
 	return WEBSITE_BASE_URL;
}

/**
  *获取当前域名
  *
  **/
function getServerName($default_server_name = 'localhost'){
	
	$server_name = '';	
	if(!defined('SERVER_NAME')){
		
		$default_valid_server_name = 'themestore.kenxinda.com';
		$valid_array = array('kxdapp.com','kenxinda.com');
		$ip = get_server_ip();
		$valid_server_name = '';
		$server_name = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : (isset($default_server_name) ? trim($default_server_name) : 'localhost'));		
		if($server_name == $ip || ($ip == '127.0.0.1' && $server_name == 'localhost')){
			
			$valid_server_name = $server_name;
		}else{
			
			foreach($valid_array as $valid_value){
				
				 if(substr($server_name,- strlen($valid_value)) == $valid_value){
				 	
				 	$valid_server_name = $server_name;
				 	break;
				 }
			}
		}
		$valid_server_name = empty($valid_server_name) ? $default_valid_server_name : $valid_server_name;
		define('SERVER_NAME',$valid_server_name);
	}else{
		
		$server_name = SERVER_NAME;
	}
	return $server_name;
}

/**
 * 获取ip地址
 * @return Ambigous <string, unknown>
 */
function get_server_ip(){
	
	$server_ip = '';	
	if(isset($_SERVER)){	
			
		$server_ip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : (isset($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR'] : (isset($_SERVER['SERVER_NAME']) ? gethostbyname($_SERVER['SERVER_NAME']) : getenv('SERVER_ADDR')));		
	}else{
		
		$server_ip = getenv('SERVER_ADDR');
	}
	return $server_ip;	
}

/**
 * 获取完整的资源文件路径
 * @param unknown $filepath
 * @param string $type
 * @param string $default_server_name
 * @return string
 */
function getFullUrl($filepath,$type = 'aws',$default_server_name = 'localhost',$resource_key='default'){
	static $resource_prefix_lang_config = null,$resource_prefix_config = null;
	
	$url = $resource_prefix = '';	
	$type = trim($type);
	$check_resource_s = false;
	$type_array = array('aws','default','resource','use_config');
	$use_config_appoint_lang_array = array('zh-cn','zh-hk');
	if(is_null($resource_prefix_lang_config)){
		
		$resource_prefix_lang_config = C('RESOURCE_PREFIX_LANG_CONFIG');
		$resource_prefix_lang_config = check_array_valid($resource_prefix_lang_config) ? $resource_prefix_lang_config : array();
	}
	if(is_null($resource_prefix_config)){
		
		$resource_prefix_config = C('RESOURCE_PREFIX_CONFIG');
		$resource_prefix_config = check_array_valid($resource_prefix_config) ? $resource_prefix_config : array();
	}	
	$check_resource = check_array_valid($resource_prefix_lang_config);
	
	if($type !='resource' && defined('LANG_SET') && in_array(LANG_SET,$use_config_appoint_lang_array) && $check_resource){
		
		$type = 'use_config';		
	}else{		
		
		if(!($type == 'aws' && C('IS_AWS_URL'))){			
			
			if($type == 'resource' && check_array_valid($resource_prefix_config)){
				
				$check_resource_s = true;
				$resource_key = isset($resource_prefix_config[$resource_key]) ? $resource_key : 'default';
				$resource_prefix = $resource_prefix_config[$resource_key];
			}
			if(!$check_resource_s){
				
				$type = $check_resource ? 'use_config' :'default';
			}			
		}		
		$type = in_array($type,$type_array) ? $type : 'default';		
	}
	if($type == 'use_config'){
		
		$lang_iso = defined('LANG_SET') ? LANG_SET : C('DEFAULT_LANG',null,'en-us');		
		if(!isset($resource_prefix_lang_config[$lang_iso])){
			
			foreach($resource_prefix_lang_config as $key=>$value){
				
				$resource_prefix_lang_array = $value;
				break;
			}
		}else{
			
			$resource_prefix_lang_array = $resource_prefix_lang_config[$lang_iso];
		}
		
		$rand_key = array_rand($resource_prefix_lang_array,1);
		$resource_prefix = $resource_prefix_lang_array[$rand_key];
	}
	switch($type){
		
		case 'aws':

			$url = getAwsFullUrl($filepath);
			break;
		case 'resource':
		case 'use_config':
		case 'default':
		default:
			
			$filepath = trim($filepath);			
			if(strlen($filepath) > 0){
				
				if(substr($filepath,0,2) == './'){
					
					$filepath = substr($filepath,2);
				}elseif(substr($filepath,0,1) == '/'){
					
					$filepath = substr($filepath,1);
				}
			}
			if($type == 'use_config' || $type == 'resource'){
				
				$url = $resource_prefix.$filepath;
			}else{
				
				getBaseURL($default_server_name);
				$base_url = WEBSITE_BASE_URL;
				//$base_url = 'http://s3-ap-southeast-1.amazonaws.com/'.C('AWS_BUCKET').'/';
				$url = $base_url.$filepath;
			}			
			break;
	}
	
	return $url;
}

/**
 * 获取aws路径
 * @param unknown $filepath
 * @return Ambigous <\AwsVendor\Ambigous, string>
 */
function getAwsFullUrl($filepath){
	
	static $aws_vendor_object = null;
	$aws_url = '';
	if(is_null($aws_vendor_object) || !is_object($aws_vendor_object)){
		
		$aws_vendor_object = new \AwsVendor\AwsVendor(C('AWS_BUCKET'),C('AWS_BASE_PATH'));		
	}
	$aws_url = $aws_vendor_object->get_plain_url($filepath);
	
	return $aws_url;
}

/**
 * 删除aws文件
 * @param unknown $filepath
 * @return boolean
 */
function deleteAwsFile($filepath){
	
	static $aws_vendor_object = null;
	
	if(is_null($aws_vendor_object) || !is_object($aws_vendor_object)){
		
		$aws_vendor_object = new \AwsVendor\AwsVendor(C('AWS_BUCKET'),C('AWS_BASE_PATH'));		
	}
	
	return $aws_vendor_object->delete_process($filepath);
}

/**
 * 判别是否为数组且至少有一个元素
 * @param unknown $result
 * @return boolean
 */
function check_array_valid($result){
	
	$check = is_array($result) && count($result) > 0 ? true : false;	
	return $check;
}

/**
 * 通过已知数据进行获取值，强制返回值数据类型，没有赋予默认值
 * @param unknown $source_value
 * @param unknown $set_name
 * @param unknown $default_value
 * @param string $return_type
 * @return Ambigous <NULL, unknown>|NULL
 */
function filter_set_value($source_value,$set_name,$default_value = array(),$return_type = 'string'){
	
	$value = null;
	$set_name = trim($set_name);
	if(strlen($set_name) > 0){
		
		$return_type = trim($return_type);		
		$return_type_array = array('boolean','int','float','string','array','object');
		if(!is_null($source_value)){
			
			$source_type = is_object($source_value) ? 'object' : 'array';
		}else{
			
			$source_type = 'null';
		}
		
		$return_type = in_array($return_type,$return_type_array) ? $return_type : 'string';
		
		switch($source_type){
			
			case 'array':
				
				$value = isset($source_value[$set_name]) ? $source_value[$set_name] : $default_value;
				
				break;
			case 'object':
				
				$value = isset($source_value->$set_name) ? $source_value->$set_name : $default_value;				
				break;
			case 'null':
				
				$value = $default_value;
				break;
			
		}
		
		$current_type = gettype($value);
		if($current_type === $return_type){
			
			return $value;
		}
		switch($return_type){
			case 'boolean':
					
				$value = (bool) $value;
				break;
			case 'int':
					
				$value = intval($value);
				break;
			case 'float':
					
				$value = floatval($value);
				break;
			case 'string':
					
				$value = strval($value);
				break;
			case 'array':
					
				$value = (array) $value;
				break;
			case 'object':
					
				$value = null;
				break;
		}
		return $value;
	}	
	return $value;
}

/**
 * 判别是否设置，强制返回值数据类型，没有赋予默认值
 * @param unknown $source_value
 * @param string $default_value
 * @param string $return_type
 * @return NULL|unknown|Ambigous <NULL, unknown>
 */
function filter_value($source_value,$default_value = '',$return_type = 'string'){
	
	$value = null;
	$return_type = trim($return_type);
	$return_type_array = array('boolean','int','float','string','array','object');
	$return_type = in_array($return_type,$return_type_array) ? $return_type : 'string';
	if(is_null($source_value) && $return_type == 'object' && is_null($default_value)){
		
		return $value;
	}		
	$value = is_null($source_value) ? $default_value : $source_value;
	$current_type = gettype($value);	
	if($current_type === $return_type){
			
		return $value;
	}
	
	switch($return_type){
		case 'boolean':

			$value = (bool) $value;
			break;
		case 'int':
				
			$value = intval($value);
			break;
		case 'float':
				
			$value = floatval($value);
			break;
		case 'string':
				
			$value = strval($value);
			break;
		case 'array':
				
			$value = (array) $value;
			break;
		case 'object':
				
			$value = null;
			break;
	}
		
	return $value;
}

//判别邮箱地址
function is_email($email){

	$chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
	if (strpos($email, '@') !== false && strpos($email, '.') !== false){

		if (preg_match($chars, $email)){
				
			return true;
		}else{
				
			return false;
		}
	}else{

		return false;
	}
}

//帅选关键词
function get_filter_keyboards(){
	
	$filter_keyboards_array = array();
	$filter_keyboards_file = BASE_DIR.'Public/filter_keywords.txt';
	if(file_exists($filter_keyboards_file)){
			
		$file_array = file($filter_keyboards_file);
		if(check_array_valid($file_array)){

			foreach($file_array as $value){
					
				$filter_keyboards_array[] = trim($value);
			}
		}
	}
	return $filter_keyboards_array;
}

//判别用户名合法性
function is_user_name($user_name){
	
	$check = false;
	$user_name = strlen(trim($user_name)) > 0 ? trim($user_name) : '';
	if(strlen($user_name) > 0){
		
		$check = preg_match("/^[^-_][\x{4e00}-\x{9fa5}a-zA-Z0-9_-]+$/u",$user_name);//utf8 包括中文，字母验证
		if($check){				
			
			$filter_keyboards_array = get_filter_keyboards();
			if(check_array_valid($filter_keyboards_array)){
				
				$check = in_array($user_name,$filter_keyboards_array) ? false : true;
				if($check){
					
					$user_name = strtolower($user_name);
					foreach($filter_keyboards_array as $filter_keyboards){
						
						$filter_keyboards = strtolower($filter_keyboards);						
						if(strpos($user_name,$filter_keyboards) !== false){
							
							$check = false;
							break;
						}
					}			
				}
			}
		}
	}		
	return (bool) $check;		
}
	
//判别是否手机
function is_tel($tel){

	$tel_check = false;
	$tel = strlen(trim($tel)) > 0 ? trim($tel) : '';
	if(strlen($tel) >= 6 && is_numeric($tel)){
			
		$tel_check = true;
		//$tel_check = preg_match('/^0?1[358]\d{9}$/',$tel);
	}
	return (bool) $tel_check;
}

//判别是否为合法格式的日期时间
function is_date($date){
	
	if (!preg_match('/^([0-9]{4})-((0?[1-9])|(1[0-2]))-((0?[1-9])|([1-2][0-9])|(3[01]))( [0-9]{2}:[0-9]{2}:[0-9]{2})?$/ui', $date, $matches)){
			return false;
	}
	return checkdate(intval($matches[2]), intval($matches[5]), intval($matches[0]));
}

/*获取客服端浏览器*/
function getBrowse(){

	$Agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	$browseinfo='';
	if(preg_match('/Mozilla/', $Agent) && !preg_match('/MSIE/', $Agent)){
		
		$browseinfo = 'Netscape Navigator';
	}
	if(preg_match('/Opera/', $Agent)) {
		
		$browseinfo = 'Opera';
	}
	if(preg_match('/Mozilla/', $Agent) && preg_match('/MSIE/', $Agent)){

		$browseinfo = 'Internet Explorer';
	}	
	if(preg_match('/Safari/', $Agent)){
		
		$browseinfo="Safari";
	}
	if(preg_match('/Chrome/', $Agent)){
	
		$browseinfo="Chrome";
	}
	if(preg_match('/Firefox/', $Agent)){
		
		$browseinfo="Firefox";
	}
	if(strlen($browseinfo) <= 0){
		
		$browseinfo = 'Unknown';
	}

	return $browseinfo;
}

//获取邮件的服务器地址
function get_mail_url($email){

	$url = '';
	$email = strlen(trim($email)) > 0 && is_email(trim($email)) ? trim($email) : '';
	if(strlen($email) > 0){
			
		$email_array = explode('@',$email);
		if(check_array_valid($email_array)){

			$url = 'http://';
			$end_str = strval(end($email_array));
			switch($end_str){
					
				case 'gmail.com':
						
					$url .= 'mail.google.com';//gmail邮箱
					break;
				case 'sohoo.com':
						
					$url .= 'mail.sohu.com';//搜狐老邮箱
					break;
				case 'sina.com':
						
					$url .= 'mail.sina.com.cn';//新浪邮箱
					break;
				case 'vip.sina.com':
						
					$url .= 'mail.sina.com.cn';//新浪vip邮箱
					break;
				default :
						
					$url .= 'mail@'.$end_str;//一般邮箱的地址
					break;
			}

		}
	}
		
	return $url;
}

/*获取客服端操作系统*/
function getOS(){

	$agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	$os = false;
	if (preg_match('/win/i', $agent) && strpos($agent, '95')){
		$os = 'Windows 95';
	}elseif (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')){
		$os = 'Windows ME';
	}elseif (preg_match('/win/i', $agent) && preg_match('/98/', $agent)){
		$os = 'Windows 98';
	}elseif (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)){
		$os = 'Windows XP';
	}elseif (preg_match('/win/i', $agent) && preg_match('/nt 5.2/i', $agent)){
		$os = 'Windows 2003';
	}elseif (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent)){
		$os = 'Windows 2000';
	}elseif (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)){
		$os = 'Windows NT';
	}elseif (preg_match('/win/i', $agent) && preg_match('/32/', $agent)){
		$os = 'Windows 32';
	}elseif (preg_match('/linux/i', $agent)){
		$os = 'Linux';
	}elseif (preg_match('/unix/i', $agent)){
		$os = 'Unix';
	}elseif (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)){
		$os = 'SunOS';
	}elseif (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)){
		$os = 'IBM OS/2';
	}elseif (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)){
		$os = 'Macintosh';
	}elseif (preg_match('/PowerPC/i', $agent)){
		$os = 'PowerPC';
	}elseif (preg_match('/AIX/i', $agent)){
		$os = 'AIX';
	}elseif (preg_match('/HPUX/i', $agent)){
		$os = 'HPUX';
	}elseif (preg_match('/NetBSD/i', $agent)){
		$os = 'NetBSD';
	}elseif (preg_match('/BSD/i', $agent)){
		$os = 'BSD';
	}elseif (preg_match('/OSF1/i', $agent)){
		$os = 'OSF1';
	}elseif (preg_match('/IRIX/i', $agent)){
		$os = 'IRIX';
	}elseif (preg_match('/FreeBSD/i', $agent)){
		$os = 'FreeBSD';
	}elseif (preg_match('/teleport/i', $agent)){
		$os = 'teleport';
	}elseif (preg_match('/flashget/i', $agent)){
		$os = 'flashget';
	}elseif (preg_match('/webzip/i', $agent)){
		$os = 'webzip';
	}elseif (preg_match('/offline/i', $agent)){
		$os = 'offline';
	}else{
		$os = 'Unknown';
	}
	return $os;
}

/*简单判别是否手机客服端*/
function is_mobile() {

	if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
		$is_mobile = false;
	} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
		$is_mobile = true;
	} else {
		$is_mobile = false;
	}

	return $is_mobile;
}

/*判别是否手机客服端*/
function is_client_mobile(){

	$pcFlag = false;
	$mobileFlag = false;
	$via = isset($_SERVER['HTTP_VIA']) ? $_SERVER['HTTP_VIA'] : '';
	$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

	$mobileGateWayHeaders = array( "ZXWAP",//中兴提供的wap网关的via信息，例如：Via=ZXWAP GateWayZTE Technologies，
			"chinamobile.com",//中国移动的诺基亚wap网关，例如：Via=WTP/1.1 GDSZ-PB-GW003-WAP07.gd.chinamobile.com (Nokia WAP Gateway 4.1 CD1/ECD13_D/4.1.04)
			"monternet.com",//移动梦网的网关，例如：Via=WTP/1.1 BJBJ-PS-WAP1-GW08.bj1.monternet.com. (Nokia WAP Gateway 4.1 CD1/ECD13_E/4.1.05)
			"infoX",//华为提供的wap网关，例如：Via=HTTP/1.1 GDGZ-PS-GW011-WAP2 (infoX-WISG Huawei Technologies)，或Via=infoX WAP Gateway V300R001 Huawei Technologies
			"XMS 724Solutions HTG",//国外电信运营商的wap网关，不知道是哪一家
			"wap.lizongbo.com",//自己测试时模拟的头信息
			"Bytemobile"
	);//貌似是一个给移动互联网提供解决方案提高网络运行效率的，例如：Via=1.1 Bytemobile OSN WebProxy/5.1
	$pcHeaders = array("Windows 98",
			"Windows ME",
			"Windows 2000",
			"Windows XP",
			"Windows NT",
			"Ubuntu"
	);
	$mobileUserAgents = array( "Nokia",//诺基亚，有山寨机也写这个的，总还算是手机，Mozilla/5.0 (Nokia5800 XpressMusic)UC AppleWebkit(like Gecko) Safari/530
			"SAMSUNG",//三星手机 SAMSUNG-GT-B7722/1.0+SHP/VPP/R5+Dolfin/1.5+Nextreaming+SMM-MMS/1.2.0+profile/MIDP-2.1+configuration/CLDC-1.1
			"MIDP-2",//j2me2.0，Mozilla/5.0 (SymbianOS/9.3; U; Series60/3.2 NokiaE75-1 /110.48.125 Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/413 (KHTML like Gecko) Safari/413
			"CLDC1.1",//M600/MIDP2.0/CLDC1.1/Screen-240X320
			"SymbianOS",//塞班系统的，
			"MAUI",//MTK山寨机默认ua
			"UNTRUSTED/1.0",//疑似山寨机的ua，基本可以确定还是手机
			"Windows CE",//Windows CE，Mozilla/4.0 (compatible; MSIE 6.0; Windows CE; IEMobile 7.11)
			"iPhone",//iPhone是否也转wap？不管它，先区分出来再说。Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_1 like Mac OS X; zh-cn) AppleWebKit/532.9 (KHTML like Gecko) Mobile/8B117
			"iPad",//iPad的ua，Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; zh-cn) AppleWebKit/531.21.10 (KHTML like Gecko) Version/4.0.4 Mobile/7B367 Safari/531.21.10
			"Android",//Android是否也转wap？Mozilla/5.0 (Linux; U; Android 2.1-update1; zh-cn; XT800 Build/TITA_M2_16.22.7) AppleWebKit/530.17 (KHTML like Gecko) Version/4.0 Mobile Safari/530.17
			"BlackBerry",//BlackBerry8310/2.7.0.106-4.5.0.182
			"UCWEB",//ucweb是否只给wap页面？ Nokia5800 XpressMusic/UCWEB7.5.0.66/50/999
			"ucweb",//小写的ucweb貌似是uc的代理服务器Mozilla/6.0 (compatible; MSIE 6.0;) Opera ucweb-squid
			"BREW",//很奇怪的ua，例如：REW-Applet/0x20068888 (BREW/3.1.5.20; DeviceId: 40105; Lang: zhcn) ucweb-squid
			"J2ME",//很奇怪的ua，只有J2ME四个字母
			"YULONG",//宇龙手机，YULONG-CoolpadN68/10.14 IPANEL/2.0 CTC/1.0
			"YuLong",//还是宇龙
			"COOLPAD",//宇龙酷派YL-COOLPADS100/08.10.S100 POLARIS/2.9 CTC/1.0
			"TIANYU",//天语手机TIANYU-KTOUCH/V209/MIDP2.0/CLDC1.1/Screen-240X320
			"TY-",//天语，TY-F6229/701116_6215_V0230 JUPITOR/2.2 CTC/1.0
			"K-Touch",//还是天语K-Touch_N2200_CMCC/TBG110022_1223_V0801 MTK/6223 Release/30.07.2008 Browser/WAP2.0
			"Haier",//海尔手机，Haier-HG-M217_CMCC/3.0 Release/12.1.2007 Browser/WAP2.0
			"DOPOD",//多普达手机
			"Lenovo",// 联想手机，Lenovo-P650WG/S100 LMP/LML Release/2010.02.22 Profile/MIDP2.0 Configuration/CLDC1.1
			"LENOVO",// 联想手机，比如：LENOVO-P780/176A
			"HUAQIN",//华勤手机
			"AIGO-",//爱国者居然也出过手机，AIGO-800C/2.04 TMSS-BROWSER/1.0.0 CTC/1.0
			"CTC/1.0",//含义不明
			"CTC/2.0",//含义不明
			"CMCC",//移动定制手机，K-Touch_N2200_CMCC/TBG110022_1223_V0801 MTK/6223 Release/30.07.2008 Browser/WAP2.0
			"DAXIAN",//大显手机DAXIAN X180 UP.Browser/6.2.3.2(GUI) MMP/2.0
			"MOT-",//摩托罗拉，MOT-MOTOROKRE6/1.0 LinuxOS/2.4.20 Release/8.4.2006 Browser/Opera8.00 Profile/MIDP2.0 Configuration/CLDC1.1 Software/R533_G_11.10.54R
			"SonyEricsson",// 索爱手机，SonyEricssonP990i/R100 Mozilla/4.0 (compatible; MSIE 6.0; Symbian OS; 405) Opera 8.65 [zh-CN]
			"GIONEE",//金立手机
			"HTC",//HTC手机
			"ZTE",//中兴手机，ZTE-A211/P109A2V1.0.0/WAP2.0 Profile
			"HUAWEI",//华为手机，
			"webOS",//palm手机，Mozilla/5.0 (webOS/1.4.5; U; zh-CN) AppleWebKit/532.2 (KHTML like Gecko) Version/1.0 Safari/532.2 Pre/1.0
			"GoBrowser",//3g GoBrowser.User-Agent=Nokia5230/GoBrowser/2.0.290 Safari
			"IEMobile",//Windows CE手机自带浏览器，
			"WAP2.0"//支持wap 2.0的
	);
	for($i = 0; strlen($via) >0  && $i < count($mobileGateWayHeaders);$i++){
			
		if(strpos($via,$mobileGateWayHeaders[$i]) !== false){
				
			$mobileFlag = true;
			break;
		}
	}
	for($i = 0;!$mobileFlag && $userAgent!=null && strlen($userAgent) > 0 && $i < count($mobileUserAgents); $i++){

		if(strpos($userAgent,$mobileUserAgents[$i]) !== false){
			$mobileFlag = true;
			break;
		}
	}
	for ($i = 0; $userAgent!=null && strlen($userAgent) > 0 && $i < count($pcHeaders); $i++){

		if(strpos($userAgent,$pcHeaders[$i]) !== false){
				
			$pcFlag = true;
		}
	}
	if($mobileFlag == true && $mobileFlag != $pcFlag){

		return true;
	}
	return false;

}

//判别是否md5值
function is_Md5($md5){
	
	return preg_match('/^[a-z0-9]{32}$/ui', $md5);
}

//判别是否sha1值
function is_Sha1($sha1){
	
	return preg_match('/^[a-z0-9]{40}$/ui', $sha1);
}

// 判断是否为搜索引擎蜘蛛
function is_spider(){

	static $spider = NULL;

	if ($spider !== NULL)
	{
		return $spider;
	}

	if (empty($_SERVER['HTTP_USER_AGENT']))
	{
		$spider = '';

		return '';
	}

	$searchengine_bot = array(
			'googlebot',
			'mediapartners-google',
			'baiduspider+',
			'msnbot',
			'yodaobot',
			'yahoo! slurp;',
			'yahoo! slurp china;',
			'iaskspider',
			'sogou web spider',
			'sogou push spider',
			'baiduspider',
			'baiduspider-image',
			'youdaobot',
			'sogou inst spider',
			'sogou spider2',
			'sogou blog',
			'sogou news spider',
			'sogou orion spider',
			'jikespider',
			'sosospider',
			'pangusospider',
			'yisouspider',
			'easouspider',
			'360spider'
	);

	$searchengine_name = array(
			'GOOGLE',
			'GOOGLE ADSENSE',
			'BAIDU',
			'MSN',
			'YODAO',
			'YAHOO',
			'Yahoo China',
			'IASK',
			'SOGOU',
			'SOGOU',
			'BAIDU',
			'BAIDU IMAGE',
			'YOUDAO',
			'SOGOU',
			'SOGOU',
			'SOGOU',
			'SOGOU',
			'SOGOU',
			'JIKE',
			'SOSO',
			'PANGUSO',
			'YISOU',
			'EASOU',
			'360'
	);

	$spider = strtolower($_SERVER['HTTP_USER_AGENT']);

	foreach ($searchengine_bot AS $key => $value){

		if (strpos($spider, $value) !== false){
				
			$spider = $searchengine_name[$key];
			return $spider;
		}
	}

	$spider = '';
	return '';
}

function getRandOnlyId() {
	//新时间截定义,基于世界未日2012-12-21的时间戳。
	$endtime=1356019200;//2012-12-21时间戳
	$curtime=time();//当前时间戳
	$newtime=$curtime-$endtime;//新时间戳
	$rand=rand(0,99);//两位随机
	$all=$rand.$newtime;
	$onlyid=base_convert($all,10,36);//把10进制转为36进制的唯一ID
	return $onlyid;
}
/**
 * 处理数据库图片,添加域名到文件或图片路径
 * @param 数据库图片字符串   $str_img
 * @return string
 */
 function splitImage($str_img){

	$arrays_apk_imgs = explode("&", $str_img);
	$str_imgs = "";	
	foreach ( $arrays_apk_imgs  as & $arrays_value ) {
		
		$arrays_value  =  getFullUrl($arrays_value) ;		
		$str_imgs = $str_imgs . $arrays_value."&";
	}
	unset( $arrays_value );  // 最后取消掉引用
	return substr ( $str_imgs ,  0 , - 1 );
}


/**
 * 获取上传数据中apk介绍图片的多图upload数组key,并排序
 *
 * @param 上传信息 $info
 * @return multitype:排序后的upload key array
 */
function getSortApkImgbyArr($info) {
	$keyarr = array ();
	$keyincrement = 0;
	foreach ( $info as $key => $value ) {
		if ("uploads" == mb_substr ( $key, 0, 7 )) {
			$keyarr [$keyincrement] = $key;
			$keyincrement ++;
		}
	}
	asort ( $keyarr );
	return $keyarr;
}



function randrgb()
{
	$estr = '';
	//'#'.sprintf("%02X",rand(0,255)).sprintf("%02X",rand(0,255)).sprintf("%02X",rand(0,255));
	$str='0123456789ABCDEF';
	for($i=1;$i<=6;$i++)
	{
		$estr .= $str[rand(0,15)];
	}
	return '#'.$estr;
}

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
	if(function_exists("mb_substr")){
		if ($suffix && strlen($str)>$length)
			return mb_substr($str, $start, $length, $charset)."";
		else
			return mb_substr($str, $start, $length, $charset);
	}
	elseif(function_exists('iconv_substr')) {
		if ($suffix && strlen($str)>$length)
			return iconv_substr($str,$start,$length,$charset)."";
		else
			return iconv_substr($str,$start,$length,$charset);
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice."";
	return $slice;
}

/**
 * 时间格式化
 * @param String $time:Y-m-d H:i:s
 * @return String
 */
function timeformat($time){
        $result=false;
        $data_array=explode(' ',$time);
        $ymd=$data_array[0];
        $hi=substr($data_array[1],0,strrpos($data_array[1],':'));
        $cu_ymd=date('Y-m-d'); //今天日期
        if($cu_ymd>$ymd){
            $result=$ymd;
        }else{
            $result=$hi;
        }
        return $result;
}

/**
 * 验证字符串起始值
 * @param string $str:原字符串
 * @param string $finStr:需验证的字符串起始值
 * @return boolean:验证一致返回true,否则返回false
 * */
function startWith($str,$finStr){
        $len=strlen($finStr);
        $strPre=substr($str,0,$len);
        $res=$strPre==$finStr?true:false;
        return $res;
}