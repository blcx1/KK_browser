<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Upload;
use Think\Page;

class LanguageController extends \Admin\Controller\DefaultController{
	
	protected $lang_check = false;
	
	/**
	 * 初始化配置
	 */
	public function init(){	
		
		$this->something_object = new \Admin\Model\LanguageModel($this->cache_object);		
		if(!(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == 1)){
				
			$position_name = L('LanguageManage');
			$this->title = $position_name;
			$this->breadNav_array[] = L('Position');
			$this->breadNav_array[] = $position_name;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Admin\Controller\DefaultController::showList()
	 */
	public function showList(){
		
		$right_array = $showList = array();
		$base_url = getBaseURL().MODULE_NAME.'/'.CONTROLLER_NAME.'/';
		$right_array[0]['href'] = $base_url.'addInfo';
		$right_array[0]['title'] = L('AddInfo');
		$right_array[1]['href'] = $base_url.'recycleList';
		$right_array[1]['title'] = L('recycleList');
		$this->head_common('ShowList',$right_array);
		
		$page_size = 30; // 每页显示条数
		$order_by = 'id';
		$order_way = 'desc';
		$group_by = '';
		$whileList = array();
		$wheredata = array();
		$something_object = $this->something_object;		
		$page_no = filter_set_value($_REQUEST,'p',1,'int'); // 当前页
		$page_no = $page_no > 0 ? $page_no : 1;		
		$name = trim(filter_set_value($_REQUEST,'name','','string'));
		
		// 条件拼接
		$whileList['is_delete'] = 0;
		
		if (!empty($name)) {
			
			if(IS_GET){
				
				$name = urldecode($name);
			}			
			$whileList['name'] = array("like","%$name%");
			$wheredata['name'] = $name;
		}
		
		$total_count = $something_object->get_show_list_count($whileList,$group_by);// 获取总记录条数				
		$showList = $something_object->get_show_list($whileList,$page_no,$page_size,$order_by,$order_way,$group_by);// 获取列表
		
		$page = new Page($total_count,$page_size);// 实例化分页类
		
		//分页跳转的时候保证查询的条件
		$page->parameter =   array_map('urlencode',$wheredata);
			
		/**
		 * 设置分页的样式
		*/
		$page->setConfig ('first', L('FirstPage'));//第一页
		$page->setConfig ('prev', L('PreviousPage'));//上一页
		$page->setConfig ('next', L('NextPage'));//下一页
		$page->setConfig ('last', L('LastPage'));//最后一页
		$show = $page->show();
		
		$this->assign('showList', $showList); // 输出记录
		$this->assign('show',$show); // 输出分页
		$this->assign('amount',$total_count);
		$this->assign('name',$name);		
		$this->display('showList');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Admin\Controller\DefaultController::recycleList()
	 */
	public function recycleList(){
		
		$right_array = $showList = array();
		$base_url = getBaseURL().MODULE_NAME.'/'.CONTROLLER_NAME.'/';
		$right_array[0]['href'] = $base_url.'addInfo';
		$right_array[0]['title'] = L('AddInfo');
		$right_array[1]['href'] = $base_url.'showList';
		$right_array[1]['title'] = L('showList');
		$this->head_common('recycleList',$right_array);
		
		$page_size = 30; // 每页显示条数
		$order_by = 'id';
		$order_way = 'desc';
		$group_by = '';
		$whileList = array();
		$wheredata = array();
		$something_object = $this->something_object;
		$title_id = filter_set_value($_REQUEST,'p',1,'int'); //
		$page_no = filter_set_value($_REQUEST,'p',1,'int'); // 当前页
		$page_no = $page_no > 0 ? $page_no : 1;
		$name = trim(filter_set_value($_REQUEST,'name','','string'));
		
		// 条件拼接
		$whileList['is_delete'] = 1;
		
		if (!empty($name)) {
				
			if(IS_GET){
		
				$name = urldecode($name);
			}
			$whileList['name'] = array("like","%$name%");
			$wheredata['name'] = $name;
		}
		
		$total_count = $something_object->get_show_list_count($whileList,$group_by);// 获取总记录条数
		$showList = $something_object->get_show_list($whileList,$page_no,$page_size,$order_by,$order_way,$group_by);// 获取列表
		
		$page = new Page($total_count,$page_size);// 实例化分页类
		
		//分页跳转的时候保证查询的条件
		$page->parameter =   array_map('urlencode',$wheredata);
			
		/**
		 * 设置分页的样式
		*/
		$page->setConfig ('first', L('FirstPage'));//第一页
		$page->setConfig ('prev', L('PreviousPage'));//上一页
		$page->setConfig ('next', L('NextPage'));//下一页
		$page->setConfig ('last', L('LastPage'));//最后一页
		$show = $page->show();
		
		$this->assign('recycleList', $showList); // 输出记录
		$this->assign('show',$show); // 输出分页
		$this->assign('amount',$total_count);
		$this->assign('name',$name);
		$this->display('recycleList');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Admin\Controller\DefaultController::addInfo()
	 */
	public function addInfo(){
		
		$info_array = array();
		$error_array = array();
		$right_array = array();
		$base_url = getBaseURL().MODULE_NAME.'/'.CONTROLLER_NAME.'/';
		$right_array[0]['href'] = $base_url.'showList';
		$right_array[0]['title'] = L('showList');
		$right_array[1]['href'] = $base_url.'recycleList';
		$right_array[1]['title'] = L('recycleList');
		$this->head_common('addInfo',$right_array);
		$token = filter_set_value($_REQUEST,'token','','string');
		$something_object = $this->something_object;
		if(IS_POST && $token === 'add'){
				
			$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
			add_operate($operate_name,'id');
			$check = $something_object->info_add_process();
			if($check){
		
				$this->success (L('AddSuccess'),$base_url.'showList',false);
				exit;
			}else{
		
				$error_array = $something_object->get_error_array();
				$info_array = $_REQUEST;
			}
		}else{
				
			$token = 'add';
		}
		
		$this->assign ('act',ACTION_NAME);
		$this->assign ('token',$token);
		$this->assign ('info_array',$info_array);
		$this->assign ('error_array',$error_array);
		$this->display('addInfo');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Admin\Controller\DefaultController::updateInfo()
	 */
	public function updateInfo(){				
			
		$right_array = array();
		$base_url = getBaseURL().MODULE_NAME.'/'.CONTROLLER_NAME.'/';
		$right_array[0]['href'] = $base_url.'showList';
		$right_array[0]['title'] = L('showList');
		$right_array[1]['href'] = $base_url.'recycleList';
		$right_array[1]['title'] = L('recycleList');
		$this->head_common('updateInfo',$right_array);
		$id = filter_set_value($_REQUEST,'id',0,'int');
		$something_object = $this->something_object;
		$check = $something_object->check_exists($id);		
		if(!$check){
				
			$this->error(L('IllegalData'),$base_url.'showList',false);
		}
		
		$info_array = array();
		$error_array = array();
		$token = filter_set_value($_REQUEST,'token','','string');
		if(IS_POST && $token === 'update'){
				
			$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
			add_operate($operate_name,'id');
				
			$check = $something_object->info_update_process($id,false);
			if ($check) {
		
				//修改成功
				$this->success (L('ModifySuccess'),$base_url.'showList', false);
				exit;
			}else{
				//修改失败
				$error_array = $something_object->get_error_array();
				$info_array = $_REQUEST;
			}
		}else{
				
			$token = 'update';
			$info_array = $something_object->get_info($id);
		}		
		$this->assign ('act',ACTION_NAME);
		$this->assign ('token',$token);
		$this->assign ('info_array',$info_array);
		$this->assign ('error_array',$error_array);
		$this->display('updateInfo');
	}
}

