<?php
namespace Api\Model;
class News_listModel extends DefaultModel{
    public function getList($page,$size){
        $webNavi=M('web_navi');
        /**广告部分************************************/
       $navid=$webNavi->field('id')->where(array('language'=>LANG_SET,'link_table'=>'news_list'))->find()['id'];
       $advObj=new \Api\Model\AdvertisementModel;
       $advList=$advObj->getList($navid,$page);
       $advpro=array();
       if($advList){
           $size=$size-count($advList);
           foreach($advList as $key=>$val){
               $advpro[$key]['tag']="";
               $advpro[$key]['tit_name']=$val['tit_name'];
               $advpro[$key]['link_address']=$val['link_address'];
               $advpro[$key]['icon_image']=$val['icon_image'];
               $advpro[$key]['come_from']="";
               $advpro[$key]['create_date']=L("Advertisement");
           }
       }
       /**************************************/
        
        $result=null;
        $DB_PREFIX=C('DB_PREFIX');
        $page=$page*$size;
        $sql="select b.name as tag,a.tit_name,a.icon_image,a.link_address,a.come_from,a.create_date from "
                .$DB_PREFIX."news_list a join ".$DB_PREFIX."news_navi b on a.news_nav_id=b.id where a.is_put=1 and b.language='".LANG_SET."' "
                . "order by a.id desc limit $page,$size";
        $res=M()->query($sql);
        $res=$res?$res:array();
        if($res){
            foreach($res as $key=>$val){
                $res[$key]['icon_image']=explode(',',$val['icon_image']);
                $res[$key]['create_date']=timeformat($val['create_date']);
                foreach($res[$key]['icon_image'] as $k=>$v){
                    $res[$key]['icon_image'][$k]=$this->imgUrlPre.'/'.$v;
                }
                if(C('TEST_JSON')){
                    $res[$key]['tit_name']= urlencode($val['tit_name']);
                    $res[$key]['link_address']= urlencode($val['link_address']);
                }
            }
        }
        if($res or $advpro){
            $res=array_merge($res,$advpro);
            shuffle($res);
        }
        return $res;
    }
    
}

