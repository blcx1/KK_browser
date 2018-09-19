<?php
namespace Admin\Model;
class Game_listModel extends DefaultInitModel{
    public function getList($pageNo,$pageSize,$arrId=array(),$is_put='1'){
        $table=$this->tables();
        $where='a.is_put='.$is_put.(empty($arrId)?null:' and a.id in ('.implode(',',$arrId).')');
        $field='a.id,a.tit_name,a.subtitle,a.icon,a.link_address,b.type_name,b.language';
        $limit=$pageNo.','.$pageSize;
        $sql='select '.$field.' from '.$table.' where '.$where.' order by a.id desc limit '.$limit;
        $res=$this->query($sql);
        if($res){
            foreach($res as $key=>$val){
                if(!startWith($val['icon'], 'http')){
                    $res[$key]['icon']=__ROOT__.'/'.$val['icon'];
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
        $gamelist=C('DB_PREFIX').'game_list a';
        $gametype=C('DB_PREFIX').'game_type b';
        $table=$gamelist.' join '.$gametype.' on a.tid=b.id';
        return $table;
    }
}

