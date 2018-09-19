<?php
namespace Api\Model;
class Entertainment_listModel extends DefaultModel{
    public function getList($page,$size){
        $result=false;
        $ordinary=$this->tyList($page,$size, 0);//普通
        $top=$this->tylist($page,$size,1);//头条
        $ordinary = $ordinary ? $ordinary : array();
        $top = $top ? $top : array();
        
    /*    if(!empty($top) || !empty($ordinary)){
            $result=array('ordinary'=>$ordinary,'top'=>$top);
        }*/
        $top1=array();
        if(!empty($top)){
            $top1[0]=$top[0];
            $top1[0]['tag']='top';//头条
        }
        if(!empty($ordinary)){
            //普通
            foreach($ordinary as $key=>$val){
                $ordinary[$key]['tag']='ordinary';
            }
        }
        $result= array_merge($top1,$ordinary);
        return $result;
    }
    
    //按头条、普通类型分别获取数据列表
    public function tyList($page,$size,$isTop){
       /**广告部分************************************/
       $advpro=array();
       if($isTop===0){
            $webNavi=M('web_navi');
            $navid=$webNavi->field('id')->where(array('language'=>LANG_SET,'link_table'=>'entertainment_list'))->find()['id'];
            $advObj=new \Api\Model\AdvertisementModel;
            $advList=$advObj->getList($navid,$page);
            if($advList){
                $size=$size-count($advList);
                foreach($advList as $key=>$val){
                    $advpro[$key]['tit_name']=$val['tit_name'];
                    $advpro[$key]['icon_image']=$val['icon_image'];
                    $advpro[$key]['link_address']=$val['link_address'];
                    $advpro[$key]['come_from']="";
                    $advpro[$key]['create_date']=L("Advertisement");
                }
            }
       }
       /**************************************/
        $page=$page*$size;
        $field='tit_name,icon_image,link_address,come_from,create_date';
        $res=$this->field($field)->where(array('language'=>LANG_SET,'is_top'=>$isTop,'is_put'=>1))->order('id desc')->limit($page,$size)->select();
        $res=$res?$res:array();
        if($res){
            foreach($res as $k=>$v){
                if(C('TEST_JSON')){
                    $res[$k]['tit_name']= urlencode($v['tit_name']);
                    $res[$k]['link_address']= urlencode($v['link_address']);
                }
                $res[$k]['create_date']=timeformat($v['create_date']);
                $res[$k]['icon_image']=explode(',',$v['icon_image']);
                foreach($res[$k]['icon_image'] as $i=>$ar){
                    $res[$k]['icon_image'][$i]=$this->imgUrlPre.'/'.$ar;
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

