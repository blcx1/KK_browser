<?php
/**
 * 娱乐
 */
namespace Home\Controller;
use Home\Controller\DefaultController;
class EntController extends DefaultController{
    public function index(){
        $EntObj=new \Home\Model\Entertainment_listModel;
        $listInfo=$EntObj->getList($this->lang);
        $this->assign('list',$listInfo);
        $this->display();
    }
    
    public function footerLoad(){
        if(empty(I('post.keys'))){
            echo '';
            exit();
        }
        $page=I('post.keys');
        $EntObj=new \Home\Model\Entertainment_listModel;
        $listInfo=$EntObj->tyList($this->lang,$page,0);
        if(empty($listInfo)){
            $page=0;
            $listInfo=$EntObj->tyList($this->lang,$page,0);
        }
        if($listInfo){
            $result['status']=array('page'=>$page);
            $result['data']=$listInfo;
            echo json_encode($result);
        }
    }
}
