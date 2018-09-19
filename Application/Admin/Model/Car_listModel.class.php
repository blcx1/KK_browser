<?php
namespace Admin\Model;
class Car_listModel extends DefaultInitModel{
    public function getList($pageNo,$pageSize,$arrId=array(),$is_put='1'){
        $field='id,tit_name,icon_image,link_address,come_from,language,create_date,is_top';
        $where='is_put='.$is_put.(empty($arrId)?null:' and id in ('.implode(',',$arrId).')');
        $res=$this->field($field)->where($where)->order('create_date desc')->limit($pageNo.','.$pageSize)->select();
        if($res){
            foreach($res as $key=>$val){
                if(!empty($val['icon_image'])){
                    $res[$key]['icon_image']=explode(',',$val['icon_image']);
                    foreach($res[$key]['icon_image'] as $k=>$v){
                        $res[$key]['icon_image'][$k]=__ROOT__.'/'.$v;
                    }
                }
                $res[$key]['is_top'] = $val['is_top']==1 ? 'yes' : 'no';
                $res[$key]['language']=L($val['language']);
                $res[$key]['languages']=$val['language'];
            }
        }
        return $res;
    }
    
    public function count($is_put='1'){
        $sql='select count(id) as num from '.C('DB_PREFIX').'car_list where is_put='.$is_put;
        $res=$this->query($sql);
        $res=!empty($res) ? $res[0]['num'] : 0;
        return $res;
    }
}