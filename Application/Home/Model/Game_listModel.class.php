<?php
namespace Home\Model;
use Think\Model;
class Game_listModel extends Model{
    public $url_pre=__ROOT__; //资源连接前辍
    public $size=10;
    
    public function getDefault($lang){
        $DB_PREFIX=C('DB_PREFIX');
        $gameList=$DB_PREFIX.'game_list a';
        $gameType=$DB_PREFIX.'game_type b';
        $field='a.subtitle,a.icon,a.link_address';
        $where='b.language="'.$lang.'"';
        $on='a.tid=b.id';
        $order='a.id desc';
        $limit=$this->size;
        $sql='select '.$field.' from '.$gameList.' join '.$gameType.' on '.$on.' where '.$where.' order by '.$order.' limit '.$limit;
        $res=M()->query($sql);
        if($res){
            foreach($res as $key=>$val){
                if(!startWith($val['icon'], 'http')){
                    $res[$key]['icon']=$this->url_pre.'/'.$val['icon'];
                }
            }
            shuffle($res);
        }
        return $res;
    }
}

