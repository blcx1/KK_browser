<?php
/**
 * 默认后台管理控制器
 * @author kivenpc pcttcnc2007@126.com
 * @date 2015-12-12
 *
 */
namespace Admin\Controller;

abstract class DefaultController extends \Think\Controller{
  	 
	protected $lang_check = false;
	protected $lang_object = null;
	protected $is_cache = false;//是否cache
	protected $cache_object = null;//缓存对象
	protected $something_object = null;//模型对象
	protected $breadNav_array = array();//显示面包屑
	protected $cache_option_array = array();//缓存配置
	protected $title = '';//标题
	
	/**
	 * 初始化操作
	 */
	public function _initialize(){
		
    	if (!session('userid')) {
    		
    		$this->display ('Index/index');
    		exit;
    	}
    	
    	//权限验证
    	$name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
    	$where = array();
    	$where['name'] = array('eq',"$name");    	
    	if(M('Auth_rule')->where($where)->find()){
    		if(!authcheck($name,$_SESSION['userid'])){
    			$this->error(L('NoAuth'));//没有权限！
    		}
    	}
    	$this->is_cache = C('IS_REDIS_CACHE') ? true : false;
    	if($this->is_cache){
    		
    		$redis_cache_prefix = C('REDIS_CACHE_PREFIX');
    		$server_name = getServerName('localhost');
    		$server_name_array = explode('.',$server_name);
    		if(check_array_valid($server_name_array)){
    		
    			$redis_cache_prefix = $server_name_array[0];
    		}    		
    		$this->cache_option_array = array(
    				'type'=>'Redis',
    				'host'=> C('REDIS_CACHE_HOST'),
    				'port'=> C('REDIS_CACHE_PORT'),
    				'timeout'=>false,
    				'persistent'=>false,
    				'expire'=> C('REDIS_CACHE_EXPIRE'),
    				'prefix'=> $redis_cache_prefix,
    				'length'=>0);
    		$this->CacheConnect();
    	}    	    	
    	if($this->lang_check){
    		
    		$lang_object = new \Admin\Model\LanguageModel($this->cache_object);
    		$this->lang_object = $lang_object;
    		if(!defined('LANG_ID')){
    		
    			$lang_id = 0;
    			if(isset($_GET['lang_id']) && $lang_object->check_exists($_GET['lang_id'],true)){
    		
    				$lang_id = intval($_GET['lang_id']);
    			}elseif(isset($_COOKIE['lang_id']) && $lang_object->check_exists($_COOKIE['lang_id'],true)){
    		
    				$lang_id = intval($_COOKIE['lang_id']);
    			}else{
    		
    				$lang_id = $lang_object->get_lang_id(LANG_SET);
    				$lang_id = $lang_id > 0 ? $lang_id : 2;
    			}
    			define('LANG_ID',$lang_id);
    			setcookie('lang_id',LANG_ID,time()+3600*24,'/');
    		}    		
    	}    	
		$this->init();
	}	
	
	/**
	 * 初始化配置
	 */
	public function init(){		
	}
	
	/**
	 * 释放
	 */
	public function __destruct() {
		
		parent::__destruct();	
		$this->lang_object = null;	
		$this->cache_object = null;		
		$this->something_object = null;		
		$this->breadNav_array = array();
		$this->cache_option_array = array();
		$this->title = '';
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
		$this->assign ('title', $this->title);
		$this->assign ('breadNav_array', $this->breadNav_array);
		$this->assign ('last_breadNav', count($this->breadNav_array) - 1);
		$this->assign ('right_array',$right_array);
	}
	
	/**
	 * 缓存连接
	 * @return Ambigous <NULL, unknown, mixed, \Think\mixed, object>
	 */
	protected function CacheConnect(){
		
		$cache_object = $this->cache_object;
		if($this->is_cache && (is_null($cache_object) || !is_object($cache_object))){			
			
			$check_cache = false;
			$option_array = $this->cache_option_array;
			if(check_array_valid($option_array) && isset($option_array['type'])){
				
				$check_cache = true;
			}			
			if(!$check_cache){
					
				return $cache_object;
			}
			$cache_object = S($option_array);
			if(!is_null($cache_object)){
			
				$this->cache_object = $cache_object;				
			}
		}		
		return $cache_object;
	}
	
	/**
	 * 默认index页
	 */
	public function index(){
		
		$method = ACTION_NAME;
		$args = $_REQUEST;
		$this->_empty($method,$args);
	}
	
	/**
	 * 默认test页
	 */
	public function test(){
		
		$method = ACTION_NAME;
		$args = $_REQUEST;
		$this->_empty($method,$args);
	}
	
	/**
	 * 空方法操作
	 */
	protected function _empty($method = '',$args = array()){
		
		$base_url = getBaseURL();
		$url = $base_url.'404.html';
		redirect($url,0,'');
		
	}
	
	/**
	 * 信息查询
	 */
	abstract public function showList();
	
	/**
	 * 查询已删除的信息
	 **/
	abstract public function recycleList();
	
	/**
	 * 添加信息
	 */
	abstract public function addInfo();
	
	/**
	 * 修改信息
	*/
	abstract public function updateInfo();	
	
