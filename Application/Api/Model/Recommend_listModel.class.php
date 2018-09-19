<?php

namespace Api\Model;
class Recommend_listModel extends DefaultModel{
    public $navTable='"news_list","video_list","entertainment_list"'; //推荐模块：新闻,视频,娱乐
    public $modeList=array(); //导航模块指定导航id:(news_list=>2)
            
    //获取推荐列表页
    public function getList($page=0,$size){
       $webNavi=M('web_navi');
    
    /**广告部分************************************/
       $navid=$webNavi->field('id')->where(array('language'=>LANG_SET,'link_table'=>'recommend_list'))->find()['id'];
       $advObj=new \Api\Model\AdvertisementModel;
       $advList=$advObj->getList($navid,$page);
       $advpro=array();
       if($advList){
           $size=$size-count($advList);
           foreach($advList as $key=>$val){
               $advpro[$key]['id']=$val['id'];
               $advpro[$key]['tit_name']=$val['tit_name'];
               $advpro[$key]['link_address']=$val['link_address'];
               $advpro[$key]['images']=$val['icon_image'];
               $advpro[$key]['come_from']="";
               $advpro[$key]['create_date']=L("Advertisement");
           }
       }
       /**************************************/
       
       $navInfo=$webNavi->field('id,link_table')->where('link_table in('.$this->navTable.') and language="'.LANG_SET.'"')->select();
       if(empty($navInfo)) return false;
       $tabpre=C('DB_PREFIX');
       $field='a.id,b.tit_name,b.icon_image as images,b.link_address,b.come_from,b.create_date';
       $sql='select '.$field.' from '.$tabpre;
       $sqlcon1=' union select '.$field.' from '.$tabpre;
       for($i=0;$i<count($navInfo);$i++){
           $where=' where a.nav_id='.$navInfo[$i]['id'];
           if($i===0){
                $sql.='recommend_list a join '.$tabpre.$navInfo[$i]['link_table'].' b on a.pid=b.id'.$where;
           }else{
               $sql.=$sqlcon1.'recommend_list a join '.$tabpre.$navInfo[$i]['link_table'].' b on a.pid=b.id'.$where;
           }
       }
       $sql.=' order by id desc limit '.$page*$size.','.$size;
       $listInfo=$this->query($sql);
       foreach($listInfo as $key=>$val){
           if(!empty($val['images'])){
               $listInfo[$key]['images']=explode(',',$val['images']);
               foreach($listInfo[$key]['images'] as $k=>$v){
                   $listInfo[$key]['images'][$k]=$this->imgUrlPre.'/'.$v;
               }
           }
           $listInfo[$key]['create_date']=timeformat($val['create_date']);
       }
       $listInfo=array_merge($advpro,$listInfo);
       shuffle($listInfo);
       return $listInfo;
    }
    
    /*
     * 自动添加推荐
     */
    public function putSource($language){
        $navInfo=M('web_navi')->field('id,link_table')->where('link_table in('.$this->navTable.') and language=\''.$language.'\'')->select();
        $date=date('Y-m-d');
        $tabpre=C('DB_PREFIX');
        $field='id';
        $where='create_date like \''.$date.'%\' and language=\''.$language.'\'';
        $order='id desc';
        $limit='2';
        $list=[];
        foreach($navInfo as $val){
            $sql='select '.$field.' from '.$tabpre.$val['link_table'].' where '.$where.' order by '.$order.' limit '.$limit;
            $res=$this->query($sql);
            if($res){
                foreach($res as $v){
                    $v['nav_id']=$val['id'];
                    $v['pid']=$v['id'];
                    $v['language']=$language;
                    unset($v['id']);
                    $list[]=$v;
                }
            }
        }
        $result=$this->addAll($list);
        return $result;
    }
}

