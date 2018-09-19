<?php
/**
 * 汽车
 */
namespace Home\Controller;
use Home\Controller\DefaultController;
class CarController extends DefaultController{
    public function index(){
        $carObj=new \Home\Model\Car_listModel;
        $listInfo=$carObj->getList($this->lang);
        $newTop=$carObj->getTop($this->lang);

        $this->assign('newtop',$newTop);
        $this->assign('list',$listInfo);
        $this->display();
    }
    
    public function footerLoad(){
        if(empty(I('post.keys'))){
            echo '';
            exit();
        }
        $page=I('post.keys');
        $carObj=new \Home\Model\Car_listModel;
        $listInfo=$carObj->getList($this->lang,$page);
        if(empty($listInfo)){
            $page=0;
            $listInfo=$carObj->getList($this->lang,$page);
        }
        if($listInfo){
            $result['status']=array('page'=>$page);
            $result['data']=$listInfo;
            echo json_encode($result);
        }
    }

}