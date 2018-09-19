<?php
namespace Admin\Model;
class Funny_listModel extends DefaultInitModel{
    
    public function getList($pageNo,$pageSize,$arrId=array(),$is_put='1'){
        $table=$this->tables();
        $where='a.is_put='.$is_put.(empty($arrId)?null:' and a.id in ('.implode(',',$arrId).')');
        $field='a.id,a.tit_name,a.icon_image,a.link_address,a.txt_content,a.mimtype,b.type_name,b.language';
        $limit=$pageNo.','.$pageSize;
        $sql='select '.$field.' from '.$table.' where '.$where.' order by id desc limit '.$limit;
        $res=$this->query($sql);
        if($res){
            foreach($res as $key=>$val){
                if(!empty($val['icon_image'])){
                    $res[$key]['icon_image']= explode(',', $val['icon_image']);
                    foreach($res[$key]['icon_image'] as $k=>$v){
                        if(!startWith($v, 'http')){
                            $res[$key]['icon_image'][$k]=__ROOT__.'/'.$v;
                        }
                    }
                }else{
                    $res[$key]['icon_image'][]=__ROOT__.'/Public/images/default.png';
                }
                $res[$key]['languages'] = L($val['language']);
            }
        }
        return $res;
    }
    
    public function count($is_put='1'){
        $table=$this->tables();
        $where='a.is_put='.$is_put;
        $field='count(a.id) as num';
        $sql='select '.$field.' from '.$table.' where '.$where;
        $res=$this->query($sql);
        $res=!empty($res) ? $res[0]['num'] : false;
        return $res;
    }
    
    public function tables(){
        $funnylist=C('DB_PREFIX').'funny_list a';
        $funnytype=C('DB_PREFIX').'funny_type b';
        $table=$funnylist.' join '.$funnytype.' on a.tid=b.id';
        return $table;
    }
    
    //删除图片
    public function delImages($id){
        $images=$this->field('icon_image')->where('id='.$id)->find();
        $result=true;
        if($images){
            $images=explode(',', $images['icon_image']);
            $result=array();
            $i=0;
            foreach($images as $val){
                $i++;
                if(!file_exists($val)){
                    $result[]=$i;
                }else{
                    if(unlink($val)) $result[]=$i;
                }
            }
        }
        $res=$result?true:false;
        return $res;
    }
    
    //更新
    public function update($data,$id){
        $res=$this->where('id='.$id)->save($data);
        return $res;
    }
}