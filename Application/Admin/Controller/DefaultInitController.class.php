<?php
/*
 * 登陆权限集合处理控制器
 */
namespace Admin\Controller;
use andreskrey\Readability\HTMLParser;
use andreskrey\Readability\Configuration;
class DefaultInitController extends \Think\Controller{
    public $nowlang = LANG_SET;
    public $languagelisets = [];
    public function __construct() {
        parent::__construct();
        if (!session ( 'userid' )) {
                $this->display ( "Index/index" );
                exit;
        }
        //权限验证
        $name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
        $where['name'] = array("eq","$name");
        $auth_ruleModel = M("Auth_rule");
        if($auth_ruleModel->where($where)->find()){
            if(!authcheck($name,$_SESSION['userid'])){
                $this->error(L("NoAuth"));//没有权限
            }
        }

        $lang_object = M('language');
        $this->languagelisets = $lang_object -> select();

    }

    /**
     * 文件上传
     */
    public function upload($cpath=''){
        if(isset($cpath)){
            $cpath = $cpath.'/';
        }else{
            $cpath='';
        }
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png','jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/'; // 设置附件上传根目录
        $upload->savePath  =     'Upload/'.$cpath; // 设置附件上传（子）目录
        // 上传文件
        return $info   =   $upload->upload();
        /*if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $this->success('上传成功！');
        }*/
    }

    /**
     * 删除上传的文件
     */
    public function deluploadfile($path){
        if(!empty($path)){
            $path = explode(',',$path);
            if(is_array($path) && count($path)){
                foreach($path as $val){
                    if(file_exists('./'.$val)){
                        @unlink('./'.$val);
                    }
                }
            }else{
                if(is_string($path) && stristr($path,'Public')){
                    if(file_exists('./'.$path)){
                        @unlink('./'.$path);
                    }
                }
            }
        }
    }
    
    /**
    * 下载文件
    */
    public function doloadfile(){
        $http=new \Org\Net\Http;
        $filename='./Public/doc/'.I('get.name');
        $http::download($filename);
    }
    
    //获取网页页面图片
    public function getUrlImg($htmlurl=''){
        $url=$htmlurl?$htmlurl:I("post.url");
        import('phpQuery','./ThinkPHP/Library/phpQuery/phpQuery','.php');
        \phpQuery::newDocumentFile($url);
        $length=pq("img")->length();
        $arr=array();
        for($i=0;$i<$length;$i++){
            $arr[]=pq("img:eq($i)")->attr('src');
        }
        if(!empty($arr)){
            echo json_encode($arr);
        }
    }
    
    //获取网页页面各类信息
    public function getInfomation(){
        $htmlurl=I('post.url');
        if(empty($htmlurl)){
            exit;
        }
        if(!startWith(strtolower($htmlurl),'http')) $htmlurl='http://'.$htmlurl;
        import('autoload','./ThinkPHP/Library/Readability','.php');
        $html = @file_get_contents($htmlurl);
        try {
            $readability = new \andreskrey\Readability\Readability(new Configuration());
            if(!$html) E('404');
            if(!$readability->parse($html)) E('404');
            $readall=$readability;
            if(!$readall) E('404');
            //$images=$readability->getImages();
        } catch (\Exception $e) {
            $msg=$e->getMessage();
            if($msg=='404'){
                exit;
            }
        }

        $imgleng=preg_match_all('/<img.*?src="(.*?)".*?>/is',$readall,$img);
        $imgurl=array();
        if(!empty($imgleng)){
            foreach($img[1] as $val){
                $imgurl[]= startWith($val, '//') ? 'http:'.$val : $val;
            }
        }
        if(count($imgurl)>1) unset($imgurl[count($imgurl)-1]);

        //获取title
        $title=$readability->getTitle()?$readability->getTitle():'';
        //获取描述
        $description=$readability->getExcerpt()?$readability->getExcerpt():'';
        $result=array('title'=>$title,'imgurl'=>$imgurl,'description'=>$description);
        echo json_encode($result);
    }
    
    //资讯来源固定值
    public function commit(){
        $arr=array('北青网','新浪网','腾讯网','青年网','北京时间','大公网','凤凰网','人民日报','央视网','一点资讯','CNN','REUTERS',
            'Facebook','MSN','Amazon','Alice','Aol','AsiaOne','Canada','NBA','Voanews');
        echo json_encode($arr);
    }
    
    public function _empty(){
        echo '404';
    }
}

