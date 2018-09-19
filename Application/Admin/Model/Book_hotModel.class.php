<?php
namespace Admin\Model;
class Book_hotModel extends DefaultInitModel{
    public function createHot($id){
        $res=false;
        if(!is_array($id)){
            $is_add=$this->field('id')->where('list_id='.$id)->select(); //验证是否已存在
            if(empty($is_add)){
                $res=$this->add(array('list_id'=>$id));
            }else{
                $res=true;
            }
        }
        return $res;
    }
    
    public function delList($id){
        $res=$this->delete((is_array($id)?implode(',',$id):$id));
        return $res;
    }
    
    public function getList($pageNo,$pageSize){
        $tables=$this->tableJoins();
        $limit=$pageNo.','.$pageSize;
        $field='a.id,b.tit_name,b.icon_image,b.link_address,b.come_from,b.create_date,b.introduction,c.type_name,c.language';
        $order='id desc';
        $sql='select '.$field.' from '.$tables.' order by '.$order.' limit '.$limit;
        $res=M()->query($sql);
        if($res){
            foreach($res as $key=>$val){
                if(!empty($val['icon_image'])){
                    if(!startWith($val['icon_image'], 'http')){
                        $res[$key]['icon_image']=__ROOT__.'/'.$val['icon_image'];
                    }
                }else{
                    $res[$key]['icon_image']=__ROOT__.'/Public/images/default.png';
                }
                $res[$key]['languages'] = L($val['language']);
            }
        }
        return $res;
    }
    
    public function count(){
        $tables=$this->tableJoins();
        $sql='select count(a.id) as num from '.$tables;
        $res=M()->query($sql);
        $res=!empty($res) ? $res[0]['num'] : false;
        return $res;
    }
    
    public function tableJoins(){
        $bookhot=C('DB_PREFIX').'book_hot a';
        $booklist=C('DB_PREFIX').'book_list b';
        $booktype=C('DB_PREFIX').'book_type c';
        $table=$bookhot.','.$booklist.','.$booktype.' where a.list_id=b.id and b.tid=c.id and b.is_put=1';
        return $table;
    }
}

