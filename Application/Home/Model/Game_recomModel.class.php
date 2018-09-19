<?php
namespace Home\Model;
use Think\Model;
class Game_recomModel extends Model{
    public $url_pre=__ROOT__.'/'; //资源连接前辍
    public $size=10;
    
    public function getList($lang){
        $field='gamename,keyword,introduction,icon,link';
        $where='language="'.$lang.'"';
        $order='id desc';
        $res=$this->field($field)->where($where)->order($order)->limit($this->size)->select();
        if($res){
            foreach($res as $key=>$val){
                $res[$key]['icon']=$this->url_pre.$val['icon'];
                $res[$key]['keyword']=unserialize($val['keyword']);
            }
        }
        return $res;
    }
}

