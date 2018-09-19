<?php
namespace Api\Model;
class Funny_listModel extends DefaultModel{
    public function getList($page,$size){
        $Model=M();
        $DB_PREFIX=C('DB_PREFIX');
        $funnyList=$DB_PREFIX.'funny_list a';
        $funnyType=$DB_PREFIX.'funny_type b';
        $order='a.id asc';
        $where['b.language'] = LANG_SET;
        $where['a.is_put']=1;
        $table=$funnyList.' join '.$funnyType.' on a.tid=b.id';
        $field='a.tit_name,a.icon_image,a.txt_content,a.link_address,a.mimtype,b.type_name';
        $res=$Model->table($table)->field($field)->where($where)->order($order)->limit($page*$size,$size)->select();
        if($res){
            foreach($res as $key=>$val){
                if(!empty($val['icon_image'])){
                    $res[$key]['icon_image']= explode(',', str_replace('Public/', $this->imgUrlPre.'/Public/', $val['icon_image']));
                }else{
                    $res[$key]['icon_image']=array();
                }
            }
            shuffle($res);
        }
        return $res;
    }
}
