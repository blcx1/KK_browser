<?php
namespace Admin\Model;
class Shop_listModel extends DefaultInitModel{
    public function getList($pageNo,$pageSize,$arrId=array(),$is_put='1'){
        $table=$this->tables();
        $where='a.is_put='.$is_put.(empty($arrId)?null:' and a.id in ('.implode(',',$arrId).')');
        $field='a.id,a.shop_image,a.shop_name,a.shop_price,a.out_count,a.link_address,a.recom,b.type_name,b.language';
        $limit=$pageNo.','.$pageSize;
        $sql='select '.$field.' from '.$table.' where '.$where.' order by a.id desc limit '.$limit;
        $res=$this->query($sql);
        if($res){
            foreach($res as $key=>$val){
                if(!startWith($val['shop_image'], 'http')){
                    $res[$key]['shop_image']=__ROOT__.'/'.$val['shop_image'];
                }
                $res[$key]['languages'] = L($val['language']);
            }
        }
        return $res;
    }
    
    public function count($is_put='1'){
        $table=$this->tables();
        $where='a.is_put='.$is_put;
        $field='count(a.id) as num';
        $sql='select '.$field.' from '.$table.' where '.$where;
        $res=M()->query($sql);
        $res=!empty($res) ? $res[0]['num'] : false;
        return $res;
    }
    
    public function tables(){
        $shoplist=C('DB_PREFIX').'shop_list a';
        $shoptype=C('DB_PREFIX').'shop_type b';
        $table=$shoplist.' join '.$shoptype.' on a.type_id=b.id';
        return $table;
    }
}