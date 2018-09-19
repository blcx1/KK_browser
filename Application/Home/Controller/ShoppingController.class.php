<?php
/**
 * 好物
 */
namespace Home\Controller;
use Home\Controller\DefaultController;
class ShoppingController extends DefaultController{
    public function index(){
        $typeObj=M('shop_type');
        $typeInfo=$typeObj->field('id,type_name')->where('language="'.$this->lang.'"')->select();
        $shopListObj=new \Home\Model\Shop_listModel;
        //热卖
        $hot=$shopListObj->hot($this->lang);
        //推荐
        $recom=$shopListObj->recom($this->lang);
        
        $this->assign('type',$typeInfo);
        $this->assign('hot',$hot);
        $this->assign('recom',$recom);
        $this->display();
    }
    
    //ajax请求
    public function typeList(){
        $id=I('post.id');
        if(empty($id)){
            exit();
        }
        $shopListObj=new \Home\Model\Shop_listModel;
        $result=$shopListObj->typeList($id);
        if($result){
            echo json_encode($result);
        }
    }
    
    public function footerLoad(){
        if(empty(I('post.keys'))){
            echo '';
            exit();
        }
        $page=I('post.keys');
        $shopListObj=new \Home\Model\Shop_listModel;
        $list=$shopListObj->getList($this->lang,$page);
        if($list){
            echo json_encode($list);
        }
    }
}

