<?php
namespace Api\Model;
class Beauty_typeModel extends DefaultModel{
    public function getList(){
        $field='id,type_name,icon_image';
        $res=$this->field($field)->where('language="'.LANG_SET.'"')->limit(30)->select();
        if($res){
            foreach($res as $key=>$val){
                $res[$key]['icon_image']=$this->imgUrlPre.'/'.$val['icon_image'];
            }
        }
        return $res;
    }
}
