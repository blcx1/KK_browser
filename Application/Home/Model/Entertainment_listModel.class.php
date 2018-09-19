<?php
namespace Home\Model;
use Think\Model;
class Entertainment_listModel extends Model{
    public $size=15;
    
    public function getList($lang,$page=0){
        $result=false;
        $ordinary=$this->tyList($lang, $page, 0);//普通
        $top=$this->tylist($lang,$page,1);//头条
        $ordinary = $ordinary ? $ordinary : array();
        $top = $top ? $top : array();
        
        if(!empty($top) || !empty($ordinary)){
            $result=array('ordinary'=>$ordinary,'top'=>$top);
        }
        return $result;
    }
    
    //按头条、普通类型分别获取数据列表
    public function tyList($lang,$page,$isTop){
        $page=$page*$this->size;
        $field='tit_name,icon_image,link_address,come_from,create_date';
        $res=$this->field($field)->where(array('language'=>$lang,'is_top'=>$isTop))->order('id desc')->limit($page,$this->size)->select();
        if($res){
            shuffle($res);
            foreach($res as $k=>$v){
                $res[$k]['create_date']=timeformat($v['create_date']);
                $res[$k]['icon_image']=explode(',',$v['icon_image']);
                foreach($res[$k]['icon_image'] as $i=>$ar){
                    $res[$k]['icon_image'][$i]=__ROOT__.'/'.$ar;
                }
            }
        }
        return $res;
    }

}

