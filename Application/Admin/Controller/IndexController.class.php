<?php
namespace Admin\Controller;

use Think\Controller;
use Org\Util\Image;

/**
 * @Description 	后台登陆控制
 * @author 			inmyfree
 * @createtime  	2014-9-5 下午12:58:26
 */
class IndexController extends Controller {
	
	protected $breadNav_array = array();
	protected $title = '';
	
	public function _initialize(){
		
		$position_name = L('Home');
		$this->title = $position_name;
		$this->breadNav_array[] = L('Position');
		$this->breadNav_array[] = $position_name;
		
	}
	
	public function index() {
		if (session ( 'userid' )) {
// 			$this->display ( "main" );
			$this->main();
		} else {
			$this->display ( "login" );
		}
	}
	
		
	/**
	 * 面包屑
	 * @param unknown $postion_name
	 */
	protected final function head_common($postion_name,$right_array = array()){
	
		$postion_name = trim($postion_name);
		$right_array = check_array_valid($right_array) ? $right_array : array();
	
		if(strlen($postion_name) > 0){
				
			$lang_postion_name = L($postion_name);
			$this->title .= '---'.$lang_postion_name;
			$this->breadNav_array[] = $lang_postion_name;
		}
	
		$this->assign ( "title", $this->title);
		$this->assign ( "breadNav_array", $this->breadNav_array);
		$this->assign ( "last_breadNav", count($this->breadNav_array) - 1);
		$this->assign ('right_array',$right_array);
	}
	
	/**
	 * 默认欢迎界面
	 */
	public function defaultHtml(){
		
		$master_id = intval(session ('userid'));
		if($master_id > 0){
			
			$right_array = array();						
			$detail_info = array('login_prev_ip_location'=>'','login_last_ip_location'=>'','os'=>'','browse'=>'','login_prev_ip'=>'','login_prev_datetime'=>'','login_last_ip'=>'','login_last_datetime'=>'');
			$this->head_common('',$right_array);
			$login_detail_info = session('login_detail_info');
			
			if(check_array_valid($login_detail_info)){
				
				$detail_info = $login_detail_info;				
			}else{
				
				$detail_info['os'] = getOS();
				$detail_info['browse'] = getBrowse();
				$result = M('master_login')->field('ip,logintime')->where('master_id = '.$master_id.' and status = 1')->order('id desc')->limit(0,2)->select();;
				if(check_array_valid($result)){
					
					$iplocation_object = new \Org\Net\IpLocation('UTFWry.dat');
					foreach($result as $key=>$value){

						$ip_location = '';
						$ip = $value['ip'];
						$logintime = $value['logintime'];						
						$ip_location_array = $iplocation_object->getlocation($ip);					
						
						if(check_array_valid($ip_location_array)){
							
							$ip_location = filter_set_value($ip_location_array,'country','','string').filter_set_value($ip_location_array,'area','','string');
						}						
						if($key === 0){
				
							$detail_info['login_last_ip'] = $ip;
							$detail_info['login_last_datetime'] = $logintime;
							$detail_info['login_last_ip_location'] = $ip_location;
				
						}else{
				
							$detail_info['login_prev_ip'] = $ip;
							$detail_info['login_prev_datetime'] = $logintime;
							$detail_info['login_prev_ip_location'] = $ip_location;
						}
					}
				}
				
				session('login_detail_info',$detail_info);
			}
			
			$this->assign ('detail_info', $detail_info);
			$this->display ( 'default' );
		}else{
			
			header('Location:./index.html');
		}
		
	}
	
	/**
	 * 生产验证码
	 */
	Public function verify() {
		ob_end_clean();
		Image::buildImageVerify (4,5);
	}
	
	/**
	 * 登陆
	 */
	public function login() {
		
		if (IS_POST) {
			
			if(!isset($_SESSION ['login_count'])){
			
				$_SESSION ['login_count'] = 1;
			}elseif($_SESSION ['login_count'] < 10){
				
				$_SESSION ['login_count'] += 1;
			}
			if($_SESSION ['login_count'] > 4 ){
					
				$this->error ( L('LoginCountEnough'));
			}
			
			$userName = isset($_POST ['username']) ? $_POST ['username'] : '';
			$psw = isset($_POST ['psw']) ? $_POST ['psw'] : '';
			$code = isset($_POST ['code']) ? $_POST ['code'] : '';
			
			if (!isset($_SESSION ['verify']) || $code == '' || $_SESSION ['verify'] != md5 (strtolower($code))) {
				
				if(isset($_SESSION ['verify'])){
					
					unset($_SESSION ['verify']);
				}		
				
				$this->error ( L("VeriError") );
			} else if ($userName == "" || $psw == "") {
				
				unset($_SESSION ['verify']);
				
				//请填写好用户名或密码 !
				$this->error (L("FillNamePwd"));
			} else {
				
				unset($_SESSION ['verify']);
				
				$Master = M ( "Master" );
				$data = $Master->where ( 'name = "' . $userName . '" and password ="' . md5 ( $psw ) . '"' )->find ();
	
				$Master_loginModel = M("Master_login");
					
				$mlData["mname"] = $userName;
				$mlData["ip"] = get_client_ip();
				$mlData["logintime"] = date('Y-m-d H:i:s', time());
	
				if (count ( $data ) > 0) {
					
					unset($_SESSION ['login_count']);
					session ( array (
					'expire' => 600
					) );
					session ( "userid", $data ["id"] );
					session ( "username", $data ["name"] );
					
					$mlData["master_id"] = $data ["id"];
					$mlData["status"] = 1;
					$Master_loginModel->add($mlData);
					$this->redirect ( "Admin/Index/main" );
				} else {
						
					$mlData["status"] = 0;
					$Master_loginModel->add($mlData);
					//密码不正确或者用户不存在,请确认 
					$this->error ( L("PwdError") );
				}
			}
		} else {
			$this->display ( "login" );
		}
	}
	
