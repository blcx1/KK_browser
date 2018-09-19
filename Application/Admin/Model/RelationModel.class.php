<?php
/**
 *关联模型
 *date 2016-03-04
 *autor:kiven pcttcnc2007@126.com
 */

namespace Admin\Model;
use Think\Model;
class RelationModel extends \Admin\Model\DefaultModel{
	
	protected $relation_name = '';
	protected $another_name = ''; 
	
	/**
	 * (non-PHPdoc)
	 * @see \Admin\Model\DefaultModel::info_add_process()
	 */
	public function info_add_process(){
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Admin\Model\DefaultModel::info_update_process()
	 */
	public function info_update_process($id,$check_exists = false){			
	}
	
	/**
	 * 获取对应关联列表
	 * @param unknown $relation_id
	 * @return multitype:
	 */
	public function get_relation_list($relation_id){
		
		$relation_list = array();
		$relation_id = intval($relation_id);
		if($relation_id > 0){
			
			$another_name = $this->another_name;
			$relation_name = $this->relation_name;
			$where = $relation_name.' = '.$relation_id;
			$result = $this->field($another_name)->where($where)->select();
			if(check_array_valid($result)){
				
				foreach($result as $value){
					
					$relation_list[] = $value[$another_name];
				}
			}
		}
		return $relation_list;
	}
	
	/**
	 * 关联处理
	 * @param unknown $param_array
	 * @param unknown $relation_id
	 * @return boolean
	 */
	public function info_relation_process($param_array,$relation_id){	

		$check = false;
		$relation_id = intval($relation_id);
		if($relation_id > 0 && check_array_valid($param_array)){
			
			$relation_name = $this->relation_name;			
			$where = $relation_name.' = '.$relation_id;
			$result = $this->where($where)->delete();
			if($result !== false){
				
				$another_name = $this->another_name;
				$info_array_tmp = array();
				$info_array_tmp[$relation_name] = $relation_id;
				foreach($param_array as $value){
					
					$another_id = intval($value);
					if($another_id <= 0){
						
						continue;
					}
					$info_array = $info_array_tmp;
					$info_array[$another_name] = $another_id;
					$result = $this->data($info_array)->add();
					if($result === false){
						
						break;
					}
				}
			}
			
			$check = $result === false ? false : true;
		}
		return $check;
	}
}
?>