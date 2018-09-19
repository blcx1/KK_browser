<?php
namespace Admin\Model;
class Shop_typeModel extends DefaultInitModel{
     //验证类型是否存在
    public function is_type($language,$type){
        $table=C('DB_PREFIX').'shop_type a join '.C('DB_PREFIX').'language b on a.language=b.iso_code';
        $field='a.id';
        $where='a.type_name="'.$type.'" and b.name="'.$language.'"';
        $sql='select '.$field.' from '.$table.' where '.$where;
        $res=$this->query($sql);
        $res=$res?$res:$this->is_type_sig($type);
        return $res;
    }
    
    //验证单条件类型是否存在
    public function is_type_sig($type){
        $res=$this->field('id')->where(array('type_name'=>$type))->select();
        return $res;
    }
    
    public function getTypeAll(){
        return $this->field('id,type_name')->select();
    }
}