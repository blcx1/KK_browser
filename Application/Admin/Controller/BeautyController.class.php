<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Page;
use Think\Upload;

/**
 * 美女管理2017-10-06
 */
class BeautyController extends DefaultInitController {
    public $dir=''; //图片目录
    public function __construct() {
        parent::__construct();
        $formattime=date("Y-m-d");
        $this->dir="Public/Upload/Beauty/".$formattime;
    }
    /**
     * 分类列表
     */
    public function beautytype(){
        $beauty = M('beauty_type');

        $count      = $beauty->count();
        $Page       = new \Think\Page($count,20);
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show       = $Page->show();
        $beauty_type = $beauty->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($beauty_type as $key=>$val){
            $beauty_type[$key]['languages'] = L($val['language']);
        }
        $this->assign('page',$show);
        $this->assign('beauty_type',$beauty_type);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('beautyType');
    }

    /**
     * 删除分类
     */
    public function delBeauty_type(){
        if(IS_GET){
            $ids = I('get.check');
        }else{
            $ids = I('post.check');
        }
        if(isset($ids)){
            $beauty = M('beauty_type');
            $id = '';
            if(is_array($ids)){
                foreach($ids as $val){
                    $id.=$val.',';
                    $path = $beauty->field('icon_image')->where('id='.$val)->find();
                    //删除文件
                    $this->deluploadfile($path['icon_image']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $id = $ids;
                $path = $beauty->field('icon_image')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['icon_image']);
            }
            $delete = $beauty->delete($id);
            if($delete){
                $this->success('删除成功',U('beautytype'));
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('还没有勾选');
        }
    }

    /**
     * 添加分类
     */
    public function addBeauty_type(){
        if(IS_POST){
            $data = [];
            $data['type_name'] = I('post.name');
            $data['language'] = I('post.language');
            $info = $this->upload('Beauty');
            $data['icon_image'] = 'Public/'.$info['image']['savepath'].$info['image']['savename'];
            $add = M('beauty_type')->data($data)->add();
            if($add){
                $this->success('添加成功',U('beautyType'));
            }else{
                $this->error('添加失败');
            }
            return false;
        }
    }

    /**
     * 美女列表
     */
    public function beautylist(){
        $beauty_list=new \Admin\Model\Beauty_listModel;
        $count=$beauty_list->count();
        $page=new \Think\Page($count,20);
        $page->setConfig("prev", L("PreviousPage"));
        $page->setConfig("next", L("NextPage"));
        $show=$page->show();
        $res=$beauty_list->getList($page->firstRow,$page->listRows);
        $beauty_type=new \Admin\Model\Beauty_typeModel;
        $type_res=$beauty_type->getNameList();
        $this->assign('cate',$type_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('lang',$this->languagelisets);
        $this->display('beautyList');
    }


    /**
     * 添加美女
     */
    public function addBeauty(){
        $beauty_type = M('beauty_type');
        $type_res = $beauty_type ->field('id,type_name') ->select();
        if(IS_POST){
            $beauty_list = M('beauty_list');
            $tid = I('post.cate');
            $link_address = I('post.link_address');

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

            $data['tid'] = $tid;
            $data['link_address'] = $link_address;
            $data['icon_image'] = $icon_image;
            $data['is_put'] = I('post.is_put');

            $status = $beauty_list->data($data)->add();

            if($status){
                $this->success('添加成功',U('Beauty/'.($data['is_put']==1?'beautylist':'off_put_list')));
            }else{
                $this->error('添加失败');
            }
            exit;
        }
        $this->assign('lang',$this->languagelisets);
        $this->assign('cate',$type_res);
        $this->display('beautyadd');

    }

    /**
     * 添加美女时的语言切换选择分类
     */
    public function langType(){
        if(IS_POST){
            $lang = I('post.language');
            $data['language'] = $lang;
            $ft = M('beauty_type');
            $res = $ft ->where($data)->select();
            if($res){
                $this->ajaxReturn($res);
            }else{
                $this->ajaxReturn('');
            }
        }
    }

    /**
     * 删除美女
     */
    public function delBeauty(){
        $beauty_list = M('beauty_list');
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
                foreach($ids as $idval){
                    $arr_id=$ids;
                    $id.=$idval.',';
                    $path = $beauty_list->field('icon_image')->where('id='.$idval)->find();
                    //删除文件
                    $this->deluploadfile($path['icon_image']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $id = $ids;
                $arr_id[]=$ids;
                $path = $beauty_list->field('icon_image')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['icon_image']);
            }
        }
        $status = $beauty_list->delete($id);
        if($status){
            $act=strtolower(I('get.act'));
            if($act=='impotaddlist'){
                $ses=array_diff(session('beautyAddId'), $arr_id);
                if(count($ses)>0){
                    session('beautyAddId',$ses);
                }else{
                    session('beautyAddId',null);
                }
            }
            $this->success('删除成功',U('Beauty/'.$act));
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 更新分类
     */
    public function updateBeautyType(){
        if(IS_POST){

            $beautytype_object = M('beauty_type');
            $data=[];
            $data['id'] = I('post.id');
            $data['type_name'] = I('post.name');
            $data['language'] = I('post.language');

            $info = $this->upload('Beauty');
            //dump($info);
            //exit;
            $img1 = $info['pic']['savepath'].$info['pic']['savename'];

            $images = $beautytype_object->field('icon_image')->where('id='.$data['id'])->select();
            $images = $images[0]['icon_image'];

            if($img1!=''){
                @unlink('./'.$images);
                $images = 'Public/'.$img1;
            }

            $data['icon_image'] = $images;

            if($beautytype_object->data($data)->save()){
                $this->success('更新成功','beautyType');
            }else{
                $this->error('更新失败');
            }
            return false;
        }
        exit;
    }

    /**
     * 更新分类
     */
    public function updateBeautyList(){
        if(IS_POST){

            $beautylist_object = M('beauty_list');
            $data=[];
            $data['id'] = I('post.id');
            $data['tid'] = I('post.cate');
            $data['link_address'] = I('post.link');

            $info = $this->upload('Beauty');
            //dump($info);
            //exit;
            $img1 = $info['pic']['savepath'].$info['pic']['savename'];

            $images = $beautylist_object->field('icon_image')->where('id='.$data['id'])->select();
            $images = $images[0]['icon_image'];

            if($img1!=''){
                if(file_exists('./'.$images)){
                    @unlink('./'.$images);
                }
                $images = 'Public/'.$img1;
            }

            $data['icon_image'] = $images;

            if($beautylist_object->data($data)->save()){
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
            $this->error('没有文件上传',U('Beauty/beautylist'),3);
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
                $beauty_type=new \Admin\Model\Beauty_typeModel;
                foreach($arrdata as $key=>$val){
                    if(count($val)<4){
                        $errRow[]=$key;
                        unset($arrdata[$key]);
                    }else{
                        $formattime=date("Y-m-d");
                        $dir="Public/Upload/Beauty/".$formattime;
                        if(!is_dir($dir)){
                            mkdir($dir,0755,true);
                        }
                        if(empty($val[0])){
                            $arrdata[$key][0]=$val[0]='中文简体';
                        }
                        if(empty($val[1])){
                            $arrdata[$key][1]=$val[1]='女神';
                        }
                        $navi=$beauty_type->is_type($val[0], $val[1]);
                        if(!$navi){
                            $errRow[]=$key;
                            unset($arrdata[$key]);
                        }else{
                            if(empty($val[2]) || empty($val[3])){
                                $errRow[]=$key;
                                unset($arrdata[$key]);
                            }else{
                                $imagUrl=$val[2];
                                if(!is_dir($dir)){
                                    mkdir($dir,0755,true);
                                }
                                if(startWith($imagUrl,'//')){
                                    $imagUrl='http:'.$imagUrl;
                                }
                                $filename=date("YmdHis").strval(rand(111111,999999)).substr($imagUrl,strrpos($imagUrl,'.'));
                                $pathname=$dir.'/'.$filename;
                                $fileSave=wgetSave($imagUrl,$pathname);
                                if($fileSave){
                                    $data=array(
                                        'tid'=>$navi[0]['id'],
                                        'icon_image'=>$pathname,
                                        'link_address'=>$val[3],
                                        'is_put'=>'0'
                                    );
                                    $result[]=M('beauty_list')->data($data)->add();
                                }else{
                                    $errRow[]=$key;
                                    unset($arrdata[$key]);
                                }
                            }
                        }
                    }

                }
                if(empty($arrdata)) $flag=false;
            }

            if(!$flag){
                $this->error('文件格式有误或空数据文件',U('Beauty/beautylist'),3);
            }else{
                $errRowNum=empty($errRow)? 0 : count($errRow);
                if(!empty($result)){
                    $idArr=array();
                    $idArr['id_count']=count($result);
                    for($i=0;$i<count($result);$i++){
                        $idArr['arr_id'.$i]=$result[$i];
                    }
                    if($errRowNum>0){
                        $this->success('上传数据成功'.count($result).'条,失败'.$errRowNum.'条', U('Beauty/ImpotAddList',$idArr), 3);
                    }else{
                        $this->success('全部数据上传成功', U('Beauty/ImpotAddList',$idArr), 3);
                    }
                }else{
                    $this->error('上传数据失败',U('Beauty/beautylist'),3);
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
            if(!empty($arr_id)) session('beautyAddId',$arr_id);
        }
        if(empty(session('beautyAddId'))) exit('暂无数据');
        
        $beauty_list=new \Admin\Model\Beauty_listModel;
        $is_put='0';
        $count=count(session('beautyAddId'));
        $page=new \Think\Page($count,20);
        $page->setConfig("prev", L("PreviousPage"));
        $page->setConfig("next", L("NextPage"));
        $show=$page->show();
        $res=$beauty_list->getList($page->firstRow,$page->listRows,session('beautyAddId'),$is_put);
        $beauty_type=new \Admin\Model\Beauty_typeModel;
        $type_res=$beauty_type->getNameList();
        $this->assign('cate',$type_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('lang',$this->languagelisets);
        $this->display('beautyList');
    }
    
    /**
     * 未发布列表
     */
    public function off_put_list(){
        $beauty_list=new \Admin\Model\Beauty_listModel;
        $is_put='0';
        $count=$beauty_list->count($is_put);
        $page=new \Think\Page($count,20);
        $page->setConfig("prev", L("PreviousPage"));
        $page->setConfig("next", L("NextPage"));
        $show=$page->show();
        $res=$beauty_list->getList($page->firstRow,$page->listRows,array(),$is_put);
        $beauty_type=new \Admin\Model\Beauty_typeModel;
        $type_res=$beauty_type->getNameList();
        $this->assign('cate',$type_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('lang',$this->languagelisets);
        $this->display('beautyList');
    }
    
    //发布
    public function put(){
        $arr_id=I('post.check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $beautyList=new \Admin\Model\Beauty_listModel;
        $res=$beautyList->put_on($arr_id,'0');
        if($res){
            $this->success("发布成功",U('Beauty/beautylist'));
        }else{
            $this->error("发布失败");
        }
    }
    
    //下架处理
    public function removed(){
        $arr_id=I('check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $beautyList=new \Admin\Model\Beauty_listModel;
        $res=$beautyList->put_off($arr_id);
        if($res){
            $this->success("处理成功",U('beauty/beautylist'));
        }else{
            $this->error("处理失败");
        }
    }

}