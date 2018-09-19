<?php

namespace Admin\Controller;
use Think\Page;
use Think\Upload;
/**
 * 视频管理2017-10-09
 */
class VideoController extends DefaultInitController {
    
    public $dir=''; //图片目录
    public function __construct() {
        parent::__construct();
        $formattime=date("Y-m-d");
        $this->dir="Public/Upload/Video/".$formattime;
    }
    /**
     * 视频列表
     */
    public function videoList(){
        $videoObj = new \Admin\Model\Video_listModel;
        $count = $videoObj->count();
        $page       = new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show       = $page->show();
        $res=$videoObj->getList($page->firstRow, $page->listRows);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('videoList');
    }

    /**
     * 添加视频信息
     */
    public function addVideo(){
        if(IS_POST){
            $video_list = M('video_list');
            $tit_name = I('post.tit_name');
            $link_address = I('post.link_address');
            $come_from = I('post.come_from');
            $lang = I('post.language');

            if(empty($tit_name)) $this->error('名称不能为空');
            if(empty($link_address)) $this->error('跳转链接不能为空');
            if(empty($_FILES) and empty($_POST['imageURL'])) $this->error('没有图片信息');
            $imgUrl=I('post.imageURL');
            $filePath=array();
            $dir=$this->dir;
            if(!is_dir($dir)){
                mkdir($dir,0755,true);
            }
            if($imgUrl){
                set_time_limit(120);
                foreach($_POST['imageURL'] as $v){
                    $fileCode=file_get_contents($v);
                    if($fileCode){
                        $filename=date("YmdHis").strval(rand(111111,999999)).substr($v,strrpos($v,'.'));
                        $pathname=$dir.'/'.$filename;
                        if(startWith($v, '//')) $v='http:'.$v;
                        $res=file_put_contents($pathname,$fileCode);
                        if($res) $filePath[]=$pathname;
                    }
                }
            }
            if(!empty($_FILES)){
                foreach($_FILES['image']['name'] as $val){
                    $file_su[]=substr($val,strrpos($val,'.'));
                }
                foreach($_FILES['image']['tmp_name'] as $k=>$val){
                    $filename=date("YmdHis").strval(rand(111111,999999)).$file_su[$k];
                    $pathname=$dir.'/'.$filename;
                    $res=move_uploaded_file($val,$pathname);
                    if($res){
                        $filePath[]=$pathname;
                    }
                }
            }
            if(empty($filePath)) $this->error('图片获取错误');
            $icon_image= implode(',', $filePath);

            $data['tit_name'] = $tit_name;
            $data['link_address'] = $link_address;
            $data['icon_image'] = $icon_image;
            $data['come_from'] = $come_from;
            $data['create_date'] = date('Y-m-d H:i:s');
            $data['language'] = $lang;
            $data['time'] = '';
            $data['is_put'] = I('post.is_put');


            $status = $video_list ->data($data) ->add();

            if($status){
                $this->success('添加成功',U('Video/'.($data['is_put']==1?'videolist':'off_put_list')));
            }else{
                $this->error('添加失败');
            }
            exit;
        }
        $this->assign('lang',$this->languagelisets);
        $this->display('videoadd');

    }

