<?php
namespace Api\Model;
class Game_listModel extends DefaultModel{
    
    public function getTypeList($page,$size,$id=false){
        if(!$id){
            $DB_PREFIX=C('DB_PREFIX');
            $gameList=$DB_PREFIX.'game_list a';
            $gameType=$DB_PREFIX.'game_type b';
            $jointable=$gameList.' join '.$gameType;
            $field='a.subtitle,a.icon,a.link_address';
            $on='b.id=a.tid';
            $where='b.language="'.LANG_SET.'" and a.is_put=1';
            $order='rand()';
            $limit=$page*$size.','.$size;
            $sql='select '.$field.' from '.$jointable.' on '.$on.' where '.$where.' order by '.$order.' limit '.$limit;
            $Model=M();
            $res=$Model->query($sql);
        }else{
            $field='subtitle,icon,link_address';
            $res=$this->field($field)->where('tid='.$id)->order('id desc')->limit($page*$size,$size)->select();
        }
        if($res){
            foreach($res as $key=>$val){
                if(!startWith($val['icon'], 'http')){
                    $res[$key]['icon']=$this->imgUrlPre.'/'.$val['icon'];
                }
            }
        }
        return $res;
    }
    
    public function getList($page,$size){
        $DB_PREFIX=C('DB_PREFIX');
        $gameList=$DB_PREFIX.'game_list a';
        $gameType=$DB_PREFIX.'game_type b';
        $jointable=$gameList.' join '.$gameType;
        $field='a.subtitle,a.icon,a.link_address';
        $on='b.id=a.tid';
        $where='b.language="'.LANG_SET.'" and a.is_put=1';
        $order='a.id desc';
        $limit=$page*$size.','.$size;
        $sql='select '.$field.' from '.$jointable.' on '.$on.' where '.$where.' order by '.$order.' limit '.$limit;
        $Model=M();
        $res=$Model->query($sql);
        if($res){
            foreach($res as $key=>$val){
                if(!startWith($val['icon'], 'http')){
                    $res[$key]['icon']=$this->imgUrlPre.'/'.$val['icon'];
                }
            }
            shuffle($res);
        }
        return $res;
    }
    
}

