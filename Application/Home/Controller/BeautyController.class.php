<?php
/**
 * 美女
 */
namespace Home\Controller;
use Home\Controller\DefaultController;
class BeautyController extends DefaultController{
    public $beauty_type_object = null;
    public $beauty_list_object = null;
    public $size=10;
    public $url_pre=__ROOT__; //资源连接前辍
    public function _initialize(){
        $this->beauty_type_object = M('beauty_type');
        $this->beauty_list_object = M('beauty_list');
    }
    //首页分类和图片列表
    public function index(){
        $typeObj=M('beauty_type');
        $type = $typeObj->where(array('language'=>$this->lang))->select();
        $type = !$type ? false : $this->imgUrl($type, 'icon_image');

        $tid=[];
        foreach($type as $tval){
            $tid[]=$tval['id'];
        }
        $max_id = count($tid)-1;
        $de_id = rand(1,$max_id);
        $Model=M();
        $DB_PREFIX=C('DB_PREFIX');
        $beautyList=$DB_PREFIX.'beauty_list a';
        $beautyType=$DB_PREFIX.'beauty_type b';
        $table=$beautyList.' join '.$beautyType.' on a.tid=b.id';
        $where='b.id='.$tid[$de_id];
        $field='a.icon_image as listimg,a.link_address,b.id,b.type_name';
        $order='a.id desc';
        $limit=$this->size;
        $sql='select '.$field.' from '.$table.' where '.$where.' order by '.$order.' limit '.$limit;
        $default_img=$Model->query($sql);
        $default_img=!$default_img ? false : $this->imgUrl($default_img, 'listimg');
        
        $where='b.language="'.$this->lang.'"';
        $field="a.icon_image,a.link_address";
        $sql='select '.$field.' from '.$table.' where '.$where.' order by '.$order.' limit '.$limit;
        $resd=$Model->query($sql);
        $resd=!$resd ? false : $this->imgUrl($resd, 'icon_image');
        
        $this->assign('new_img',$resd);
        $this->assign('default_img',$default_img);
        $this->assign('type',$type);
        $this->display();
    }

    //获取一个分类中最新的数据
    public function categoryimg(){
        $beautyListObj=M('beauty_list');
        $cid=I('get.id');
        $field='icon_image as listimg,link_address';
        $res=$beautyListObj->field($field)->where(array('tid'=>$cid))->order('id desc')->limit($this->size)->select();
        $res=!$res ? false : $this->imgUrl($res, 'listimg');
        $this->ajaxReturn($res);
    }

    public function footerLoad(){
        if(empty(I('post.keys'))){
            $this->ajaxReturn('no data');
            exit();
        }
        $page=I('post.keys');
        $Model=M();
        $DB_PREFIX=C('DB_PREFIX');
        $beautyList=$DB_PREFIX.'beauty_list a';
        $beautyType=$DB_PREFIX.'beauty_type b';
        $table=$beautyList.' join '.$beautyType.' on a.tid=b.id';
        $where='b.language="'.$this->lang.'"';
        $field='a.id';
        $count=$Model->table($table)->field($field)->where($where)->count();
        $pages = ceil($count/$this->size);
        if($page>$pages){
            $this->ajaxReturn('no data');
            exit();
        }else{
            $order='a.id desc';
            $field='a.icon_image,a.link_address';
            $res=$Model->table($table)->field($field)->where($where)->order($order)->limit($page*$this->size,$this->size)->select();
            $res=!$res ? false : $this->imgUrl($res, 'icon_image');
            $this->ajaxReturn($res);
        }
    }
    
    public function imgUrl($data,$img_key){
        foreach($data as $key=>$val){
                $data[$key][$img_key]=$this->url_pre.'/'.$val[$img_key];
        }
        return $data;
    }
}
