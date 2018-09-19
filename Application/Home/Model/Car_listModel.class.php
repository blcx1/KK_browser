<?php
namespace Home\Model;
use Think\Model;
class Car_listModel extends Model{
    public $size=15;
    public function getList($lang,$page=0){
        $field='tit_name,icon_image,link_address,come_from,create_date';
        $where=array('language'=>$lang,'is_top'=>0);
        $page=$page*$this->size;
        $limit="$page,$this->size";
        $res=$this->field($field)->where($where)->order('id desc')->limit($limit)->select();
        if($res){
            shuffle($res);
            foreach($res as $key=>$val){
                $res[$key]['icon_image']= explode(',', $val['icon_image']);
                $res[$key]['create_data']= timeformat($val['create_date']);
                foreach($res[$key]['icon_image'] as $t=>$vl){
                    $res[$key]['icon_image'][$t]=__ROOT__.'/'.$vl;
                }
            }
        }
        return $res;
    }
    
    public function getTop($lang){
        $field='tit_name,icon_image,link_address,come_from,create_date';
        $where=array('language'=>$lang,'is_top'=>1);
        $res=$this->field($field)->where($where)->order('id desc')->find();
        if($res){
            $res['icon_image']= explode(',', $res['icon_image']);
            foreach($res['icon_image'] as $k=>$v){
                $res['icon_image'][$k]=__ROOT__.'/'.$v;
            }
            $res['create_date']= timeformat($res['create_date']);
        }
        return $res;
    }
}

