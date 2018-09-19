<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Upload;
use Think\Page;

class SystemController extends Controller {
	
	/**
	 * 判断是否非法访问
	 */
	public function _initialize(){

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
	}

	
	/**
	 * 导航菜单管理
	 */
	public function  navigationMenu(){
		
		if($_POST){
			
			$operate_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
			add_operate($operate_name,'seltopname');
			
			$datacate["topid"] =I("seltopname");//选择顶级菜单ID
			$datacate["topname"] = I("topname");//输入顶级菜单名
			$datacate["topename"] = I("topename");//输入顶级菜单英文名
			$datacate["alink"] =I("alink");//输入链接
			$datacate["catename"] = I("catename");//输入子菜单名
			$datacate["ename"] = I ("ename");//输入子菜单英文名
			$dataAuthModel["moderid"] = I("authmodelId");//选择权限组ID
			$dataAuthModel["name"] = I("name");//输入权限组名
			
			$cateModel = M("Cate");
			
			//如果选择顶级菜单
			if($datacate["topid"]){
				$catefield = "topname,topename, orderid ";
				$wheretopid["topid"] = $datacate["topid"];
				$cateData = $cateModel ->field($catefield)-> where($wheretopid) -> limit (1)->  order("orderid desc") -> find();
				$datacate["topname"] = $cateData["topname"];
				$datacate["topename"] = $cateData["topename"];
				$datacate["orderid"] = $cateData["orderid"];
			}else{
				$cateDataID = $cateModel -> field("max(topid)+1 as topid") ->find();
				$datacate["topid"] = $cateDataID["topid"];
				$datacate["orderid"] = "1";
			}
			
			$auth_rule = M("auth_rule");
			//如果没有选择权限，添加tb_auth_model数据
			if($dataAuthModel["moderid"] == 0 ){
				$auth_model = M("auth_model");
				$dataModerid=$auth_model -> field("max(moderid)+1 as modelid") -> find();
				$dataAuthModel["moderid"] = $dataModerid["modelid"];
				$Addauthmodel = $auth_model -> data($dataAuthModel) -> add();
				$dataAuthRule["orderid"] ="1";
				$dataAuthRule["topid"] = $dataAuthModel["moderid"];
			}else{
				$dataAuthRule["topid"] = $dataAuthModel["moderid"];
				$wheretopid["topid"] = $dataAuthRule["topid"];
				$dataOrderid= $auth_rule -> where($wheretopid) -> field("MAX(orderid)+1 as orderid")->  find();
				$dataAuthRule["orderid"] = $dataOrderid["orderid"];
			}
			$dataAuthRule["name"] =$datacate["alink"];//tb_auth_rule的链接
			$wherename["name"] = $dataAuthRule["name"];
			$result =  $auth_rule ->where($wherename) -> find();
// 			判断tb_auth_rule的链接name是否存在
			if($result){
				$this->error(L("AlinkExist"),"./navigationMenu",3);//
			}
			else{
				$dataAuthRule["title"] = $datacate["catename"];
				$Addauthrule = $auth_rule -> data($dataAuthRule)-> add();
				$Addcatemodel = $cateModel -> data($datacate) -> add();
					
				if($Addauthrule && $Addcatemodel){
					$this->success (L("AddSuccess"), "./navigationMenu", 3 );//添加成功
				}
				else{
					$this->error ( L("AddFailure"), "./navigationMenu", 3 );//添加失败
				}
			}
		}else{
			$cateModel = M("Cate");
			$cateField = "topid,topname,topename";
			$cateData = $cateModel -> field($cateField) -> group("topid") -> select();
			
			$authModel =  M("auth_model");
			$authModelData = $authModel -> select();
			
			$this->assign("authmodelData",$authModelData);
			$this->assign("cateData",$cateData);
			$this->display("navigationMenu");
		}
	}

