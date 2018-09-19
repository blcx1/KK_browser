<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Page;

class AdminController extends Controller {
	
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
		$position_name = L('Administrator');
		$this->title = $position_name;
		$this->breadNav_array[] = L('Position');
		$this->breadNav_array[] = $position_name;		
	}
	
	/**
	 * 析构方法
	 * @access public
	 */
	public function __destruct() {
	
		//parent::__destruct();
		$this->breadNav_array = array();
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
	
		$this->assign ( "title", $this->title);
		$this->assign ( "breadNav_array", $this->breadNav_array);
		$this->assign ( "last_breadNav", count($this->breadNav_array) - 1);
		$this->assign ('right_array',$right_array);
	}
	
	/**
	 * *********************** 管理员信息管理 start *****************************
	 */
	/**
	 * 修改管理员的密码（tb_master）
	 */
	public function updateAdminPwd() {
		
		$right_array = array();
		$right_array[0]['href'] = getBaseURL().'Admin/Admin/showAllAdmin';
		$right_array[0]['title'] = L('AdministratorList');
		$this->head_common('Changepassword',$right_array);
		
		if (IS_POST) {
			
			$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
			add_operate($operate_name,'id');
			
			// 获取要修改的密码
			$password = I("password");
			$repassword = I("repassword");
			if(empty($password) || empty($repassword)){
				$this->error ( L("PwdNoNull"));//密码不能为空
			}else	if ($password != $repassword) {
				$this->error ( L("TwoPwdOK"));//密码不一致
			} else {
				$MasterModel = M ( "Master" );
				$Master = $MasterModel->where ( "id = " . session ( "userid" ) )->find ();
				if (count ( $Master ) > 0) {
					$Master ["password"] = md5 ( $password );
					if ($MasterModel->data ( $Master )->save () > 0) {
						//修改成功
						$this-> success(L("ModifySuccess"),"./showAdmin");
					}else{
						//密码修改失败
						$this->error(L("ModifyFailure"),"./updateAdminPwd",3);
					}
				}else{
					$this->error(L("NoSearchAdmin"),"./updateAdminPwd",3);//查找不到该管理员信息！
				}
			}
		} else {
			$this->display ("updateAdminPwd");
		}
	}
	/**
	 * 修改其他管理员的密码
	 */
	public  function  updatePwdByID(){
		
		$right_array = array();
		$right_array[0]['href'] = getBaseURL().'Admin/Admin/showAllAdmin';
		$right_array[0]['title'] = L('AdministratorList');
		$this->head_common('Changepassword',$right_array);
		
		if($_POST){
			
			$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
			add_operate($operate_name,'id');
			
			$id = I ("id");
			// 获取要修改的密码
			$password = I("password");
			$repassword = I("repassword");
			if(empty($password) || empty($repassword)){
				$this->error ( L("PwdNoNull"));//密码不能为空
			}else	if ($password != $repassword) {
				$this->error ( L("TwoPwdOK"));//密码不一致
			} else {
				$MasterModel = M ( "Master" );
				$whereData["id"] = $id;
				$Master = $MasterModel->where ($whereData)->find ();
				if (count ( $Master ) > 0) {
					$Master ["password"] = md5 ( $password );
					if ($MasterModel->data ( $Master )->save () > 0) {
						//修改成功
						$this-> success(L("ModifySuccess"),"./showAllAdmin");
					}else{
						//密码修改失败
						$this->error(L("ModifyFailure"),"./updatePwdByID",3);
					}
				}else{
					$this->error(L("NoSearchAdmin"),"./updatePwdByID",3);//查找不到该管理员信息！
				}
			}
		}else{
			$id = I("id");
			$this->assign("id",$id);
			$this->display("updatePwdById");
		}
	}
	/**
	 * 查询登录的管理员信息
	 */
	public function showAdmin() {
		
		$right_array = array();
		$right_array[0]['href'] = getBaseURL().'Admin/Admin/showAllAdmin';
		$right_array[0]['title'] = L('AdministratorList');
		$this->head_common('updateAdminInfo',$right_array);
		
		$MasterMode = M ( "Master" );
		$id = I("id");
		if($id == "" || $id == null){
			$master = $MasterMode->where ( "id =" . session ( "userid" ) )->find ();
		}else{
			$master = $MasterMode -> where("id = $id")-> find();
		}
		$this->assign ( 'master', $master );
		$this->display ( "admininfo" );
	}
	/**
	 * 查询所有的管理员信息
	 */
	public  function  showAllAdmin(){
		
		$right_array = array();
		$right_array[0]['href'] = getBaseURL().'Admin/Admin/addAdmin';
		$right_array[0]['title'] = L('AddAdministrator');
		$this->head_common('AdministratorList',$right_array);
		
		$adminModel=M("Master");
		$pageNo =I("p"); // 当前页
		if (empty ( $pageNo )) {
			$pageNo = 0;
		}
		$pageSize = 15; // 每页显示条数
		$pageCount = $adminModel -> Count();//获取总记录条数
		
		$field ="tb_master.* ,tb_auth_group.title";
		$join1 ="LEFT  JOIN tb_auth_group_access on tb_master.id = tb_auth_group_access.uid";
		$join2= "LEFT JOIN tb_auth_group ON tb_auth_group_access.group_id = tb_auth_group.id";
		
		$data = $adminModel -> join($join1) ->join($join2) ->field($field) ->page($pageNo,$pageSize)-> select();
		$page = new Page ( $pageCount, $pageSize ); // 实例化分页类
		
		/**
		 * 设置分页的样式
		*/
		$page->setConfig ( "first",L("FirstPage"));//第一页
		$page->setConfig ( "prev", L("PreviousPage"));//上一页
		$page->setConfig ( "next", L("NextPage"));// 下一页
		$page->setConfig ( "last", L("LastPage"));//最后一页
		$show = $page->show ();
		$this->assign ( 'adminList', $data ); // 输出记录
		$this->assign ( "show", $show ); // 输出分页
		$this->assign('amount',$pageCount);
		$this->display ( "showAdmin" );
	}
	/**
	 * 修改管理员自己的信息
	 */
	public function updateAdmin() {
		
		$right_array = array();
		$right_array[0]['href'] = getBaseURL().'Admin/Admin/showAllAdmin';
		$right_array[0]['title'] = L('AdministratorList');
		$this->head_common('updateAdminInfo',$right_array);
		
		if (IS_POST) {
			
			$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
			add_operate($operate_name,'id');
			
			// 实例化对象
			$MasterMode = M ( "Master" );
			$master = $MasterMode->where ( "id =" . I("id") )->find ();
			$master ["name"] = I("name");
			$master ["borthday"] = I("borthday");
			$master ["address"] =I("address");
			$master ["mobile"] = trim ( I("mobile") );
			$master ["jobnum"] = I("jobnum");
			if ($MasterMode->data ( $master )->save ()) {
				//修改成功
				$this-> success(L("ModifySuccess"),"./showAllAdmin",1);
			}else{
				//修改失败
				$this-> error(L("ModifyFailure"),"./showAdmin",1);
			}
		} else {
			echo L("ModifyRepeat");//修改失败，请重新输入
			$this->display ( "admininfo" );
		}
	}
	
	/**
	 * 添加管理员信息
	 */
	public function addAdmin() {
		
		$right_array = array();
		$right_array[0]['href'] = getBaseURL().'Admin/Admin/showAllAdmin';
		$right_array[0]['title'] = L('AdministratorList');
		$this->head_common('AddAdministrator',$right_array);
		
		if (IS_POST)
		{
			$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
			add_operate($operate_name,'id');
			//dump($_POST);
			 // 保存表单数据，包括图片
			$adminModel = M("Master");
			$admin ["name"] = I("post.name");
			$admin ["password"] = md5(I("post.password"));
			$admin ["jobnum"] = I("post.jobnum");
			$admin ["borthday"]	= I("post.birthday");
			$admin ["address"] = I("post.address");
			$admin ["mobile"] = I("post.mobile");
			$admin ["lastlogintime"] = date( "Y-m-d H:i:s", time ());
			$admin ["createtime"] = date ( "Y-m-d H:i:s", time () );
			$count = $adminModel -> where ("name= '" .$admin["name"] . "'")-> count();
			if ($count > 0) {
				//改用户已存在，请重新添加新的管理员
				$this->error ( L("AddRepeat"), "addAdmin", 3 );
			}else{
				$result = $adminModel ->data($admin)-> add(); 
				if($result){
					//添加成功
					$this->success ( L("AddSuccess"), "showAllAdmin", 3 );
				}else{
					//添加失败，重新添加
					$this->error(L("AddFailure"),"addAdmin",3);
				}
			}
		}else{
			//如果不是post请求，转发添加管理员页面
			$this->display("addAdmin");
		}
	}
	/**
	 * 删除管理员信息
	 */
	public  function deleteAdmin(){
		
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'id');
		
		$id = I("id");
		$auth_group_access = M("auth_group_access");
		$whereAcc["uid"] = $id;
		$result2 = $auth_group_access ->where ($whereAcc)->getField();
		
		$adminModel = M("Master");
		$where["id"]=$id;
		
		if($result2 > 0){
			$resul = $auth_group_access -> where($whereAcc) -> delete();
				if($resul){
					$result = $adminModel -> where($where)-> delete();
					//删除成功
					$this->success(L("DeleteSuccess"),"showAllAdmin",3);
				}else{
					//删除失败
					$this->error(L("DeleteFailure"),"showAllAdmin",3);
				}
		}else{
			//没有映射只删除管理员信息表
			$result = $adminModel -> where($where)-> delete();
			//删除成功
			$this->success(L("DeleteSuccess"),"showAllAdmin",3);
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