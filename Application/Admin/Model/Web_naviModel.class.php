<?php
namespace Admin\Model;
class Web_naviModel extends \Think\Model{
    
    public function listAll($nowpage,$size){
        $info=$this->limit($nowpage.','.$size)->select();
        return $info;
    }
    
    public function deleteEntByID($arrayId){
        $res=false;
        /*if(is_array($arrayId)){
            $id=implode(',', $arrayId);
            $res=$this->delete($id);
        }*/
        return $res;
    }

    public function counts(){
        return $this->count();
    }
}
