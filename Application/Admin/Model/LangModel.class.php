<?php
/**
 *顶级类别模型
 *date 2016-03-21
 *autor:kiven pcttcnc2007@126.com
 */

namespace Admin\Model;
use Think\Model;
class LangModel extends \Admin\Model\DefaultModel{
		
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
	 * 语言处理
	 * @param unknown $param_array
	 * @param unknown $where
	 * @return boolean
	 */
	public function info_lang_process($param_array,$where){	

		$check = false;
		if(check_array_valid($param_array)){
			
			$result = $this->where($where)->count();
			if(intval($result) > 0){
				
				$result = $this->info_update($where,$param_array);				
			}else{
				
				$result = $this->info_add($param_array);
			}
			$check = $result === false ? false : true;
		}
		return $check;
	}
}
?>