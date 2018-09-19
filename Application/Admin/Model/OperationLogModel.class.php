<?php

namespace Admin\Model;
use Think\Model;

class OperationLogModel extends Model {
	
	protected $current_time;
	protected $current_datetime;
	protected $current_date;
	protected $default_order_by = 'id';
	protected $error_array = array();//错误信息
	protected $error_code_array = array();//错误编码
	
	/**
	 * 初始化
	 */
	public function __construct($name='',$tablePrefix='',$connection='') {
		
		$this->tableName = C('DB_PREFIX').'operation_log';
		$this->trueTableName = $this->tableName;
		$this->pk = 'id';
		parent::__construct($name,$tablePrefix,$connection);
		$this->current_time = time();
		$this->current_datetime = date('Y-m-d H:i:s',$this->current_time);
		$this->current_date = date('Y-m-d',$this->current_time);
		
	}
	
	/**
	 * 获取信息
	 */
	public function get_info($id){
	
		$info_array = array();
		$id = intval($id);
		if($id > 0){
			$id_name = $this->pk;
				
			$result = $this->where ($id_name.' = '.$id)->find();
			if(check_array_valid($result)){
					
				$info_array = $result;
			}
		}
	
		return $info_array;
	}
	
	/**
	 * 释放
	 **/
	public function __destruct(){
	    		
		$this->current_time = 0;
		$this->current_datetime = '';
		$this->current_date = '';
		$this->default_order_by = '';
		$this->error_code_array = array();
		$this->error_array = array();		
	}
	
	/**
	 * 获取列表
	 * @param number $start
	 * @param number $len
	 * @param string $order_by
	 * @param string $order_way
	 * @param string $group_by
	 * @param unknown $where
	 * @return Ambigous <multitype:, unknown>
	 */
	public function get_list($start = 0,$len = 30,$order_by,$order_way = 'desc',$group_by = '',$where){
	
		$list_array = array();
		$start = intval($start);
		$len = intval($len);
		$start = $start > 0 ? $start : 0;
		$len = $len > 0 ? $len : 30;
		$order_by = trim($order_by);
		$order_by = strlen($order_by) > 0 ? $order_by : $this->default_order_by;
		$order_way = strtolower(trim($order_way));
		$order_way = strlen($order_way) > 0 ? $order_way : 'desc';
		$group_by = trim($group_by);
	
		$something_object = $this;
		if(check_array_valid($where) || (is_string($where) && strlen(trim($where)) > 0)){
				
			$something_object->where($where);
		}
		if(strlen($group_by) > 0){
	
			$something_object->group($group_by);
		}
		 
		$result = $something_object->order($order_by.' '.$order_way)->limit($start,$len)->select();
		if(check_array_valid($result)){
	
			$list_array = $result;
		}
		return $list_array;
	}
	
	/**
	 * 获取列表总个数
	 * @param string $group_by
	 * @param unknown $where
	 * @return number
	 */
	public function get_list_count($group_by = '',$where){
	
		$list_count = 0;
		$group_by = trim($group_by);
		$something_object = $this;
	
		if(check_array_valid($where) || (is_string($where) && strlen(trim($where)) > 0)){
				
			$something_object->where($where);
		}
	
		if(strlen($group_by) > 0){
	
			$something_object->group($group_by);
		}
	
		$list_count = $something_object->count();
		$list_count = intval($list_count);
	
		return $list_count;
	}
	
	/**
	 * 按页码获取列表
	 * @param number $page_no
	 * @param number $page_size
	 * @param string $order_by
	 * @param string $order_way
	 * @param string $group_by
	 * @param string $str_where
	 */
	public function get_page_list($page_no = 1,$page_size = 30,$order_by,$order_way = 'desc',$group_by = '',$where){
	
		$page_no = intval($page_no);
		$page_size = intval($page_size);
		$page_no = $page_no > 0 ? $page_no : 1;
		$page_size = $page_size > 0 ? $page_size : 30;
		$start = ($page_no - 1)*$page_size;
	
		return $this->get_list($start,$page_size ,$order_by,$order_way,$group_by,$where);
	}
	
	/**
	 * 修改一列值
	 * @param unknown $name
	 * @param unknown $value
	 * @param unknown $where
	 * @return boolean
	 */
	public function change_name($name,$value,$where){
		
		$check = false;
		$name = trim($name);
		if(strlen($name) > 0 && in_array($name,$this->fields) && (check_array_valid($where) || (is_string($where) && strlen(trim($where)) > 0))){
			
			$update_array = array();
			$update_array[$name] = $value;
			$result = $this->where($where)->data($update_array)->save();
			if($result !== false){
				
				$check = true;
			}				
			
		}
		return $check;
	}
	
	/**
	 * 获取某个id下某一列值
	 * @param unknown $id
	 * @param unknown $name
	 * @return string
	 */
	public function getFieldValue($id,$name){
		
		$field_value = '';
		$id = intval($id);
		$name = trim($name);
		$this->_checkTableInfo();
		$filed_array = check_array_valid($this->fields) ? $this->fields : array();
		if($id > 0 && strlen($name) > 0 && in_array($name,$filed_array)){
			
			 $result = $this->field($name)->where($this->pk.' = '.$id)->find();
			 if(check_array_valid($result)){
			 	
			 	$field_value = $result[$name];
			 }
		}		
		return $field_value;
	}
	
