<?php
namespace Api\Model;
use Think\Model;
class DefaultModel extends Model{
    public $imgUrlPre='';
    public function _initialize() {
        parent::_initialize();
        $this->imgUrlPre='http://'.$_SERVER['HTTP_HOST'].__ROOT__;
    }
    
    public function saveDates($list){
        if(!$list || !is_array($list)){
            return false;
        }
        $res=$this->addAll($list);
        return $res;
    }
}

