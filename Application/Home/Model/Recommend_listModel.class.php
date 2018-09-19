<?php

namespace Home\Model;
use \Think\Model;
class Recommend_listModel extends Model{
    public $size=10;
    public $zhNavName=array("新闻","视频","娱乐"); //推荐内容中文版
    public $usNavName=array("News","Video","Entertainment"); //推荐内容英文版
    public $modeList=array(); //导航模块指定导航id:(news_list=>2)
    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct($name, $tablePrefix, $connection);
        
        //格式化导航名字便于数据库查询字符
        if(LANG_SET=='zh-cn'){
            $zhnavname="";
               for($i=0;$i<count($this->zhNavName);$i++){
                   $zhnavname.='"'.$this->zhNavName[$i].'",';
               }
            $this->zhNavName= rtrim($zhnavname,',');
        }else if(LANG_SET=='en-us'){
            $usnavname="";
               for($i=0;$i<count($this->usNavName);$i++){
                   $usnavname.='"'.$this->usNavName[$i].'",';
               }
            $this->usNavName= rtrim($usnavname,',');
        }
    }
            
    //获取推荐列表页
    public function getList($lang,$page=0){
       $webNavi=M('web_navi');
       if($lang=='zh-cn'){
           $navInfo=$webNavi->field('id,link_table')->where('name in('.$this->zhNavName.')')->select();
       }else if($lang=='en-us'){
           $navInfo=$webNavi->field('id,link_table')->where('name in('.$this->usNavName.')')->select();
       }
       if($navInfo){
            foreach($navInfo as $val){
                $this->modeList[$val['link_table']]=$val['id'];
            }
        }
       
       $page=$page*$this->size;
       $language='language="'.$lang.'"';
       $list=$this->where($language)->order('id desc')->limit($page,$this->size)->select();
       $newsIdArr=array();//新闻id
       $videoIdArr=array();//视频id
       $entIdArr=array(); //娱乐id
       
       foreach($list as $val){
           if($val['nav_id']==$this->modeList['news_list']){
               $newsIdArr[]=$val['pid'];
           }else if($val['nav_id']==$this->modeList['video_list']){
               $videoIdArr[]=$val['pid'];
           }else if($val['nav_id']==$this->modeList['entertainment_list']){
               $entIdArr[]=$val['pid'];
           }
       }

       $field='tit_name,icon_image as images,link_address,come_from,create_date';
       $newsList=M('news_list');
       $newsInfo=$newsList->field($field)->where('id in ('.implode(',',$newsIdArr).')')->select();
       $videoList=M('video_list');
       $videoInfo=$videoList->field($field)->where('id in ('.implode(',',$videoIdArr).')')->select();
       $entList=M('entertainment_list');
       $entInfo=$entList->field($field)->where('id in ('.implode(',',$entIdArr).')')->select();
       if(empty($newsInfo)) $newsInfo=array();
       if(empty($videoInfo)) $videoInfo=array();
       if(empty($entInfo)) $entInfo=array();
       $listInfo=array_merge($newsInfo,$videoInfo,$entInfo);
       foreach($listInfo as $k=>$val){
           $listInfo[$k]['images']=explode(',',$val['images']);
           $listInfo[$k]['create_date']=timeformat($val['create_date']);
       }
       shuffle($listInfo);
       return $listInfo;
       
    }
}

