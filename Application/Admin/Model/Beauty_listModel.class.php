<?php
namespace Admin\Model;
class Beauty_listModel extends DefaultInitModel{
    public function count($is_put='1'){
        $table=$this->tables();
        $where='a.is_put='.$is_put;
        $field='a.id';
        $sql='select count('.$field.') as num from '.$table.' where '.$where;
        $res=$this->query($sql);
        $res=empty($res) ? 0 : $res[0]['num'];
        return $res;
    }
    
    public function getList($pageNo,$pageSize,$arrId=array(),$is_put='1'){
        $table=$this->tables();
        $where='a.is_put='.$is_put.(empty($arrId)?null:' and a.id in ('.implode(',',$arrId).')');
        $field='a.id,a.icon_image,a.link_address,b.type_name,b.language';
        $limit=$pageNo.','.$pageSize;
        $sql='select '.$field.' from '.$table.' where '.$where.' order by a.id desc limit '.$limit;
        $res=$this->query($sql);
        if($res){
            foreach($res as $key=>$val){
                $res[$key]['icon_image']=__ROOT__.'/'.$val['icon_image'];
                $res[$key]['languages'] = L($val['language']);
            }
        }
        return $res;
    }
    
    public function tables(){
        $beauty_list=C('DB_PREFIX').'beauty_list a';
        $beauty_type=C('DB_PREFIX').'beauty_type b';
        $tables=$beauty_list.' join '.$beauty_type.' on a.tid=b.id';
        return $tables;
    }
}