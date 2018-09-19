<?php
namespace Home\Model;
use Think\Model;
class Book_listModel extends Model{
    public $size=15;
    public function getList($lang,$page=0){
        $result=false;
        $model=M();
        $booklist=C('DB_PREFIX').'book_list';
        $booktype=C('DB_PREFIX').'book_type';
        $field='a.tit_name,a.icon_image,a.link_address,a.introduction,b.type_name';
        $order='order by a.id desc';
        $limit=$page*$this->size.','.$this->size;
        $sql='select '.$field.' from '.$booklist.' a join '.$booktype.' b on a.tid=b.id where b.language="'.$lang.'" '.$order.' limit '.$limit;
        $res=$model->query($sql);
        if($res){
            shuffle($res);
            foreach($res as $key=>$val){
                $res[$key]['icon_image']=__ROOT__.'/'.$val['icon_image'];
                $type[]=$val['type_name'];
            }
            $type=array_unique($type);
            foreach($type as $i=>$ty){
                foreach($res as $val){
                    if($type[$i]==$val['type_name']){
                        $result[$type[$i]][]=$val;
                    }
                }
            }
        }
        return $result;
    }
}

