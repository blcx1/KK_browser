<?php
/**
 * 小说
 */
namespace Home\Controller;
use Home\Controller\DefaultController;
class NovelController extends DefaultController{
    public function index(){
        $bookList=new \Home\Model\Book_listModel;
        $bookHot=new \Home\Model\Book_hotModel;
        
        $listInfo=$bookList->getList($this->lang);
        $hotInfo=$bookHot->getList($this->lang);
        $listInfo = $listInfo ? $listInfo : array();
        $hotInfo = $hotInfo ? $hotInfo : array();
        $this->assign('list',$listInfo);
        $this->assign('hot',$hotInfo);
        $this->display();
    }
    
    //新闻列表拉取尾部时ajax加载
    public function footerLoad(){
        if(empty(I('post.keys'))){
            echo '';
            exit();
        }
        $page=I('post.keys');
        $bookList=new \Home\Model\Book_listModel;
        $listInfo=$bookList->getList($this->lang,$page);
        if(empty($listInfo)){
            $page=0;
            $listInfo=$bookList->getList($this->lang,$page);
        }
        if($listInfo){
            $result['status']=array('page'=>$page);
            $result['data']=$listInfo;
            echo json_encode($result);
        }
    }
}

