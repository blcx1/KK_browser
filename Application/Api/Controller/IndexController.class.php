<?php
/**
 * APP数据接口
 * 2017-10-23
 */
namespace Api\Controller;
class IndexController extends DefaultController{
/*
 * 频道tab接口
 */
    public function index(){
        $webNavi=M("web_navi");
        $webNaviInfo=$webNavi->field('name,link_table as nav_mark')->where('language="'.LANG_SET.'"')->select();
        $this->checkJson($webNaviInfo);
    }
    
/*
 * 首页接口(tab+推荐)
 */
    public function reIndex(){
        $data=$this->data;
        $webNavi=M("web_navi");
        $webNaviInfo=$webNavi->field('name,link_table as nav_mark')->where('language="'.LANG_SET.'"')->select();
        $recomModel=new \Api\Model\Recommend_listModel();
        $recomInfo=$recomModel->getList($this->page,$this->size);
        $result=array('tab'=>$webNaviInfo,'recom'=>$recomInfo);
        $this->checkJson($result);
    }
    
/*
 * 推荐、新闻、娱乐、汽车
 */
    public function reNewsEntCar(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark'])){
            $this->checkJson($data,'997');
        }
        
        $navMark=$data['nav_mark'];
        
        if($navMark=='recommend_list'){
            $recomModel=new \Api\Model\Recommend_listModel();
            $result=$recomModel->getList($this->page,$this->size);
        }else if($navMark=='news_list'){
            $newsObj=new \Api\Model\News_listModel;
            $result=$newsObj->getList($this->page, $this->size);
        }else if($navMark=='entertainment_list'){
            $entObj=new \Api\Model\Entertainment_listModel;
            $result=$entObj->getList($this->page,$this->size);
        }else if($navMark=='car_list'){
            $carObj=new \Api\Model\Car_listModel;
            $result=$carObj->getList($this->page,$this->size);
        }
        $this->checkJson($result);
    }
    
    /**
     * 视频接口
     */
    public function video(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='video_list'){
            $this->checkJson($data,'997');
        }        
        $videoObj=new \Api\Model\Video_listModel;
        $result=$videoObj->getList($this->page, $this->size);
        $this->checkJson($result);
    }
    
    /**
     * 小说接口
     */
    public function book(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='book_list'){
            $this->checkJson($data,'997');
        }
        $bookObj=new \Api\Model\Book_listModel;
        $result=$bookObj->getList($this->page,$this->size);
        $this->checkJson($result);
    }
    
    /**
     * 小说接口-最受追捧
     */
    public function bookHot(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='book_list'){
            $this->checkJson($data,'997');
        }
        $bookhotObj=new \Api\Model\Book_hotModel;
        $result=$bookhotObj->getList($this->page,$this->size);
        $this->checkJson($result);
    }
    
    /**
     * 搞笑
     */
    public function funny(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='funny_list'){
            $this->checkJson($data,'997');
        }
        $funnyObj=new \Api\Model\Funny_listModel;
        $result=$funnyObj->getList($this->page,$this->size);
        $this->checkJson($result);
    }
    
    /**
     * 美女类型
     */
    public function beautyType(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='beauty_list'){
            $this->checkJson($data,'997');
        }
        $beautyTypeObj=new \Api\Model\Beauty_typeModel;
        $type=$beautyTypeObj->getList();
        $type=$type?$type:array();
        $typeRes=array();
        if(!empty($type)){
            $key=array_rand($type,1);
            $id=$type[$key]['id'];
            $type_name=$type[$key]['type_name'];
            $beautyListObj=new \Api\Model\Beauty_listModel;
            $typeRes=$beautyListObj->getTypeList($id,$type_name,$this->page,$this->size); //一个随机类型数据列表
        }
        $result=array('type'=>$type,'typeList'=>$typeRes);
        $this->checkJson($result);
    }
    
    /**
     * 美女类型详情列表
     */
    public function beautyTypeList(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='beauty_list' || empty($data['type_id']) || empty($data['type_name'])){
            $this->checkJson($data,'997');
        }
        $id=$data['type_id'];
        $type_name=$data['type_name'];
        $beautyListObj=new \Api\Model\Beauty_listModel;
        $result=$beautyListObj->getTypeList($id,$type_name,$this->page,$this->size);
        $this->checkJson($result);
    }
    
    /**
     * 更多美女
     */
    public function beautyMore(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='beauty_list'){
            $this->checkJson($data,'997');
        }
        $beautyListObj=new \Api\Model\Beauty_listModel;
        $result=$beautyListObj->getList($this->page,$this->size);
        $this->checkJson($result);
    }
    
    /**
     * 游戏
     */
    public function game(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='game_list'){
            $this->checkJson($data,'997');
        }
        $gameTypeObj=new \Api\Model\Game_typeModel;
        $type=$gameTypeObj->getList();
        //游戏情报
        $gameListObj=new \Api\Model\Game_listModel;
        $gameInformer=$gameListObj->getTypeList($this->page,$this->size);
        //热门推荐
        $gameReObj=new \Api\Model\Game_recomModel;
        $hotRecom=$gameReObj->getList($this->page, $this->size);
        
        $type=$type?$type:array();
        $gameInformer=$gameInformer?$gameInformer:array();
        $hotRecom=$hotRecom?$hotRecom:array();
        $result=array('type'=>$type,'gameInformer'=>$gameInformer,'hotRecom'=>$hotRecom);
        
        $this->checkJson($result);
    }
    
    /**
     * 分类游戏情报
     */
    public function gameTypeList(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='game_list' || !$data['type_id']){
            $this->checkJson($data,'997');
        }
        $id=$data['type_id'];
        $gameListObj=new \Api\Model\Game_listModel;
        $result=$gameListObj->getTypeList($this->page,$this->size,$id);
        $this->checkJson($result);
    }
    
    /**
     * 更多游戏
     */
    public function gameMore(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='game_list'){
            $this->checkJson($data,'997');
        }
        $gameListObj=new \Api\Model\Game_listModel;
        $result=$gameListObj->getList($this->page,$this->size);
        $res=array();
        if($result){
            foreach($result as $key=>$val){
                $res[$key]['gamename']=$val['subtitle'];
                $res[$key]['keyword']=array('size'=>'','RPG'=>'','type'=>'');
                $res[$key]['introduction']='';
                $res[$key]['icon']=$val['icon'];
                $res[$key]['link']=$val['link_address'];
            }
        }
        $result=$res;
        $this->checkJson($result);
    }
    
    /**
     * 好物
     */
    public function shop(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='shop_list'){
            $this->checkJson($data,'997');
        }
        $shopTypeObj=new \Api\Model\Shop_typeModel;
        $type=$shopTypeObj->getList();
        //热卖专栏
        $shopListObj=new \Api\Model\Shop_listModel;
        $hot=$shopListObj->hot($this->size);
        //推荐专栏
        $recom=$shopListObj->recom($this->size);
        
        $type=$type?$type:array();
        $hot=$hot?$hot:array();
        $recom=$recom?$recom:array();
        $result=array('type'=>$type,'hot'=>$hot,'recom'=>$recom);
        $this->checkJson($result);
    }
    
    /**
     * 分类好物详情列表
     */
    public function shopTypeList(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='shop_list' || !$data['type_id']){
            $this->checkJson($data,'997');
        }
        $id=$data['type_id'];
        $shopListObj=new \Api\Model\Shop_listModel;
        $result=$shopListObj->typeList($this->page,$this->size,$id);
        $this->checkJson($result);
    }
    
    /**
     * 全部好物
     */
    public function shopAll(){
        $data=$this->data;
        if(empty($data) || empty($data['nav_mark']) || $data['nav_mark']!='shop_list'){
            $this->checkJson($data,'997');
        }
        $shopListObj=new \Api\Model\Shop_listModel;
        $result=$shopListObj->getList($this->page,$this->size);
        $this->checkJson($result);
    }
    
    /**
     * 网址导航
     */
    public function websitnav(){
        $webNavObj=new \Api\Model\Api_navModel;
        $result=$webNavObj->getList($this->page,$this->size);
        $this->checkJson($result);
    }
    
    /**
     * 空操作
     */
    public function _empty(){
         $this->checkJson($this->data,'404');
    }
}