    /**
     * 新导航菜单列表
     */
    public function Menu(){
        $menu = M('cate');
        $amodel = M('auth_model');
        $menu_res = $menu->distinct(true)->field('topid,topename,topname')->order('topid asc')->select();

        if($menu_res){
            foreach($menu_res as $key=>$mval){
                $list[$key] = $mval;
                $where['topid'] = $mval['topid'];
                $chil_res = $menu->field('catename,id')->where($where)->order('id')->select();
                $list[$key]['chil'] = $chil_res;
                $list[$key]['ename'] = L($mval['topename']);
                $list[$key]['enname'] = $mval['topename'];
            }
        }

        $mlist = $amodel ->field('id,name')->order('moderid asc')->select();
        $this->assign('mlist',$mlist);
        $this->assign('list',$list);
        $this->display('menu');
    }

    /**
     * 新导航菜单添加
     */
    public function addmainmenu(){
        $addmenu = M('cate');
        $addauthrule = M('auth_rule');
        if(IS_POST){
            $zname = I('post.zname');
            $ename = I('post.ename');
            $alink = I('post.alink');
            $czname = I('post.czname');
            $cename = I('post.cename');
            $groupid = I('post.groupid');
            $menuid = I('post.menuid');
            $sort = I('post.sort');

            $orderid = intval($sort);

            if($menuid==0){
                $newtopid = $addmenu ->max('topid');
                $newtopid = $newtopid+1;
            }else{
                $newtopid = $menuid;
            }

            $data['topname'] = $zname;
            $data['topename'] = $ename;
            $data['alink'] = $alink;
            $data['catename'] = $czname;
            $data['ename'] = $cename;
            $data['orderid'] = $orderid;
            $data['topid'] = $newtopid;

            if($id = $addmenu -> data($data)->add()){

                $datar['type'] = 1;
                $datar['status'] =1;
                $datar['orderid'] = $orderid;
                $datar['topid'] = $groupid;
                $datar['title'] = $data['catename'];
                $datar['name'] =  $data['alink'];

                if($addauthrule->data($datar)->add()){
                    echo 1;
                }else{
                    $where['id'] = $id;
                    $addmenu->where($where)->delete();
                    echo 2;
                }
            }else{
                echo 3;
            }

        }
    }

    /**
     * 删除新菜单的子菜单和子菜单对应权限
     */
    public function delchilmenu(){
        $cate = M('cate');
        $authmodel = M('auth_rule');
        if(IS_POST){
            $cid = I('post.cid');
            $cid = intval($cid);
            $where['id'] = $cid;
            $link = $cate->field('alink')->where($where)->find();
            if(!$link){
                echo 3;
                exit;
            }
            $link = $link['alink'];
            $delstatus = $cate->where($where)->limit(1)->order('id desc')->delete();
            if($delstatus){
                $awhere['name'] = $link;
                $aud = $authmodel -> where($awhere)->order('id desc')->limit(1)->delete();
                if($aud){
                    echo 1;
                }else{
                    echo 4;
                }
            }else{
                echo 2;
            }

        }

    }

    /**
     * 删除主菜单和子菜单及权限
     */
    public function delgroupmenu(){
        $cate = M('cate');
        $authmodel = M('auth_rule');
        if(IS_POST){
            $gid = I('post.gid');
            $gid = intval($gid);
            $where['topid'] = $gid;
            $link = $cate->field('alink')->where($where)->select();
            if(!$link){
                echo 3;
                exit;
            }

            foreach($link as $aval){
                $wheres['name'] = $aval['alink'];
                $aud = $authmodel -> where($wheres)->delete();
                if(!$aud){
                    echo 4;
                    exit;
                }
            }

            $delstatus = $cate->where($where)->delete();
            if(!$delstatus){
                echo 2;
                exit;
            }

            echo 1;

        }
    }

    /**
     * 获取主菜单的信息
     */
    public function getmininfo(){
        $mcate = M('cate');
        if(IS_POST){
            $eid = intval(I('post.eid'));
            $where['topid'] = $eid;
            $mres = $mcate ->field('topname,topename')->where($where)->find();
            echo json_encode($mres);
            exit;
        }
        exit;
    }

