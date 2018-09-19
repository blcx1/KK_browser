<?php

namespace Home\Controller;
use Home\Controller\DefaultController;
class IndexController extends DefaultController{
    private $recomListModel=null;
    
    public function _initialize(){
        $this->recomListModel=new \Home\Model\Recommend_listModel;
    }
    
    //头部标签
    public function index(){
    /*    $webNavi=M("web_navi");
        $webNaviInfo=$webNavi->field('name,url')->where('language="'.$this->lang.'"')->select();
        foreach($webNaviInfo as $k=>$val){
            $webNaviInfo[$k]['url']=__MODULE__.$val['url'];
        }
        $this->assign('webNavi',$webNaviInfo);
        $this->display();*/
        $this->redirect('Index/recom');
    }
    
    //推荐列表
    public function recom(){
        $listInfo=$this->recomListModel->getList($this->lang);
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
        $listInfo=$this->recomListModel->getList($this->lang,$page);
        if(empty($listInfo)){
            $page=0;
            $listInfo=$this->recomListModel->getList($this->lang,$page);
        }
        if($listInfo){
            $result['status']=array('page'=>$page);
            $result['data']=$listInfo;
            echo json_encode($result);
        }
    }
    
    //推荐列表滑动头部时ajax刷新页面
    public function headRef(){
        $listInfo=$this->recomListModel->getList($this->lang);
        echo json_encode($listInfo);
    }
    
}
