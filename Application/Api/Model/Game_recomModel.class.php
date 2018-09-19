<?php
namespace Api\Model;
class Game_recomModel extends DefaultModel{   
    public function getList($page,$size){
        $field='gamename,keyword,introduction,icon,link';
        $where='language="'.LANG_SET.'"';
        $order='id desc';
        $res=$this->field($field)->where($where)->order($order)->limit($page*$size,$size)->select();
        if($res){
            foreach($res as $key=>$val){
                $res[$key]['icon']=$this->imgUrlPre.'/'.$val['icon'];
                $res[$key]['keyword']=unserialize($val['keyword']);
            }
        }
        return $res;
    }
}