	/**
	 * 详情
	 */
	public function getDetail(){
	
		$id = filter_set_value($_REQUEST,'id',0,'int');
		$detail_info = $this->something_object->get_info($id);
		$base_url = getBaseURL().MODULE_NAME.'/'.CONTROLLER_NAME.'/';
		if(check_array_valid($detail_info)){
			
			$right_array = array();
			$right_array[0]['href'] = $base_url.'showList';
			$right_array[0]['title'] = L('showList');
			$this->head_common('Detail',$right_array);
			$this->assign('detail_info',$detail_info);
			$this->display('getDetail');
		}else{
	
			$this->error(L('IllegalData') ,$base_url.'showList',false);
		}	
	}
	
	/**
	 * 修改状态
	 */
	public function changStatus(){
	
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'id');
		$ajax_array = array('error'=>0,'status_value'=>'');
		$id = filter_set_value($_REQUEST,'id',0,'int');
		$status_name = filter_set_value($_REQUEST,'status','','string');
		if($id > 0 && strlen($status_name) > 0){
	
			$where = array();
			$Model_object = $this->something_object;
			$value = intval($Model_object->getFieldValue($id,$status_name));			
			$value = $value === 0 ? 1 : 0;
			$where[$Model_object->getPk()] = $id;
			$check = $Model_object->change_name($status_name,$value,$where);			
			if(!$check){
					
				$ajax_array['error'] = 1;
			}else{
	
				$value = intval($Model_object->getFieldValue($id,$status_name));
				if($status_name == 'status'){
						
					$ajax_array['status_value'] = $value === 0 ? L('StatusNo') : L('StatusYes');
				}else{
						
					$ajax_array['status_value'] = $value === 0 ? L('No') : L('Yes');
				}
			}
	
		}else{
	
			$ajax_array['error'] = 1;
		}
		echo json_encode($ajax_array);
		exit;
	}
		
	/**
	 * 删除信息
	 * 
	 */
	public function deleteInfo() {
		
		$check = false;
		$where = array();
		$Model_Object = $this->something_object;
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'id');
		$id = filter_set_value($_REQUEST,'id',0,'int');
		$id_array = filter_set_value($_REQUEST,'check',array(),'array');
		$base_url = getBaseURL().MODULE_NAME.'/'.CONTROLLER_NAME.'/';
		if($id > 0){			
			
			$check = $Model_Object->check_exists($id);
			if($check){			
				
				$where['id'] = $id;								
			}
		}elseif(check_array_valid($id_array)){
			
			$id_array_tmp = array();
			foreach($id_array as $id_value){
				
				if(is_numeric($id_value) && intval($id_value) > 0){
					
					$id_array_tmp[] = intval($id_value);
				}
			}
			if(count($id_array_tmp) > 0){
				
				$check = true;
				$where['id'] = array('in',implode(',',$id_array_tmp));
			}			
		}
		if($check){
			
			$check = $Model_Object->info_delete_process($where);
		}
		
		if($check){
			
			$this->success (L('DeleteSuccess') ,$base_url.'showList', false);//删除成功
		}else{
			
			$this->error(L('DeleteFailure') , $base_url.'showList',false);
		}		
	}
	
	/**
	 * 恢复删除的信息
	 */
	public function recoverInfo(){
			
		$check = false;
		$where = array();
		$Model_Object = $this->something_object;
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'id');
		$id = filter_set_value($_REQUEST,'id',0,'int');
		$id_array = filter_set_value($_REQUEST,'check',array(),'array');
		$base_url = getBaseURL().MODULE_NAME.'/'.CONTROLLER_NAME.'/';
		if($id > 0){
				
			$result = $Model_Object->get_info($id);
			if(check_array_valid($result)){		
				
				$check = true;
				$where['id'] = $id;								
			}
		}elseif(check_array_valid($id_array)){
			
			$id_array_tmp = array();
			foreach($id_array as $id_value){
				
				if(is_numeric($id_value) && intval($id_value) > 0){
					
					$id_array_tmp[] = intval($id_value);
				}
			}
			if(count($id_array_tmp) > 0){
				
				$check = true;
				$where['id'] = array('in',implode(',',$id_array_tmp));
			}			
		}
		if($check){
			
			$check = $Model_Object->info_recover($where);
		}
		if($check){
				
			$this->success (L('RestoreSuccess') , $base_url.'recycleList',false);//恢复成功
		}else{
				
			$this->error(L('RestoreFailed') , $base_url.'recycleList',false);
		}		
	}
	
	/**
	 * 强制删除信息
	 */
	public function forceDel(){
		
		$check = false;
		$where = array();
		$Model_Object = $this->something_object;
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'id');
		$id = filter_set_value($_REQUEST,'id',0,'int');
		$id_array = filter_set_value($_REQUEST,'check',array(),'array');
		$base_url = getBaseURL().MODULE_NAME.'/'.CONTROLLER_NAME.'/';
		if($id > 0){						
			
			$check = true;
			$where['id'] = $id;		
		}elseif(check_array_valid($id_array)){
			
			$id_array_tmp = array();
			foreach($id_array as $id_value){
				
				if(is_numeric($id_value) && intval($id_value) > 0){
					
					$id_array_tmp[] = intval($id_value);
				}
			}
			if(count($id_array_tmp) > 0){
				
				$check = true;
				$where['id'] = array('in',implode(',',$id_array_tmp));
			}			
		}
		
		if($check){
			
			$check = $Model_Object->info_force_delete($where);
		}
		if($check){
				
			$this->success (L('DeleteSuccess') , $base_url.'recycleList', false);//删除成功
		}else{
				
			$this->error(L('DeleteFailure') , $base_url.'recycleList',false);
		}		
	}    
}