	/**
	 * 信息添加
	 * @param unknown $info_array
	 * @return Ambigous <\Think\mixed, boolean, string, unknown>|boolean
	 */
	public function info_add($info_array = array()){
		
		$info_array = check_array_valid($info_array) ? $info_array : array();
		if(count($info_array) > 0){
			
			return $this->data($info_array)->add();
		}else{
			
			return false;
		}		
	}
	
	/**
	 * 信息更新
	 * @param unknown $where
	 * @param unknown $update_array
	 * @return boolean
	 */
	public function info_update($where,$update_array = array()){
		
		$check = false;
		if(count($update_array) > 0 && (check_array_valid($where) || (is_string($where) && strlen(trim($where)) > 0))){
			
			$result = $this->where($where)->data($update_array)->save();			
			$check = $result === false ? false : true;			
		}		
		return $check;
	}
	
	/**
	 * 信息删除
	 * @param unknown $where
	 * @return boolean
	 */
	public function info_delete($where){
		
		$check = false;
		if(check_array_valid($where) || (is_string($where) && strlen(trim($where)) > 0)){
			
			$check = $this->change_name('is_delete',1,$where);
		}
		return $check;		
	}
	
	/**
	 * 信息恢复
	 * @param unknown $where
	 * @return boolean
	 */
	public function info_recover($where){
		
		$check = false;
		if(check_array_valid($where) || (is_string($where) && strlen(trim($where)) > 0)){
				
			$check = $this->change_name('is_delete',0,$where);
		}
		return $check;
	}

	/**
	 * 判别是否存在
	 * @param int $user_id
	 * @return boolean
	 */
	public function check_exists($id){
	
		$check = false;
		$id = intval($id);
		if($id > 0){
				
			$total_count = $this->where($this->pk.' = '.$id.' and is_delete = 0')->count();
			if(intval($total_count) > 0){
	
				$check = true;
			}
		}
	
		return $check;
	}
	
	/**
	 * 添加处理
	 * @return Ambigous <boolean, \Admin\Model\Ambigous, \Think\mixed, string, unknown>
	 */
	public function info_add_process($operate_name,$operate_id_name = 'id'){
		
		$id = 0;
		$check = false;					
		$admin_id = intval(session('userid'));
		$admin_name = trim(session('username'));
		$admin_id = $admin_id > 0 ? $admin_id : 0;
		$operate_id_name = trim($operate_id_name);
		$operate_id_name = strlen($operate_id_name) > 0 ? $operate_id_name : 'id';
		$operate_id = filter_set_value($_REQUEST,$operate_id_name,0,'int');
		$operate_id = $operate_id > 0 ? $operate_id : 0;
		$operate_name = trim($operate_name);
		$operate_ip = get_client_ip(0,true);
		$operate_useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$operate_browse = getBrowse();
		$operate_os = getOS();
		$request = isset($_REQUEST) ? $_REQUEST : array();
		if(isset($request['password'])){
			
			unset($request['password']);
		}
		if(isset($request['repassword'])){
			
			unset($request['repassword']);
		}
		if(isset($request['confirm_password'])){
				
			unset($request['confirm_password']);
		}	
		$request = is_array($request) ? $request : array();
		
		if(check_array_valid($_FILES)){
			
			$request['upload_file_param'] = $_FILES;
		}
		$operate_content = urlencode(base64_encode(json_encode($request)));
					
		$add_array = array();
		$current_datetime = $this->current_datetime;			
		$add_array['admin_id'] = $admin_id;
		$add_array['admin_name'] = addslashes($admin_name);
		$add_array['operate_id'] = $operate_id;
		$add_array['operate_name'] = addslashes($operate_name);
		$add_array['operate_ip'] = addslashes($operate_ip);
		$add_array['operate_useragent'] = addslashes($operate_useragent);
		$add_array['operate_browse'] = addslashes($operate_browse);
		$add_array['operate_os'] = addslashes($operate_os);
		$add_array['operate_content'] = addslashes($operate_content);
		$add_array['is_delete'] = 0;		
		$add_array['add_time'] = $current_datetime;
		$add_array['update_time'] = $current_datetime;		
		$check = $this->info_add($add_array);
		if(!$check){
				
			$this->error_array['result'] = L('AddFailure');
		}				
		
		return $check;
	}
	
	/**
	 * 强制删除
	 * @param unknown $where
	 * @return boolean
	 */
	public function info_force_delete($where){
		
		$check = false;
		$admin_id = intval(session('userid'));
		if($admin_id > 0 && check_array_valid($where) || (is_string($where) && strlen(trim($where)) > 0)){
				
			if(is_array($where)){
				
				$where['admin_id'] = array('neq',$admin_id);				
			}else{
				
				$where .= ' and admin_id != '.$admin_id;
			}
			$result = $this->where($where)->delete();
			$check = (bool)$result;			
		}
		return $check;
	}
}
?>