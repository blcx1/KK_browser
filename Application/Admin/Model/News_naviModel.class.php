<?php
namespace Admin\Model;
class News_naviModel extends \Think\Model{
    
    public function listAll($field='',$pagenow,$size){
        if($field and is_string($field)){
            $info=$this->field($field)->limit($pagenow.','.$size)->select();
        }else{
            $info=$this->limit($pagenow.','.$size)->select();
        }
        return $info;
    }

    public function counts(){
        return $this->count();
    }
    
    public function deleteEntByID($arrayId){
        $res=false;
        if(is_array($arrayId)){
            $id=implode(',', $arrayId);
            $res=$this->delete($id);
        }else{
            $res=$this->delete($arrayId);
        }
        return $res;
    }
    
    //添加新闻版块
    public function addlist($data){
        $res=false;
        if(is_array($data)){
            $res=$this->add($data);
        }
        return $res;
    }

    //更新新闻版块
    public function updatelist($data){
        $res=false;
        //dump($data);
        if(is_array($data)){
            $res=$this->save($data);
            //echo $this->_sql();
        }
        return $res;
    }
    
    //按语言分类
    public function getList($language){
        $res=$this->field('id,name')->where(array('language'=>$language))->order('id')->select();
        return $res;
    }
    
    //检测语言和类型是否存在
    public function isType($langName,$type){
        $tabpre=C('DB_PREFIX');
        $field='a.iso_code,b.id';
        $table=$tabpre.'language a join '.$tabpre.'news_navi b on a.iso_code=b.language';
        $sql='select '.$field.' from '.$table.' where a.name="'.$langName.'" and b.name="'.$type.'"';
        $res=$this->query($sql);
        if($res) return $res;
    }
    
}
