<?php
namespace Home\Controller;
use Think\Controller;
class DefaultController extends Controller{
    public $lang='';
    public function __construct() {
        parent::__construct();
        $this->lang=LANG_SET;
        C('HOST_URL','http://'.$_SERVER['HTTP_HOST'].__ROOT__);
        $this->head();
    }
    
    public function head(){
        $webNavi=M("web_navi");
        $webNaviInfo=$webNavi->field('name,url')->where('language="'.$this->lang.'"')->select();
        foreach($webNaviInfo as $k=>$val){
            $webNaviInfo[$k]['url']=__MODULE__.$val['url'];
        }
        $this->assign('webNavi',$webNaviInfo);
    }

}