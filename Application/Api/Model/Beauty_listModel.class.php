<?php
namespace Api\Model;
class Beauty_listModel extends DefaultModel{
    public function getTypeList($id,$type_name,$page,$size){
        $field='icon_image,link_address';
        $res=$this->field($field)->where('tid='.$id.' and is_put=1')->order('id desc')->limit($page*$size,$size)->select();
        if($res){
            foreach($res as $key=>$val){
                $res[$key]['icon_image']=$this->imgUrlPre.'/'.$val['icon_image'];
                $res[$key]['type_name']=$type_name;
            }
        }
        return $res;
    }
    
    public function getList($page,$size){
       $webNavi=M('web_navi');
       /**广告部分************************************/
       $navid=$webNavi->field('id')->where(array('language'=>LANG_SET,'link_table'=>'beauty_list'))->find()['id'];
       $advObj=new \Api\Model\AdvertisementModel;
       $advList=$advObj->getList($navid,$page);
       $advpro=array();
       if($advList){
           $size=$size-count($advList);
           foreach($advList as $key=>$val){
               $advpro[$key]['link_address']=$val['link_address'];
               $advpro[$key]['icon_image']=$val['icon_image'][0];
           }
       }
       /**************************************/
        
        $DB_PREFIX=C('DB_PREFIX');
        $beautyList=$DB_PREFIX.'beauty_list a';
        $beautyType=$DB_PREFIX.'beauty_type b';
        $jointable=$beautyList.' join '.$beautyType;
        $field='a.icon_image,a.link_address';
        $on='b.id=a.tid';
        $where='b.language="'.LANG_SET.'" and a.is_put=1';
        $order='a.id desc';
        $limit=$page*$size.','.$size;
        $sql='select '.$field.' from '.$jointable.' on '.$on.' where '.$where.' order by '.$order.' limit '.$limit;
        $Model=M();
        $res=$Model->query($sql);
        $res=$res?$res:array();
        if($res){
            foreach($res as $key=>$val){
                $res[$key]['icon_image']=$this->imgUrlPre.'/'.$val['icon_image'];
            }
        }
        $res=array_merge($res,$advpro);
        shuffle($res);
        return $res;
    }
}