    /**
     * 删除视频信息
     */
    public function delVideo(){
        $video_list = M('Video_list');
        if(IS_GET){
            $ids = I('get.check');
        }else{
            $ids = I('post.check');
        }
        if(empty($ids)){
            $this->error('还没有勾选');
        }else{
            $id = '';
            $arr_id=array();
            if(is_array($ids)){
                $arr_id=$ids;
                foreach($ids as $idval){
                    $id.=$idval.',';
                    $path = $video_list->field('icon_image')->where('id='.$idval)->find();
                    //删除文件
                    $this->deluploadfile($path['icon_image']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $arr_id[]=$ids;
                $id = $ids;
                $path = $video_list->field('icon_image')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['icon_image']);
            }

        }
        $status = $video_list->delete($id);
        if($status){
            $act=strtolower(I('get.act'));
            if($act=='impotaddlist'){
                $ses=array_diff(session('videoAddId'), $arr_id);
                if(count($ses)>0){
                    session('videoAddId',$ses);
                }else{
                    session('videoAddId',null);
                }
            }
            $this->success('删除成功',U('Video/'.$act));
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 更新视频
     */
    public function updateVideo(){
        if(IS_POST){

            $video_object = M('video_list');
            $data=[];
            $data['id'] = I('post.id');
            $data['tit_name'] = I('post.name');
            $data['language'] = I('post.language');
            $data['link_address'] = I('post.link');
            $data['come_from'] = I('post.from');

            $info = $this->upload('Video');
            //dump($info);
            //exit;
            $img1 = $info['pic']['savepath'].$info['pic']['savename'];

            $images = $video_object->field('icon_image')->where('id='.$data['id'])->select();
            $images = $images[0]['icon_image'];

            if($img1!=''){
                @unlink('./'.$images);
                $images = 'Public/'.$img1;
            }

            $data['icon_image'] = $images;

            if( $video_object->data($data)->save()){
                $this->success('更新成功');
            }else{
                $this->error('更新失败');
            }
            return false;
        }
        exit;
    }

    //csv文件导入数据
    public function csvfile(){
        if(empty($_FILES) || empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['size']<1){
            $this->error('没有文件上传',U('Video/videoList'),3);
        }else{
            $flag=true; //文件格式有误或者是空数据
            $result=array(); //返回添加是否成功结果
            $errRow=array();//记录文件错误行
            $csvfile=$_FILES['csvfile']['tmp_name'];
            $arrdata=csvIn($csvfile);
            unset($arrdata[0]);
            if(empty($arrdata)){
                $flag=false;
            }else{
                set_time_limit(120);
                $langtype=new \Admin\Model\LanguageModel;
                foreach($arrdata as $key=>$val){
                    if(count($val)<6){
                        $errRow[]=$key;
                        unset($arrdata[$key]);
                    }else{
                        $formattime=date("Y-m-d");
                        $dir="Public/Upload/Video/".$formattime;
                        if(!is_dir($dir)){
                            mkdir($dir,0755,true);
                        }
                        if(empty($val[3])){
                            $arrdata[$key][3]=$val[3]='中文简体';
                        }
                        $navi=$langtype->istype($val[3]);
                        if(!$navi){
                            $errRow[]=$key;
                            unset($arrdata[$key]);
                        }else{

                            if(empty($val[2]) || empty($val[1]) || empty($val[0])){
                                $errRow[]=$key;
                                unset($arrdata[$key]);
                            }else{

                                $imagUrl=explode(';',$val[1]);
                                $imagUrl_err=true; //记录imagUrl地址是否全部无效。
                                $imgPath=array();
                                if(!is_dir($dir)){
                                    mkdir($dir,0755,true);
                                }
                                for($i=0;$i<1;$i++){
                                    if(startWith($imagUrl[$i],'//')){
                                        $imagUrl[$i]='http:'.$imagUrl[$i];
                                    }
                                    $filename=date("YmdHis").strval(rand(111111,999999)).substr($imagUrl[$i],strrpos($imagUrl[$i],'.'));
                                    $pathname=$dir.'/'.$filename;
                                    $fileSave=wgetSave($imagUrl[$i],$pathname);
                                    if($fileSave){
                                        $imgPath[]=$pathname;
                                        $imagUrl_err=false;
                                    }
                                }

                                if($imagUrl_err) $errRow[]=$key;
                                if(!empty($imgPath)){
                                    $data=array(
                                        'tit_name'=>$val[0],
                                        'icon_image'=>implode(',', $imgPath),
                                        'link_address'=>$val[2],
                                        'time'=>$val[5],
                                        'come_from'=>$val[4],
                                        'language'=>$navi[0]['iso_code'],
                                        'create_date'=>date('Y-m-d H:i:s'),
                                        'is_put'=>'0'
                                    );

                                    $result[]=M('video_list')->data($data)->add();
                                }

                            }
                        }
                    }

                }
                if(empty($arrdata)) $flag=false;
            }

            if(!$flag){
                $this->error('文件格式有误或空数据文件',U('Video/videoList'),3);
            }else{
                $errRowNum=empty($errRow)? 0 : count($errRow);
                if(!empty($result)){
                    $idArr=array();
                    $idArr['id_count']=count($result);
                    for($i=0;$i<count($result);$i++){
                        $idArr['arr_id'.$i]=$result[$i];
                    }
                    if($errRowNum>0){
                        $this->success('上传数据成功'.count($result).'条,失败'.$errRowNum.'条', U('Video/ImpotAddList',$idArr), 3);
                    }else{
                        $this->success('全部数据上传成功', U('Video/ImpotAddList',$idArr), 3);
                    }
                }else{
                    $this->error('上传数据失败',U('Video/videoList'),3);
                }
            }
        }
    }

    /**
     * 批量添加成功后进入的列表页面
     */
    public function ImpotAddList(){
        if(!empty(I('arr_id0'))){
            $arr_id=array();
            $id_count=I('id_count');
            for($i=0;$i<$id_count;$i++){
                $arr_id[]=I('arr_id'.$i);
            }
            if(!empty($arr_id)) session('videoAddId',$arr_id);
        }
        if(empty(session('videoAddId'))) exit('暂无数据');

        $videoObj = new \Admin\Model\Video_listModel;
        $is_put='0';
        $count = count(session('videoAddId'));
        $page       = new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show       = $page->show();
        $res=$videoObj->getList($page->firstRow, $page->listRows, session('videoAddId'), $is_put);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('videoList');
    }
    
    /**
     * 未发布列表
     */
    public function off_put_list(){
        $videoObj = new \Admin\Model\Video_listModel;
        $is_put='0';
        $count = $videoObj->count($is_put);
        $page       = new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show       = $page->show();
        $res=$videoObj->getList($page->firstRow, $page->listRows, array(), $is_put);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('videoList');
    }
    
    //发布
    public function put(){
        $arr_id=I('post.check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $videoList=new \Admin\Model\Video_listModel;
        $res=$videoList->put_on($arr_id);
        if($res){
            $this->success("发布成功",U('Video/videolist'));
        }else{
            $this->error("发布失败");
        }
    }
    
    //下架处理
    public function removed(){
        $arr_id=I('check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $videoList=new \Admin\Model\Video_listModel;
        $res=$videoList->put_off($arr_id);
        if($res){
            $this->success("处理成功",U('Video/videolist'));
        }else{
            $this->error("处理失败");
        }
    }

}