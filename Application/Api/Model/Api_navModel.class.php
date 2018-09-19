<?php
namespace Api\Model;
class Api_navModel extends DefaultModel{
    public function getList($page,$size){
        $field='icon,tit_name,link_address';
        $where=array('language'=>LANG_SET);
        $order='sort asc';
        $page=$page*size;
        $limit="$page,$size";
        $res=$this->field($field)->where($where)->order($order)->limit($limit)->select();
        if($res){
            foreach($res as $key=>$val){
                $res[$key]['icon']=$this->imgUrlPre.'/'.$val['icon'];
            }
        }
        return $res;
    }
}
