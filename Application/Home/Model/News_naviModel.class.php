<?php
namespace Home\Model;
use Think\Model;
class News_naviModel extends Model{
    
    public function tagList($lang,$is_rand=false,$size='all'){
        $res=$this->field('name')->where(array('language'=>$lang))->select();
        if($is_rand && $res){
            shuffle($res); 
        }
        if($res && is_numeric($size)){
            $res=array_slice($res,0,8);
        }
        return $res;
    }
}

