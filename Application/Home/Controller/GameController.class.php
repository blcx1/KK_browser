<?php
/**
 * 游戏
 */
namespace Home\Controller;
use Home\Controller\DefaultController;
class GameController extends DefaultController{
    public $url_pre=__ROOT__; //资源连接前辍
    public $game_type_object = null;
    public $game_list_object = null;
    public $size=10;
    public function _initialize(){
        $this->game_type_object = M('game_type');
        $this->game_list_object = M('game_list');
    }

    public function index(){
        //分类
        $where='language="'.$this->lang.'"';
        $type = $this->game_type_object->where($where)->order('id asc')->select();
        //首页默认
        $gameListObj=new \Home\Model\Game_listModel;
        $new_con=$gameListObj->getDefault($this->lang);
        //热门推荐
        $gameRecomObj=new \Home\Model\Game_recomModel;
        $recomInfo=$gameRecomObj->getList($this->lang);
        $this->assign('new_con',$new_con);
        $this->assign('type',$type);
        $this->assign('recom',$recomInfo);
        $this->display();
    }
    
    public function categery_newcon(){
        $cid = I('get.id');
        $where['tid'] = $cid;
        $res = $this->game_list_object->where($where)->order('id desc')->limit($this->size)->select();
        foreach($res as $key=>$val){
            if(!startWith($val['icon'], 'http')){
                $res[$key]['icon']=$this->url_pre.'/'.$val['icon'];
            }
        }
        $this->ajaxReturn($res);
    }

    public function footerLoad(){        
        if(empty(I('get.page'))){
            $this->ajaxReturn('no data');
            exit();
        }
        $page=I('get.page');
        $Model=M();
        $DB_PREFIX=C('DB_PREFIX');
        $gameList=$DB_PREFIX.'game_list a';
        $gameType=$DB_PREFIX.'game_type b';
        $table=$gameList.' join '.$gameType;
        $where='b.language="'.$this->lang.'" and a.tid=b.id';
        $count=$Model->table($table)->field('a.id')->where($where)->count();
        $pages = ceil($count/$this->size);
        if($page>$pages){
            $this->ajaxReturn('no data');
            exit();
        }else{
            $field='a.subtitle,a.icon,a.link_address';
            $res = $Model->table($table)->field($field)->where($where)->order('a.id desc')->limit(($page-1)*$this->size,$this->size)->select();
            if($res){
                foreach($res as $key=>$val){
                    if(!startWith($val['icon'], 'http')){
                        $res[$key]['icon']=$this->url_pre.'/'.$val['icon'];
                    }
                }
            }
            $this->ajaxReturn($res);
        }
    }
}