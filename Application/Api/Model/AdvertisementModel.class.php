<?php
namespace Api\Model;
class AdvertisementModel extends DefaultModel{
    private $flag=false; //判断是否重复执行过，用于获取不到数据时跳转到第一页获取。
    public function getList($navid,$page){
        $size=3;
        $res=$this->field('id,tit_name,icon_image,link_address')->where(array('nid'=>$navid))->limit($page*$size,$size)->order('id desc')->select();
        if($res){
            foreach($res as $key=>$val){
                $res[$key]['icon_image']=explode(',',$val['icon_image']);
                foreach($res[$key]['icon_image'] as $k=>$v){
                    $res[$key]['icon_image'][$k]=$this->imgUrlPre.'/'.$v;
                }
            }
        }
        if(!$res and !$this->$flag and $page>0){
            $res=$this->getList($navid, 0);
            $this->flag=true;
        }
        return $res;
    }
}

