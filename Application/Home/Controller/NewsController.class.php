<?php
namespace Home\Controller;
use Home\Controller\DefaultController;
class NewsController extends DefaultController{
    public function index(){

        $newsList=new \Home\Model\News_listModel;
        $listInfo=$newsList->getList($this->lang);
        $this->assign('tag',$tag);
        $this->assign('list',$listInfo);
        $this->display();
    }
    
    //新闻列表拉取尾部时ajax加载
    public function footerLoad(){
        if(empty(I('post.keys'))){
            echo '';
            exit();
        }
        $page=I('post.keys');
        $newsList=new \Home\Model\News_listModel;
        $listInfo=$newsList->getList($this->lang,$page);
        if(empty($listInfo)){
            $page=0;
            $listInfo=$newsList->getList($this->lang,$page);
        }
        if($listInfo){
            $result['status']=array('page'=>$page);
            $result['data']=$listInfo;
            echo json_encode($result);
        }
    }
}

