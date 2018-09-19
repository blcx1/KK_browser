<?php
namespace Api\Model;
class Book_listModel extends DefaultModel{
    public function getList($page,$size){
        $webNavi=M('web_navi');
        /**广告部分************************************/
       $navid=$webNavi->field('id')->where(array('language'=>LANG_SET,'link_table'=>'book_list'))->find()['id'];
       $advObj=new \Api\Model\AdvertisementModel;
       $advList=$advObj->getList($navid,$page);
       $advpro=array();
       if($advList){
           $size=$size-count($advList);
           foreach($advList as $key=>$val){
               $advpro[$key]['tit_name']=$val['tit_name'];
               $advpro[$key]['icon_image']=$val['icon_image'][0];
               $advpro[$key]['link_address']=$val['link_address'];
               $advpro[$key]['introduction']=L("Advertisement");
               $advpro[$key]['type_name']=L("Advertisement");
           }
       }
       /**************************************/
        
        $result=false;
        $model=M();
        $booklist=C('DB_PREFIX').'book_list';
        $booktype=C('DB_PREFIX').'book_type';
        $field='a.tit_name,a.icon_image,a.link_address,a.introduction,b.type_name';
        $order='order by a.id desc';
        $limit=$page*$size.','.$size;
        $sql='select '.$field.' from '.$booklist.' a join '.$booktype.' b on a.tid=b.id where a.is_put=1 and b.language="'.LANG_SET.'" '.$order.' limit '.$limit;
        $res=$model->query($sql);
        $res=$res?$res:array();
        if($res or $advpro){
            if($res){
                foreach($res as $key=>$val){
                    if(C('TEST_JSON')){
                        $res[$key]['tit_name']=urlencode($val['tit_name']);
                        $res[$key]['link_address']= urlencode($val['link_address']);
                        $res[$key]['introduction']= urlencode($val['introduction']);
                    }
                    $res[$key]['icon_image']=$this->imgUrlPre.'/'.$val['icon_image'];
                }
            }
            if($advpro and $res){
                foreach($advpro as $key=>$v){
                    $advpro[$key]['type_name']=$res[array_rand($res)]['type_name'];
                }
            }
            $res=array_merge($res,$advpro);
            foreach($res as $e){
                $type[]=$e['type_name'];
            }
            shuffle($res);
            $type=array_unique($type);
            foreach($type as $i=>$ty){
                foreach($res as $val){
                    if($type[$i]==$val['type_name']){
                        $result[$type[$i]][]=$val;
                    }
                }
            }
            $result1=array();
            foreach($result as $val){
                $result1[]=$val;
            }
            $result=$result1;
        }
        return $result;
    }
}

