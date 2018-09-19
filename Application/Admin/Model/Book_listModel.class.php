<?php
namespace Admin\Model;
class Book_listModel extends DefaultInitModel{
    public function getList($pageNo,$pageSize,$arrId=array(),$is_put='1'){
        $table=$this->tables();
        $where='a.is_put='.$is_put.(empty($arrId)?null:' and a.id in ('.implode(',',$arrId).')');
        $field='a.id,a.tit_name,a.icon_image,a.link_address,a.come_from,a.create_date,a.introduction,b.type_name,b.language';
        $limit=$pageNo.','.$pageSize;
        $sql='select '.$field.' from '.$table.' where '.$where.' order by create_date desc limit '.$limit;
        $res=M()->query($sql);
        if($res){
            foreach($res as $key=>$val){
                if(!empty($val['icon_image'])){
                    if(!startWith($val['icon_image'], 'http')){
                        $res[$key]['icon_image']=__ROOT__.'/'.$val['icon_image'];
                    }
                }else{
                    $res[$key]['icon_image']=__ROOT__.'/Public/images/default.png';
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
        $booklist=C('DB_PREFIX').'book_list a';
        $booktype=C('DB_PREFIX').'book_type b';
        $table=$booklist.' join '.$booktype.' on a.tid=b.id';
        return $table;
    }

}

