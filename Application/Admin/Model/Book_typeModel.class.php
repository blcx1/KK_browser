<?php
namespace Admin\Model;
class Book_typeModel extends DefaultInitModel{
    public function getTypeAll(){
        return $this->field('id,type_name')->select();
    }
}

