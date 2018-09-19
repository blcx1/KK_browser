<?php
namespace Api\Model;
class Shop_listModel extends DefaultModel{
    public function hot($size){
        $DB_PREFIX=C('DB_PREFIX');
        $shopList=$DB_PREFIX.'shop_list a';
        $shopType=$DB_PREFIX.'shop_type b';
        $field='a.shop_image,a.shop_name,a.shop_price,a.out_count,a.link_address';
        $joinOn='a.type_id=b.id';
        $where='b.language="'.LANG_SET.'" and a.is_put=1';
        $order='a.id desc';
        $sql='select '.$field.' from '.$shopList.' join '.$shopType.' on '.$joinOn.' where '.$where.' order by '.$order.' limit '.$size;
        $res=M()->query($sql);
        if($res){
            foreach($res as $k=>$val){
                $res[$k]['shop_image']=$this->imgUrlPre.'/'.$val['shop_image'];
            }
        }
        return $res;
    }
    
    public function recom($size){
        $DB_PREFIX=C('DB_PREFIX');
        $shopList=$DB_PREFIX.'shop_list a';
        $shopType=$DB_PREFIX.'shop_type b';
        $field='a.shop_image,a.shop_name,a.shop_price,a.out_count,a.link_address';
        $joinOn='a.type_id=b.id';
        $where='b.language="'.LANG_SET.'" and a.recom=1 and a.is_put=1';
        $order='a.id desc';
        $sql='select '.$field.' from '.$shopList.' join '.$shopType.' on '.$joinOn.' where '.$where.' order by '.$order.' limit '.$size;
        $res=M()->query($sql);
        if($res){
            foreach($res as $k=>$val){
                $res[$k]['shop_image']=$this->imgUrlPre.'/'.$val['shop_image'];
            }
        }
        return $res;
    }
    
    public function typeList($page,$size,$id){
        $field='shop_image,shop_name,shop_price,out_count,link_address';
        $res=$this->field($field)->where('type_id='.$id.' and is_put=1')->order('id desc')->limit($page*$size,$size)->select();
        if($res){
            foreach($res as $k=>$val){
                $res[$k]['shop_image']=$this->imgUrlPre.'/'.$val['shop_image'];
            }
        }
        return $res;
    }
    
    public function getList($page,$size){
        $DB_PREFIX=C('DB_PREFIX');
        $shopList=$DB_PREFIX.'shop_list a';
        $shopType=$DB_PREFIX.'shop_type b';
        $field='a.shop_image,a.shop_name,a.shop_price,a.out_count,a.link_address';
        $joinOn='a.type_id=b.id';
        $where='b.language="'.LANG_SET.'" and a.is_put=1';
        $order='a.id desc';
        $page=$page*$size;
        $limit="$page,$size";
        $sql='select '.$field.' from '.$shopList.' join '.$shopType.' on '.$joinOn.' where '.$where.' order by '.$order.' limit '.$limit;
        $res=M()->query($sql);
        if($res){
            foreach($res as $k=>$val){
                $res[$k]['shop_image']=$this->imgUrlPre.'/'.$val['shop_image'];
            }
        }
        return $res;
    }
    
}

