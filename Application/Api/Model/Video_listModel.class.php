<?php

namespace Api\Model;
class Video_listModel extends DefaultModel{   
    public function getList($page,$size){
        $webNavi=M('web_navi');
        /**广告部分************************************/
       $navid=$webNavi->field('id')->where(array('language'=>LANG_SET,'link_table'=>'video_list'))->find()['id'];
       $advObj=new \Api\Model\AdvertisementModel;
       $advList=$advObj->getList($navid,$page);
       $advpro=array();
       if($advList){
           $size=$size-count($advList);
           foreach($advList as $key=>$val){
               $advpro[$key]['tit_name']=$val['tit_name'];
               $advpro[$key]['icon_image']=$val['icon_image'][0];
               $advpro[$key]['link_address']=$val['link_address'];
               $advpro[$key]['time']="";
               $advpro[$key]['come_from']=L("Advertisement");
           }
       }
       /**************************************/
        
        $page=$page*$size;
        $field='tit_name,icon_image,link_address,time,come_from';
        $res=$this->field($field)->where(array('language'=>LANG_SET,'is_put'=>1))->order("id desc")->limit($page,$size)->select();
        $res=$res?$res:array();
        if($res or $advpro){
            if($res){
                foreach($res as $k=>$val){
                    $res[$k]['icon_image']=$this->imgUrlPre.'/'.$val['icon_image'];
                    if(C('TEST_JSON')){
                        $res[$k]['tit_name']= urlencode($val['tit_name']);
                        $res[$k]['link_address']= urlencode($val['link_address']);
                    }
                }
            }
            $res=array_merge($res,$advpro);
            shuffle($res);
        }
        return $res;
    }
}
