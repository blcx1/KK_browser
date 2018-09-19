<?php
/**
 * 视频
 */
namespace Home\Controller;
use Home\Controller\DefaultController;
class VideoController extends DefaultController{
    public function index(){
        $VideoList=new \Home\Model\Video_listModel;
        $listInfo=$VideoList->getList($this->lang);
        $this->assign('list',$listInfo);
        $this->display();
    }
    
    //推荐列表拉取尾部时ajax加载
    public function footerLoad(){
        if(empty(I('post.keys'))){
            echo '';
            exit();
        }
        $page=I('post.keys');
        $VideoList=new \Home\Model\Video_listModel;
        $listInfo=$VideoList->getList($this->lang,$page);
        if(empty($listInfo)){
            $page=0;
            $listInfo=$VideoList->getList($this->lang,$page);
        }
        if($listInfo){
            $result['status']=array('page'=>$page);
            $result['data']=$listInfo;
            echo json_encode($result);
        }
    }
}