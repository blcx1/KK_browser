<?php
namespace Admin\Controller;
use Think\Controller;

class AuthController extends Controller {
	
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
				$this->error(L("NoAuth"));//没有权限
			}
		}
				
		$position_name = L('Administrator');
		$this->title = $position_name;
		$this->breadNav_array[] = L('Position');
		$this->breadNav_array[] = $position_name;
		$this->breadNav_array[] = L('AdminGroupList');
	}
	
	/**
	 * 析构方法
	 * @access public
	 */
	public function __destruct() {
	
		parent::__destruct();
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
	 * 管理员组信息
	 */
	public  function  showGroup(){
		
		$right_array = array();
		$right_array[0]['href'] = getBaseURL().'Admin/Auth/addGroup';
		$right_array[0]['title'] = L('addAdminGroup');
		$this->head_common('',$right_array);
		
		$groupModel = M("Auth_group");
		$data= $groupModel -> select();
		$this->assign('groupList',$data);
		$this->display("showGroup");
	} 
	/**
	 * 查询所有的权限信息
	 */
	public  function  getAllAuth(){
		
		$right_array = array();
		$right_array[0]['href'] = getBaseURL().'Admin/Auth/showGroup.html';
		$right_array[0]['title'] = L('AdminGroupList');
        $right_array[1]['href'] = getBaseURL().'Admin/Auth/addrule.html';
        $right_array[1]['title'] = L('Addrule');
        $right_array[2]['href'] = getBaseURL().'Admin/Auth/addrulegroup.html';
        $right_array[2]['title'] = L('Addrulegroup');
		$this->head_common('AllotAuthority',$right_array);
		
		$id = I("id");
		session('authgroupid',$id);
		$groupModel = M("Auth_group");
		$whereList["id"] = $id;
		$data = $groupModel ->field("title,rules") -> where($whereList) -> find();
		$groupAuthority = explode(",", $data["rules"]);
		$title = $data["title"];
		
		$authModel = M("Auth_rule");
		$field = "tb_auth_rule.id,tb_auth_rule.title,tb_auth_model.`name`,tb_auth_model.moderid,tb_auth_rule.orderid";
		$join = "INNER JOIN tb_auth_model on tb_auth_rule.topid = tb_auth_model.id";
		$order = "tb_auth_model.moderid,tb_auth_rule.orderid";
		$allAuthority = $authModel->join($join)->field($field)->order($order)->select();
		
		$resultKey = 0;
		$result = array();
		foreach ($allAuthority as $key=>$value){
			
			if(count($result) <= 0 || $result[$resultKey]["moderid"] != $value["moderid"]){
				
				if($key != 0){
					$resultKey++;
				}
				$result[$resultKey]["moderid"] = $value["moderid"];
				$result[$resultKey]["name"] = $value["name"];
				$result[$resultKey]["data"][] = array(
						"id" => $value["id"],
						"title" => $value["title"],
						"orderid" => $value["orderid"],
				);
			}else{
				$result[$resultKey]["data"][] = array(
						"id" => $value["id"],
						"title" => $value["title"],
						"orderid" => $value["orderid"],
				);
			}
		}
		$this->assign('groupAuthority',$groupAuthority);
		$this->assign("title",$title);
		$this->assign('authList',$allAuthority);
		$this->assign("result",$result);
		$this->assign("groupid",$id);
		$this->display("addAuth");
	}

    /**
     * 添加权限规则
     */
    public function addrule(){
        $right_array = array();
        $right_array[0]['href'] = 'getAllAuth.html?id='.session('authgroupid');
        $right_array[0]['title'] = L('Return').L('Group').L('RuleList');
        $this->head_common('AllotAuthority',$right_array);

        $model = M('auth_model');
        $rule_object = M('auth_rule');
        if(IS_POST){
            $rule = M('auth_rule');
            $gid = I('post.gid');
            $name = I('post.name');
            $link =  I('post.link');
            $sort = I('post.sort');

            $data['topid'] = $gid;
            $data['title'] = $name;
            $data['name'] = $link;
            $data['orderid'] = $sort;


            if($rule->add($data)){
                $this->success(L('AddSuccess'));
            }else{
                $this->error(L('AddFailure'));
            }
            exit;
        }
        $modelist = $model->order('moderid asc')->select();
        $rores = $rule_object -> order('id asc')->select();

        $this->assign('rulelist',$rores);
        $this->assign('modelist',$modelist);
        $this->display();
    }

    /**
     * 添加权限规则
     */
    public function addrulegroup(){
        $right_array = array();
        $right_array[0]['href'] = 'getAllAuth.html?id='.session('authgroupid');
        $right_array[0]['title'] = L('Return').L('Group').L('RuleList');
        $this->head_common('AllotAuthority',$right_array);

        $model = M('auth_model');
        if(IS_POST){
            $name = I('post.name');
            $maxmode = $model->max('moderid');
            $maxmode = $maxmode+1;
            $data['name'] = $name;
            $data['moderid'] = $maxmode;


            if($model->add($data)){
                $this->success(L('AddSuccess'));
            }else{
                $this->error(L('AddFailure'));
            }
            exit;
        }
        $glist = $model->select();
        $this->assign('glist',$glist);
        $this->display();
    }

    /**
     * 删除权限组
     */
    public function delrulegroup(){
        $model = M('auth_model');
        if(IS_GET){
            $id = I('get.id');
            if($model->delete($id)){
                $this->success(L('DeleteSuccess'));
            }else{
                $this->error(L('DeleteFailure'));
            }
        }
    }

    /**
     * 删除权限
     */
    public function delrule(){
        $model = M('auth_rule');
        if(IS_GET){
            $id = I('get.id');
            if($model->delete($id)){
                $this->success(L('DeleteSuccess'));
            }else{
                $this->error(L('DeleteFailure'));
            }
            exit;
        }
    }

    /**
     *更新权限组
     */
    public function uprulegroup(){
        $right_array = array();
        $this->head_common('AllotAuthority',$right_array);

        $model = M('auth_model');
        $where['id'] = I('get.id');
        $res = $model -> where($where)->find();

        if(IS_POST){
            $id = I('post.id');
            $name = I('post.name');
            $data['id'] = $id;
            $data['name'] = $name;
            if($model->save($data)){
                $this->success(L('ModifySuccess'),U('admin/auth/addrulegroup'));
            }else{
                $this->error(L('ModifyFailure'));
            }
            exit;
        }
        $this->assign('res',$res);
        $this->display();
    }

	/**
	 * 修改更新管理员组的权限
	 */
	function editGroupAuth(){
		
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'id');
		$authArr = I("authid");		
		foreach ($authArr as $key=>$value){
			$authStr .=$value.",";
		}
		$authStr = substr($authStr, 0,strlen($authStr)-1);
		$groupData["id"] = I("post.id");
		$groupData["rules"] = $authStr;
		$authgroupModel = M("Auth_group");
		if($authgroupModel->save($groupData)){
			//更新权限成功
			$this->success(L("ModifySuccess"),U("Admin/Auth/getAllAuth/id/".I("post.id") ),3);
		}else{
			//更新权限失败
			$this->error(L("ModifyFailure"),U("Admin/Auth/getAllAuth/id/".I("post.id") ),3);
		}
	}
	
	/**
	 *  添加管理员组
	 */
	public  function addGroup(){
		
		$right_array = array();		
		$right_array[0]['href'] = getBaseURL().'Admin/Auth/showGroup.html';
		$right_array[0]['title'] = L('AdminGroupList');
		$this->head_common('addAdminGroup',$right_array);
		
		if($_POST){
			
			$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
			add_operate($operate_name,'id');
			$authgroup["title"] = I("title");
			$authArr = I("authid");
			foreach ($authArr as $key => $value){
				$authStr .=$value.",";
			}
			$authStr = substr($authStr,0,strlen($authStr)-1);
			$authgroup["rules"] = $authStr;
			
			$authgroupModel = M("Auth_group");
			$result = $authgroupModel -> data($authgroup) ->add();
			if($result){
				//管理员组添加成功
				$this->success(L("AddSuccess"),U("Admin/Auth/showGroup"),3);
			}else{
				//管理员组添加失败
				$this->error(L("AddFailure"),U("Admin/Auth/showGroup"),3);
			}
			
		}else{
			
			$groupid = 0;
			$groupAuthority = array();
			$authModel = M("Auth_rule");
			$field = "tb_auth_rule.id,tb_auth_rule.title,tb_auth_model.`name`,tb_auth_model.moderid,tb_auth_rule.orderid";
			$join = "INNER JOIN tb_auth_model on tb_auth_rule.topid = tb_auth_model.id";
			$order = "tb_auth_model.moderid,tb_auth_rule.orderid";
			$allAuthority = $authModel->join($join)->field($field)->order($order)->select();
			
			$resultKey = 0;
			$result = array();
			foreach ($allAuthority as $key=>$value){
				
				if(count($result) <= 0 || $result[$resultKey]["moderid"] != $value["moderid"]){
					if($key != 0){
						$resultKey++;
					}
					$result[$resultKey]["moderid"] = $value["moderid"];
					$result[$resultKey]["name"] = $value["name"];
					$result[$resultKey]["data"][] = array(
							"id" => $value["id"],
							"title" => $value["title"],
							"orderid" => $value["orderid"],
					);
				}else{
					$result[$resultKey]["data"][] = array(
							"id" => $value["id"],
							"title" => $value["title"],
							"orderid" => $value["orderid"],
					);
				}
			}
			$this->assign('groupid',$groupid);
			$this->assign('groupAuthority',$groupAuthority);
			$this->assign('authList',$allAuthority);
			$this->assign("result",$result);
			$this->display("addGroup");
		}
	}
	
	/**
	 * 删除管理员组
	 */
	public  function  deleteGroup(){
		
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'id');
		
		$id = I("id");
		$authgroupModel = M("Auth_group");
		$whereList["id"] = $id;
		$result = $authgroupModel ->where($whereList) -> delete();
		
		$authgroupaccessModel = M("Auth_group_access");
		$whereList2["group_id"] = $id;
		$re=$authgroupaccessModel -> where($whereList2) -> select();
		//判断该管理员组中是否存在成员		
		if($re>0){
			$result2=$authgroupaccessModel -> where($whereList2) -> delete();
		}else{
			$result2 = true;
		}
		if($result && $result2){
			//管理员组删除成功
			$this->success(L("DeleteSuccess"),U("Admin/Auth/showGroup"),3);
		}else{
			//管理员组删除失败
			$this->error(L("DeleteFailure"),U("Admin/Auth/showGroup"),3);
		}
	}
	
	/**
	 * 查询管理员组中的所有成员信息
	 */
	public  function  manageGroupMaster(){
		
		$right_array = array();
		$id = I("id");
		$base_url = getBaseURL();
		$right_array[0]['href'] = $base_url.'Admin/Auth/showGroup.html';
		$right_array[0]['title'] = L('AdminGroupList');
		$right_array[1]['href'] = $base_url.'Admin/Auth/getAllMaster.html?id='.$id;
		$right_array[1]['title'] = L('AddMembers');
		$this->head_common('Members',$right_array);		
		
		$authgroupaccessModel = M("Auth_group_access");
		$whereList["group_id"] = $id;
		$data = $authgroupaccessModel -> where($whereList) -> select ();
		
		$field ="tb_master.id,tb_master.name,tb_master.jobnum,tb_auth_group.title,tb_auth_group_access.group_id";
		$join1 ="INNER JOIN tb_master ON  tb_auth_group_access.uid=tb_master.id";
		$join2="INNER JOIN tb_auth_group on tb_auth_group_access.group_id = tb_auth_group.id";
		$where = "tb_auth_group_access.group_id = ".$id;
				
		$dataMaster = $authgroupaccessModel ->join($join1) -> join($join2) ->field($field)-> where($where)-> select();
		 
		//下拉列表
		$authgroupModel =M("Auth_group");
		$whereMasterGroup["id"] = array("neq",$id);
		$dataAuthgroup=$authgroupModel ->where($whereMasterGroup) -> select();
		$this->assign("dataAuthgroupList",$dataAuthgroup);
		
		$this->assign("id",$id);
		$this->assign("dataMasterList",$dataMaster);
		$this->display("manageGroup");
	}
	
	/**
	 * 删除管理员组中的成员
	 */
	public  function  deleteGroupAccess(){
		
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'id');
		
		$id = I("id");
		$group_id =I("group_id");
		$authgroupaccessModel = M("Auth_group_access");
		$whereList["uid"] = $id;
		$result = $authgroupaccessModel -> where($whereList) -> delete();
		if($result){
			//组成员删除成功
			$this->success(L("DeleteSuccess"),U("Admin/Auth/manageGroupMaster/id/".I("group_id")),3);
		}else{
			//组成员删除失败
			$this->error(L("DeleteFailure"),U("Admin/Auth/manageGroupMaster/id/".I("group_id")),3);
		}
	}
	/**
	 * 查询所有的管理员信息
	 */	
	public  function  getAllMaster(){
		
		$right_array = array();
		$this->breadNav_array[] = L('Members');
		$right_array[0]['href'] = getBaseURL().'Admin/Auth/showGroup.html';
		$right_array[0]['title'] = L('AdminGroupList');
		$this->head_common('AddMembers',$right_array);
		
		$id = I("id");
		$authgroupModel = M("Auth_group");
		$whereAuth = "id = ".$id;
		$authList = $authgroupModel ->where($whereAuth) -> select();
		
		$masterModel = M("Master");
		$authgroupaccessModel = M("Auth_group_access");
		$dataAuth = $authgroupaccessModel-> select();
		$idArr = array();
		foreach ($dataAuth as $key => $value){
			$idArr[]  =  $value["uid"];
		}
		$wherelist["id"] = array('not in',$idArr);
		$dataList = $masterModel ->where($wherelist)-> select();
		$this->assign("masterList",$dataList);
		$this->assign("id",$id);
		$this->assign("authList",$authList);
		$this->display("addGroupAccess");
	}	
	
	/**
	 * 管理员组中添加成员
	 */
	public  function  addGroupAccess(){
		
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'uid');
		
		$data["uid"] = I("uid");
		$data["group_id"] = I("group_id");
		$authgroupaccessModel = M("Auth_group_access");
		$uid = $data["uid"]; 
		$group_id = $data["group_id"];
		
		$whereList['uid'] = array('eq',$uid);
		$result = $authgroupaccessModel -> where($whereList) -> find();
		
		if(!$result){
			$result = $authgroupaccessModel -> data($data) -> add();
			if($result){
				//添加成员成功！
				$this->success(L("AddSuccess"),U("Admin/Auth/manageGroupMaster/id/".I("group_id")),3);
			}else{
				//添加成员失败,请重新添加
				$this->error(L("AddFailure"),U("Admin/Auth/manageGroupMaster/id/".I("group_id")),3);
			}
		}else {
			//添加成员失败，该用户已经拥有角色！
			$this->error(L("AddRepeat"),U("Admin/Auth/getAllMaster/id/".I("group_id")),3);
		}
	}
	
	/**
	 * 修改管理员组中的成员
	 */
	public  function  editGroupAccess(){
		
		$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		add_operate($operate_name,'uid');
		
		$data["uid"] = I("uid");
		$data["group_id"] = I("group_id");
		
		$authgroupaccessModel = M("Auth_group_access");
		if($authgroupaccessModel ->where($data)-> select()){
			//成员修改所属组失败，已经在该组
			$this->error(L("ModifyRepeat"),U("Admin/Auth/manageGroupMaster/id/".I("group_id")),3);
		}else{
			$wherelist["uid"] = $data["uid"];
			$result = $authgroupaccessModel -> where($wherelist) -> save($data);
			if($result){
				//修改成功
				$this->success(L("ModifySuccess"),U("Admin/Auth/manageGroupMaster/id/".I("group_id")),3);
			}else{
				//修改失败
				$this->error(L("ModifyFailure"),U("Admin/Auth/manageGroupMaster/id/".I("group_id")),3);
			}
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