    /**
     * 更新主菜单
     */
    public function updateminmenu(){
        $ucate = M('cate');
        if(IS_POST){
            $topid= intval(I('post.topid'));
            $topname = I('post.topname');
            $topename = I('post.topename');
            if(empty($topname)){
                echo '名称不能为空';
                exit;
            }
            if(empty($topename)){
                echo '英文名称不能为空';
                exit;
            }
            $where['topid'] = $topid;
            $data['topname'] = $topname;
            $data['topename'] = $topename;
            $ustatus = $ucate->where($where)->save($data);
            if($ustatus){
                echo '更新成功！';
            }else{
                echo '更新失败！';
            }
        }
        exit;
    }

    /**
     * 二级菜单更新功能数据获取
     */
    public function getchilinfo(){
        $gcate = M('cate');
        $grule = M('auth_rule');
        if(IS_POST){
            $id = intval(I('post.uid'));
            $where['id'] = $id;
            $gres = $gcate->where($where)->field('catename,alink,orderid,ename,topid')->find();
            $where1['name'] = $gres['alink'];
            $name=$grule->where($where1)->field('topid')->find();

            $gres['name'] = $name['topid'];
            echo json_encode($gres);
            exit;
        }
    }

    /**
     * 更新子菜单信息
     */
    public function upchilinfo(){
        $ucate = M('cate');
        $urule = M('auth_rule');
        if(IS_POST){
            $id = intval(I('post.id'));
            $att = I('post.att');
            $link = I('post.link');
            $grule = I('post.grule');
            $cname = I('post.cname');
            $ename = I('post.ename');
            $sort = I('post.sort');
            $oldlink = I('post.oldlink');

            if($oldlink !=$link){
                $whered['name'] = $oldlink;
                $urule->where($whered)->delete();
            }

            $where['id'] = $id;
            $data['topid'] = $att;
            $data['alink'] = $link;
            $data['catename'] = $cname;
            $data['ename'] = $ename;
            $data['orderid'] = $sort;
            $where2['topid'] = $att;
            $chname = $ucate->where($where2)->field('topname,topename')->find();
            $data['topname'] = $chname['topname'];
            $data['topename'] = $chname['topename'];

            $ucate -> where($where)->save($data);

            if($urule->field('id')->where('name='."'".$link."'")->find()){
                $data1['title'] = $cname;
                $data1['topid'] = $grule;
                $data1['orderid'] = $sort;
                $urule->where('name='."'".$link."'")->save($data1);
            }else{
                $data1['title'] = $cname;
                $data1['topid'] = $grule;
                $data1['orderid'] = $sort;
                $data1['name'] = $link;
                $urule->add($data1);
            }

            echo '更新成功！';
        }

    }

    /**
     * 语言包管理
     */
    public function lang(){

        $zhch = MODULE_PATH.'Lang/zh-cn.php';
        $enus = MODULE_PATH.'Lang/en-us.php';

        if(IS_POST){
            $cn = $_POST['cn'];
            $en = $_POST['en'];
            $status1 = file_put_contents($zhch,$cn);
            $status2 = file_put_contents($enus,$en);
            if(!$status1){
                $this->error(L('Cn').L('ModifyFailure'));
            }else if(!$status2){
                $this->error(L('En').L('ModifyFailure'));
            }else{
                $this->success(L('ModifySuccess'),'lang');
            }
            exit;
        }

        $z = file_get_contents($zhch);
        $e = file_get_contents($enus);

        //备份
        //@file_put_contents($zhch.'.bak',$z);
        //@file_put_contents($enus.'.bak',$e);

        $this->assign('cn',$z);
        $this->assign('en',$e);
        $this->display();

    }


	/**
	 * 定义操作方法不存在的方法调用 404 页面
	 */ 
	public function _empty(){
		
		header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
		$this->display("./Public/404.html");
	}
}