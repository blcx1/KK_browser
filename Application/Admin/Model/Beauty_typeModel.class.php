<?php
namespace Admin\Model;
class Beauty_typeModel extends DefaultInitModel{
    public function is_type($language,$type){
        $tables=C('DB_PREFIX').'beauty_type a join '.C('DB_PREFIX').'language b on a.language=b.iso_code';
        $field='a.id';
        $where='a.type_name="'.$type.'" and b.name="'.$language.'"';
        $sql='select '.$field.' from '.$tables.' where '.$where;
        $res=$this->query($sql);
        $res=$res?$res:$this->is_type_sig($type);
        return $res;
    }
    
    /*只对类型不对语言*/
    public function is_type_sig($type){
        $tables=C('DB_PREFIX').'beauty_type a join '.C('DB_PREFIX').'language b on a.language=b.iso_code';
        $field='a.id';
        $where='a.type_name="'.$type.'"';
        $sql='select '.$field.' from '.$tables.' where '.$where;
        $res=$this->query($sql);
        return $res;
    }
    
    public function getNameList(){
        return $this->field('id,type_name')->select();
    }
}
