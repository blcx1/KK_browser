<?php
/**
 * 搞笑
 */
namespace Home\Controller;
use Home\Controller\DefaultController;
class FunnyController extends DefaultController{
    public $url_pre=__ROOT__; //资源连接前辍
    public $size=10;

    public function index(){
        $Model=M();
        $DB_PREFIX=C('DB_PREFIX');
        $funnyList=$DB_PREFIX.'funny_list a';
        $funnyType=$DB_PREFIX.'funny_type b';
        $order='a.id asc';
        $where['b.language'] = $this->lang;
        $table=$funnyList.' join '.$funnyType.' on a.tid=b.id';
        $res=$Model->table($table)->where($where)->order($order)->limit($this->size)->select();
        if($res){
                $res=$this->urlImg($res);
                shuffle($res);
        }
        $this->assign('funnylist',$res);
        $this->display();
    }

    public function footerLoad(){
        if(empty(I('post.keys'))){
            $this->ajaxReturn('no data');
            exit();
        }
        $page=I('post.keys');
        $Model=M();
        $DB_PREFIX=C('DB_PREFIX');
        $funnyList=$DB_PREFIX.'funny_list a';
        $funnyType=$DB_PREFIX.'funny_type b';
        $table=$funnyList.' join '.$funnyType.' on a.tid=b.id';
        $where='b.language="'.$this->lang.'"';
        $count=$Model->table($table)->field('a.id')->where($where)->count();
        $pages = ceil($count/$this->size);
        if($page>=$pages){
            $this->ajaxReturn('no data');
            exit();
        }else{
            $field='a.tit_name,a.icon_image,a.txt_content,a.link_address,a.mimtype,a.praise,b.type_name,b.icon';
            $order='a.id asc';
            $res=$Model->table($table)->field($field)->where($where)->order($order)->limit($page*$this->size,$this->size)->select();
            if($res){
                $res=$this->urlImg($res);
            }
            $this->ajaxReturn($res);
        }
    }
    
    //图片资源路径前缀添加
    public function urlImg($data){
        foreach($data as $key=>$val){
            if(!empty($val['icon_image'])){
                $data[$key]['icon_image']= str_replace('Public/', $this->url_pre.'/Public/', $val['icon_image']);
            }
            if(!empty($val['icon'])){
                $data[$key]['icon']=$this->url_pre.$val['icon'];
            }
        }
        return $data;
    }

}

