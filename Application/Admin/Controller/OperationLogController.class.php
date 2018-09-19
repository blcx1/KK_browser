<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Page;
use Admin\Model\OperationLogModel;

class OperationLogController extends Controller {
	
	protected $something_object = null;
	protected $breadNav_array = array();
	protected $title = '';

	/**
	 * 判断是否非法访问
	 */
	Public function _initialize(){
		if (!session ( 'userid' )) {
			$this->display ( "Index/index" );
			exit;
		}
		//权限验证
		$name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		
		$where['name'] = array("eq","$name");
		$auth_ruleModel = M("Auth_rule");
		if($auth_ruleModel->where($where)->find()){
			if(!authcheck($name,$_SESSION['userid'])){
				$this->error(L("NoAuth"));//没有权限！
			}
		}		
		$position_name = L('OperationLogManage');
		$this->title = $position_name;			
		$this->breadNav_array[] = L('Position');
		$this->breadNav_array[] = $position_name;
		$this->something_object = new OperationLogModel();
		
	}
	
	/**
	 * 析构方法
	 * @access public
	 */
	public function __destruct() {
		
		parent::__destruct();
		$this->something_object = null;		
		$this->breadNav_array = array();
		$this->title = '';
	}
	
	/**
	 * 详情
	 */
	public function getDetail(){
	
		$id = filter_set_value($_REQUEST,'id',0,'int');
		$detail_info = $this->something_object->get_info($id);
		if(check_array_valid($detail_info)){
			
			$right_array = array();
			$right_array[0]['href'] = getBaseURL().'Admin/OperationLog/showList';
			$right_array[0]['title'] = L('showList');
			$this->head_common('Detail',$right_array);
			$this->assign('detail_info',$detail_info);
			$this->display('getDetail');
		}else{
	
			$this->error(L("IllegalData") , "./showList",false);
		}
	
	}
	
	/**
	 * 信息查询
	 */
	public function showList() {
		
		$right_array = array();		
		$this->head_common('ShowList',$right_array);
		$order_by = 'id';
		$order_way = 'desc';
		$group_by = '';
		$whileList = array(); 
		$wheredata = array();		
		$Model_Object = $this->something_object;

		$user_name = trim(I("user_name"));
		$operate_name = trim(I("operate_name"));
				
		$pageNo = I("p"); // 当前页
		if (empty ( $pageNo )) {
			
				$pageNo = 1;
		}
		$pageSize = 30; // 每页显示条数
		// 条件拼接
		$whileList["is_delete"] = 0;	
		
		if (! empty ( $user_name )) {
			
			$user_name = urldecode($user_name);
			$whileList["admin_name"] = array("like","%$user_name%");
			$wheredata["user_name"] = $user_name  ;
		}
		if (! empty ( $operate_name )) {
			
			$template_code = urldecode($operate_name);
			$whileList["operate_name"] = array("like","%$operate_name%");
			$wheredata["operate_name"] = $operate_name  ;
		}
		
		$pageCount = $Model_Object->get_list_count($group_by,$whileList); // 获取总记录条数		
		
		$data = $Model_Object->get_page_list($pageNo,$pageSize,$order_by,$order_way,$group_by,$whileList);// 获取列表
				
		$page = new Page ( $pageCount, $pageSize ); // 实例化分页类
		
		//分页跳转的时候保证查询的条件
	 	$page->parameter =   array_map('urlencode',$wheredata);
	 	
	 	/**
		 * 设置分页的样式
		 */
		$page->setConfig ( "first", L("FirstPage"));//第一页
		$page->setConfig ( "prev", L("PreviousPage"));//上一页
		$page->setConfig ( "next", L("NextPage"));//下一页
		$page->setConfig ( "last", L("LastPage"));//最后一页
		$show = $page->show ();
		
		$this->assign ( 'showList', $data ); // 输出记录
		$this->assign ( "show", $show ); // 输出分页
		$this->assign('amount',$pageCount);
		$this->assign ( "user_name", $user_name );
		$this->assign ( "operate_name", $operate_name );			
		$this->display ( "showList" );
	}
	/**
	 *查询已删除的信息 
	 **/
	 public function recycleList() 
	 {
	 	$this->head_common('RecycleList');
	 	$order_by = 'id';
		$order_way = 'desc';
		$group_by = '';
		$whileList = array(); 
		$wheredata = array();		
		$Model_Object = $this->something_object;

		$user_name = trim(I("user_name"));
		$operate_name = trim(I("operate_name"));
		
		$pageNo = I("p"); // 当前页
		if (empty ( $pageNo )) {
				
			$pageNo = 1;
		}
		$pageSize = 30; // 每页显示条数
		// 条件拼接
		$whileList["is_delete"] = 1;
		
		if (! empty ( $user_name )) {
				
			$user_name = urldecode($user_name);
			$whileList["admin_name"] = array("like","%$user_name%");
			$wheredata["user_name"] = $user_name  ;
		}
		if (! empty ( $operate_name )) {
				
			$template_code = urldecode($operate_name);
			$whileList["operate_name"] = array("like","%$operate_name%");
			$wheredata["operate_name"] = $operate_name  ;
		}		
		
		$pageCount = $Model_Object->get_list_count($group_by,$whileList); // 获取总记录条数		
		
		$data = $Model_Object->get_page_list($pageNo,$pageSize,$order_by,$order_way,$group_by,$whileList);// 获取列表
				
		$page = new Page ( $pageCount, $pageSize ); // 实例化分页类
		
		//分页跳转的时候保证查询的条件
	 	$page->parameter =   array_map('urlencode',$wheredata);

	 	/**
		 * 设置分页的样式
		 */
		$page->setConfig ( "first", L("FirstPage"));//第一页
		$page->setConfig ( "prev", L("PreviousPage"));//上一页
		$page->setConfig ( "next", L("NextPage"));//下一页
		$page->setConfig ( "last", L("LastPage"));//最后一页
		$show = $page->show ();
				
		$this->assign ( 'recycleList', $data ); // 输出记录
		$this->assign ( "show", $show ); // 输出分页		
		$this->assign('amount',$pageCount);
		$this->assign ( "user_name", $user_name );
		$this->assign ( "operate_name", $operate_name );			
		$this->display ( "recycleList" );
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
			
			$check = $Model_Object->info_delete($where);
		}
		
		if($check){
			
			$this->success (L("DeleteSuccess") , "./showList", false);//删除成功
		}else{
			
			$this->error(L("DeleteFailure") , "./showList",false);
		}
		
	}
	/**
	 * 恢复删除的信息
	 */
	public function recoverInfo()
	{		
		$check = false;
		$where = array();
		$Model_Object = $this->something_object;
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'id');
		$id = filter_set_value($_REQUEST,'id',0,'int');
		$id_array = filter_set_value($_REQUEST,'check',array(),'array');
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
				
			$this->success (L("RestoreSuccess") , "./recycleList", false);//恢复成功
		}else{
				
			$this->error(L("RestoreFailed") , "./recycleList",false);
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
				
			$this->success (L("DeleteSuccess") , "./recycleList", false);//删除成功
		}else{
				
			$this->error(L("DeleteFailure") , "./recycleList",false);
		}		
	}
	
	/**
	 * 定义操作方法不存在的方法调用 404 页面
	 */
	public function _empty(){
		
		header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
		header('Location:'.getBaseURL().'404.html');
		exit;
	}
	
}

