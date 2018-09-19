<?php
/*用于数据处理共享公用模块*/
namespace Admin\Model;
class DefaultInitModel extends \Think\Model{
    
    //发布数据
    public function put_on($arr_id,$create_date='1'){
        $data = $create_date==='0' ? array('is_put'=>1) : array('is_put'=>1,'create_date'=>date("Y-m-d H:i:s"));
        if(is_array($arr_id)){
            $res=$this->where('id in('.implode(',', $arr_id).')')->save($data);
        }else{
            $res=$this->where('id='.$arr_id)->save($data);
        }
        return $res;
    }
    
    //撤销发布
    public function put_off($arr_id){
        if(is_array($arr_id)){
            $res=$this->where('id in('.implode(',', $arr_id).')')->save(array('is_put'=>0));
        }else{
            $res=$this->where('id='.$arr_id)->save(array('is_put'=>0));
        }
        return $res;
    }
}

