<?php
namespace Api\Model;
class Game_typeModel extends DefaultModel{
    public function getList(){
        $res=$this->field('id,type_name')->where(array('language'=>LANG_SET))->limit(30)->select();
        return $res;
    }
}
