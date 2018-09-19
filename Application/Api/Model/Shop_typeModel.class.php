<?php
namespace Api\Model;
class Shop_typeModel extends DefaultModel{
    public function getList(){
        $field='id,type_name';
        $res=$this->field('id,type_name')->where(array('language'=>LANG_SET))->limit(30)->select();
        return $res;
    }
}

