<?php
namespace Api\Controller;
use Think\Controller;
class DefaultController extends Controller{
    private $is_bug=false;
    private $msg_log_file=LOG_PATH.'api_client_msg.txt';
    private $client_log='';
    protected $data=array();
    protected $page='';
    protected $size='';
    public function __construct() {
        parent::__construct();
        $this->data=$_POST;
        $this->inited();
    }
    
    public function inited(){
        if(!empty($this->data)){
            $this->page=(!empty($this->data['page']) and is_numeric($this->data['page']) and $this->data['page']>0) ? $this->data['page']-1 : 0;
            $size=(!empty($this->data['size']) and is_numeric($this->data['size']) and $this->data['size']>0) ? $this->data['size'] : 30;
            $this->size=$size<=30?$size:30;
        }
        if($this->is_bug){
            $data=@file_get_contents("php://input");
            $this->client_log='['.date("Y-m-d H:i:s").'] '.get_client_ip().' '.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].PHP_EOL.'INFO:'.PHP_EOL;
            $this->client_log.='request post all :'.PHP_EOL;
            $this->client_log.=$data.PHP_EOL;
        }
    }
    
    /**
     * 数据转换json输出
     */
    public function checkJson($data,$status='0'){
        $result=array();
        if($status==='0' && !empty($data)){
            $result=$data;
        }
        $res=array('status'=>$status,'result'=>$result);
        //header('Content-Type:application/json; charset=utf-8');
        $jsoncode=json_encode($res);
        echo $jsoncode;
        
        if($this->is_bug){
            $this->client_log.=PHP_EOL.'last out json data :'.PHP_EOL;
            $this->client_log.=$jsoncode.PHP_EOL.PHP_EOL.PHP_EOL;
            $file_log=fopen($this->msg_log_file,'a');
            fwrite($file_log, $this->client_log);
            fclose($file_log);
        }
        exit();
    }
    
    /**
     * 清除log数据
     */
    public function clearLog(){
        file_put_contents($this->msg_log_file,'');
        echo 'log clear ok !';
    }

}