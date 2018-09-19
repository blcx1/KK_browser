<?php
namespace Home\Model;
use Think\Model;
class News_listModel extends Model{
    public $size=10;
    
    public function getList($lang,$page=0){
        $result=null;
        $DB_PREFIX=C('DB_PREFIX');
        $page=$page*$this->size;
        $sql="select b.name as tag,a.tit_name,a.icon_image,a.link_address,a.come_from,a.create_date from "
                .$DB_PREFIX."news_list a join ".$DB_PREFIX."news_navi b on a.news_nav_id=b.id where b.language='".$lang."' "
                . "order by a.id desc limit $page,$this->size";
        $res=M()->query($sql);
        if($res){
            shuffle($res);
            foreach($res as $key=>$val){
                $tag[]=$val['tag'];
                $res[$key]['icon_image']=explode(',',$val['icon_image']);
                $res[$key]['create_date']=timeformat($val['create_date']);
                foreach($res[$key]['icon_image'] as $k=>$v){
                    $res[$key]['icon_image'][$k]=__ROOT__.'/'.$v;
                }
            }
            $tag=array_unique($tag);
            foreach($tag as $val){
                foreach($res as $v){
                    if($v['tag']==$val){
                        $result[$val][]=$v;
                    }
                }
            }
            $ttop=array();
            foreach($result as $key=>$val){
                if($key=='头条' || $key=='Headlines'){
                    $ttop[$key]=$val;
                    unset($result[$key]);
                }
            }
            if(!empty($ttop)){
                $result=array_merge($ttop,$result);
            }
        }
        return $result;
    }
}

