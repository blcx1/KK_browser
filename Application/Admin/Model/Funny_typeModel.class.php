<?php
namespace Admin\Model;
class Funny_typeModel extends DefaultInitModel{
    public function typeList(){
        return $this->field('id,type_name')->select();
    }
}

