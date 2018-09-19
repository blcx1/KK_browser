<?php
namespace Api\Model;
class Car_listModel extends DefaultModel{
    public function getList($page,$size){
       $webNavi=M('web_navi');
       /**广告部分************************************/
       $navid=$webNavi->field('id')->where(array('language'=>LANG_SET,'link_table'=>'car_list'))->find()['id'];
       $advObj=new \Api\Model\AdvertisementModel;
       $advList=$advObj->getList($navid,$page);
       $advpro=array();
       if($advList){
           $size=$size-count($advList);
           foreach($advList as $key=>$val){
               $advpro[$key]['tit_name']=$val['tit_name'];
               $advpro[$key]['icon_image']=$val['icon_image'];
               $advpro[$key]['link_address']=$val['link_address'];
               $advpro[$key]['come_from']="";
               $advpro[$key]['create_date']=L("Advertisement");
               $advpro[$key]['tag']='ordinary';
           }
       }
       /**************************************/
        
        $field='tit_name,icon_image,link_address,come_from,create_date';
        $where=array('language'=>LANG_SET,'is_top'=>0,'is_put'=>1);
        $page=$page*$size;
        $limit="$page,$size";
        $res=$this->field($field)->where($where)->order('id desc')->limit($limit)->select();
        $res=$res?$res:array();
        if($res or $advpro){
            if($res){
                foreach($res as $key=>$val){
                    if(C('TEST_JSON')){
                        $res[$key]['tit_name']= urlencode($val['tit_name']);
                        $res[$key]['link_address']= urlencode($val['link_address']);
                    }
                    $res[$key]['icon_image']= explode(',', $val['icon_image']);
                    $res[$key]['create_date']= timeformat($val['create_date']);
                    foreach($res[$key]['icon_image'] as $t=>$vl){
                        $res[$key]['icon_image'][$t]=$this->imgUrlPre.'/'.$vl;
                    }
                    $res[$key]['tag']='ordinary'; //普通
                }
            }
            $res=array_merge($res,$advpro);
            shuffle($res);
        }
        $top=$this->getTop();
        $top1=array();
        if(!empty($top)){
            $top1[0]=$top;
            $top1[0]['tag']='top';
        }
        $result= array_merge($top1,$res);
        return $result;
    }
    
    public function getTop(){
        $field='tit_name,icon_image,link_address,come_from,create_date';
        $where=array('language'=>LANG_SET,'is_top'=>1,'is_put'=>1);
        $res=$this->field($field)->where($where)->order('id desc')->find();
        if($res){
            if(C('TEST_JSON')){
                $res['tit_name']= urlencode($res['tit_name']);
                $res['link_address']= urlencode($res['link_address']);
            }
            $res['icon_image']= explode(',', $res['icon_image']);
            foreach($res['icon_image'] as $k=>$v){
                $res['icon_image'][$k]=$this->imgUrlPre.'/'.$v;
            }
            $res['create_date']= timeformat($res['create_date']);
        }
        return $res;
    }
}

