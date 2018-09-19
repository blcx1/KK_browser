<?php
namespace Api\Model;
class Book_hotModel extends DefaultModel{
    public function getList($page,$size){
        $result=false;
        $model=M();
        $bookhot=C('DB_PREFIX').'book_hot a';
        $booklist=C('DB_PREFIX').'book_list b';
        $booktype=C('DB_PREFIX').'book_type c';
        $onjoin='a.list_id=b.id and b.tid=c.id where b.is_put=1 and c.language="'.LANG_SET.'"';
        $order=' order by a.id desc';
        $field='b.tit_name,b.icon_image,b.link_address,b.introduction,c.type_name';
        $limit='limit '.$page*$size.','.$size;
        $sql='select '.$field.' from '.$bookhot.' join '.$booklist.' join '.$booktype.' on '.$onjoin.$order.' '.$limit;
        $res=$model->query($sql);
        if($res){
            shuffle($res);
            foreach($res as $k=>$v){
                $res[$k]['icon_image']=$this->imgUrlPre.'/'.$v['icon_image'];
            }
        }
        return $res;
    }

}

