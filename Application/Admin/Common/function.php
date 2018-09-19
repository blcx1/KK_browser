<?php

/**
 * 
 * @Description 获取图片上传对象
 * @parpams		
 * @return 		\Think\Upload
 * @author 		inmyfree
 * @createtime  	2014-9-11 下午1:40:22
 */
function getImgUploadObj(){
	$upload = new \Think\Upload ();
	$upload->maxSize = 0;
	$upload->exts = array (
			'jpg',
			'gif',
			'png',
			'jpeg'
	);
	$upload->rootPath = './';
	$upload->savePath = './Public/Upload/';
	return $upload;
}

/**
 *
 * @Description 获取图片/apk上传对象
 * @parpams
 * @return 		\Think\Upload
 * @author 		inmyfree
 * @createtime  	2014-9-11 下午1:40:22
 */
function getFileUploadObj(){
	$upload = new \Think\Upload ();
	$upload->maxSize = 0;
	$upload->exts = array (
			'jpg',
			'gif',
			'png',
			'jpeg',
			'apk',
			'nif',
			'ttf'
	);
	$upload->rootPath = './';
	$upload->savePath = './Public/Upload/';
	return $upload;
}
function getMusicUploadObj(){
	$upload = new \Think\Upload ();
	$upload->maxSize = 0; // 设置附件上传大小
	$upload->exts = array (
			'jpg',
			'gif',
			'png',
			'jpeg',
			'mp4',
			'mp3',
			'ttf'
	);
	$upload->rootPath = './';
	$upload->savePath = './Public/Upload/';
	return $upload;
}
/**
 * 检查权限
 * @param name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
 * @param uid  int           认证用户的id
 * @param string mode        执行check的模式
 * @param relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
 * @return boolean           通过验证返回true;失败返回false
 */
function authcheck($name, $uid, $type=1, $mode='url', $relation='or'){
	if(!in_array($uid,C('ADMINISTRATOR'))){
		$auth=new \Think\Auth();
		return $auth->check($name, $uid, $type, $mode, $relation)?true:false;
	}else{
		return true;
	}
}

function  is_inArray($id,$array){
	return in_array($id, $array,true);
}


/**
 * 添加操作日志
 * @param unknown $operate_name
 * @param string $operate_id_name
 * @return boolean
 */
function add_operate($operate_name,$operate_id_name = 'id'){

	$operate_log_object = new \Admin\Model\OperationLogModel();
	$check = $operate_log_object->info_add_process($operate_name,$operate_id_name);

	return (bool)$check;
}

/**
 * 删除目录
 * @param unknown $path
 * @return boolean
 */
function delete_dir($path){
	
	$check = false;
	$path = trim($path);
	if(strlen($path) > 0 ){
		
		$dh = opendir($path);
		while($file = readdir($dh)){
			
			if($file != '.' && $file != '..'){
				
				$fullpath = $path.'/'.$file;
				if(!is_dir($file)){
					
					unlink($fullpath);
				}else{
					
					delete_dir($fullpath);
				}
			}			
		}
		closedir($dh);
		if(rmdir($path)){
			
			$check = true;
		}		
	}
	
	return $check;	
}

//清除redis站点资源缓存数据
function clear_redis_cach(){
    require_once './redis.php';
    if($res){
        if($redis->keys('browser_*')){
            $redis->delete($redis->keys('browser_*'));
        }
    }
}

/**
 * 解释csv文件
 * @param file.csv
 * @return Array
 */
function csvIn($csvfile){
    $arr=array();
    if (($handle = fopen($csvfile, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    $num = count($data);
                    $str='';
                    for ($c=0; $c < $num; $c++) {
                            $str.=iconv('gbk','utf-8',$data[$c]).',';
                    }
                    $str=rtrim($str,',');
                    $arr[]=explode(',',$str);
            }
            fclose($handle);
    }
    return $arr;
}

/**
 * 获取网络文件至服务器
 * @param String $httpfile 需要下载的文件url地址
 * @param String $savefile 转存的文件名和路径
 * @return boolean
 */
function wgetSave($httpfile,$savefile){
    $fp=@fopen($httpfile,'rb');
    $ok=false;
    if($fp){
        $ok=true;
        $fsave=fopen($savefile,'wb');
        while(!feof($fp)){
                $buffer=fread($fp,8192);
                $fw=fwrite($fsave,$buffer);
        }
        fclose($fp);
        fclose($fsave);
    }
    return $ok;
}