	/**
	 * 修改管理员的密码（tb_master）
	 */
	public function updateAdminPwd() {
		
		if (IS_POST) {
			
			$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
			add_operate($operate_name,'id');
			// 获取要修改的密码
			$password = I("password");
			$repassword = I("repassword");
			if(empty($password) || empty($repassword)){
				//密码不能为空
				$this->error (L("PwdNoNull"));
			}else	if ($password != $repassword) {
				//两次输入密码不一致,请确认
				$this->error ( L("TwoPwdOK") );
			} else {
				$MasterModel = M ( "Master" );
				$Master = $MasterModel->where ( "id = " . session ( "userid" ) )->find ();
				if (count ( $Master ) > 0) {
					$Master ["password"] = md5 ( $password );
					if ($MasterModel->data ( $Master )->save () > 0) {
						//修改成功
						$this-> success(L("ModifySuccess"));
					}else{
						//修改失败
						$this->error(L("ModifyFailure"),"./updateAdminPwd",3);
					}
				}else{
					//查找不到该管理员信息！！
					$this->error(L("NoSearchAdmin"),"./updateAdminPwd",3);
				}
			}
		} else {
			$this->display ("updateAdminPwd");
		}
	}

	/**
	 * 显示后台主页面
	 */
	public function main() {
		if ($this->checkLogin ()) {
			
			$lang = I("l");
			if($lang && isset($lang)){
				session("l",I("l"));
			}
			$cateModel = M("Cate");
			$cateList = $cateModel->field("topid,topname,alink,catename,orderid,ename,topename")->order("topid,orderid")->select();
                        $resultKey = 0;
			$result = array();
			foreach ($cateList as $key=>$value){
				
				if(count($result) <= 0 || $result[$resultKey]["topid"] != $value["topid"]){
					if($key != 0){
						$resultKey++;
					}
					$result[$resultKey]["topid"] = $value["topid"];
					$result[$resultKey]["topname"] =  L($value["topename"]);//顶层菜单
					$result[$resultKey]["data"][] = array(
							"alink" => $value["alink"],
							"catename" => L($value["ename"]),//里层菜单
							"orderid" => $value["orderid"],
					);
				}else{
					$result[$resultKey]["data"][] = array(
							"alink" => $value["alink"],
							"catename" => L($value["ename"]),//里层菜单
							"orderid" => $value["orderid"],
					);
				}
			}
                        
			$this->assign("cateList",$result);
			$this->display ("main");
		}
	}
	
	/**
	 * 清除缓存
	 */
	public function cleanCache(){
		
		$base_url = getBaseURL().MODULE_NAME.'/'.CONTROLLER_NAME.'/';
              
                //clear_redis_cach();               
		if (session ( 'userid' )) {			
			
			$check_redis = C('IS_REDIS_CACHE') ? true : false;
			if($check_redis){
				
				$redis_cache_prefix = C('REDIS_CACHE_PREFIX');
				$server_name = getServerName('localhost');
				$server_name_array = explode('.',$server_name);
				if(check_array_valid($server_name_array)){
					
					$redis_cache_prefix = $server_name_array[0];
				}				
				$cache_expire = intval(C('REDIS_CACHE_EXPIRE'));
				$cache_expire = $cache_expire > 0 ? $cache_expire : 86400;
				$option_array = array(
				        'type'=>'Redis',
						'host'=> C('REDIS_CACHE_HOST'),
						'port'=> C('REDIS_CACHE_PORT'),
						'timeout'=>false,
						'persistent'=>false,
						'expire'=> $cache_expire,
						'prefix'=> $redis_cache_prefix,
						'length'=>0);
				$cache_object = S($option_array);
				$cache_source_object = $cache_object->get_object();
				if(method_exists($cache_source_object,'keys') && method_exists($cache_source_object,'delete')){
					
					$key = $redis_cache_prefix.'*';
					$keys_array = $cache_source_object->keys($key);
					if(check_array_valid($keys_array)){
					
						foreach($keys_array as $key){
					
							$cache_source_object->delete($key);
						}
					}
				}else{
					
					$result = $cache_object->clear();
				}
			}
						
			$dir_path = dirname(dirname(dirname(__FILE__))).'/Runtime';
			delete_dir($dir_path);
			
			$this->success(L('ClearSuccess'),$base_url.'index');
			
		} else {
			
			$this->redirect($base_url.'login');
		}	
	}
	
	/**
	 * 退出系统
	 */
	public function logout() {
		session ( null );
		session ( '[destroy]' );
		$this->display ( "login" );
	}
	
	/**
	 * 检测是否登陆
	 */
	protected function checkLogin() {
		if (session ( 'userid' )) {
			return true;
		} else {
			$this->redirect ( "Admin/Index/login" );
		}
	}

	public function center(){
		$this->display("center");
	}
	
	
	public function left(){
		$this->display("left");
	}
	
	public function middel(){
		$this->display("middel");
	}
	
	public function down(){
		$this->display("down");
	}

	/**
	 * 定义操作方法不存在的方法调用 404 页面
	 */
	function _empty(){
		header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
		$this->display("./Public/404.html");
	}
	
}