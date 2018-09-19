<?php
namespace Home\Model;
use Think\Model;
class Shop_listModel extends Model{
    public $size=10;
    public $url_pre=__ROOT__.'/'; //资源连接前辍
    public function hot($lang){
        $DB_PREFIX=C('DB_PREFIX');
        $shopList=$DB_PREFIX.'shop_list a';
        $shopType=$DB_PREFIX.'shop_type b';
        $field='a.shop_image,a.shop_name,a.shop_price,a.out_count,a.link_address';
        $joinOn='a.type_id=b.id';
        $where='b.language="'.$lang.'"';
        $order='a.id desc';
        $sql='select '.$field.' from '.$shopList.' join '.$shopType.' on '.$joinOn.' where '.$where.' order by '.$order.' limit '.$this->size;
        $res=M()->query($sql);
        if($res){
            foreach($res as $k=>$val){
                $res[$k]['shop_image']=$this->url_pre.$val['shop_image'];
            }
        }
        return $res;
    }
    
    public function recom($lang){
        $DB_PREFIX=C('DB_PREFIX');
        $shopList=$DB_PREFIX.'shop_list a';
        $shopType=$DB_PREFIX.'shop_type b';
        $field='a.shop_image,a.shop_name,a.shop_price,a.out_count,a.link_address';
        $joinOn='a.type_id=b.id';
        $where='b.language="'.$lang.'" and a.recom=1';
        $order='a.id desc';
        $sql='select '.$field.' from '.$shopList.' join '.$shopType.' on '.$joinOn.' where '.$where.' order by '.$order.' limit '.$this->size;
        $res=M()->query($sql);
        if($res){
            foreach($res as $k=>$val){
                $res[$k]['shop_image']=$this->url_pre.$val['shop_image'];
            }
        }
        return $res;
    }
    
    public function typeList($id){
        $field='shop_image,shop_name,shop_price,out_count,link_address';
        $res=$this->field($field)->where('type_id='.$id)->order('id desc')->limit($this->size)->select();
        if($res){
            foreach($res as $k=>$val){
                $res[$k]['shop_image']=$this->url_pre.$val['shop_image'];
            }
        }
        return $res;
    }
    
    public function getList($lang,$page){
        $DB_PREFIX=C('DB_PREFIX');
        $shopList=$DB_PREFIX.'shop_list a';
        $shopType=$DB_PREFIX.'shop_type b';
        $field='a.shop_image,a.shop_name,a.shop_price,a.out_count,a.link_address';
        $joinOn='a.type_id=b.id';
        $where='b.language="'.$lang.'"';
        $order='a.id desc';
        $page=($page-1)*$this->size;
        $size=$this->size;
        $limit="$page,$size";
        $sql='select '.$field.' from '.$shopList.' join '.$shopType.' on '.$joinOn.' where '.$where.' order by '.$order.' limit '.$limit;
        $res=M()->query($sql);
        if($res){
            foreach($res as $k=>$val){
                $res[$k]['shop_image']=$this->url_pre.$val['shop_image'];
            }
        }
        return $res;
    }
    
}

