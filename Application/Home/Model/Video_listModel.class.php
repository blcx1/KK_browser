<?php

namespace Home\Model;
use Think\Model;
class Video_listModel extends Model{
    public $size=5;
    
    public function getList($lang,$page=0){
        $page=$page*$this->size;
        $field='tit_name,icon_image,link_address,time,come_from';
        $res=$this->field($field)->where(array('language'=>$lang))->order("id desc")->limit($page,$this->size)->select();
        if($res){
            foreach($res as $k=>$val){
                $res[$k]['icon_image']=__ROOT__.'/'.$val['icon_image'];
            }
            shuffle($res);
        }
        return $res;
    }
}
