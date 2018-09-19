<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Page;
use Think\Upload;

/**
 * 搞笑管理2017-10-06
 */
class FunnyController extends DefaultInitController {
    public $dir=''; //图片目录
    public function __construct() {
        parent::__construct();
        $formattime=date("Y-m-d");
        $this->dir="Public/Upload/Funny/".$formattime;
    }
    /**
     * 分类列表
     */
    public function Funny_type(){
        $funny = M('funny_type');

        $count      = $funny->count();
        $Page       = new \Think\Page($count,20);
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show       = $Page->show();
        $funny_type = $funny->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($funny_type as $key=>$val){
            $funny_type[$key]['languages'] = L($val['language']);
        }
        $this->assign('page',$show);
        $this->assign('funny_type',$funny_type);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('funny_type');
    }

    /**
     * 更新分类
     */
    public function updateNavfunny(){
        if(IS_POST){
            $data['id'] = I('post.id');
            $data['type_name'] = I('post.name');
            $data['language'] = I('post.language');
            $info = $this->upload('Funny');
            if($info){
                $data['icon'] = 'Public/'.$info['pic']['savepath'].$info['pic']['savename'];
                $img_old_path =  M('funny_type')->field('icon')->where('id='.$data['id'])->find();
                if(file_exists('./'.$img_old_path['icon'])){
                    @unlink('./'.$img_old_path['icon']);
                }
            }
            $res = M('funny_type')->data($data)->save();
            if($res){
                $this->success('更新成功','Funny_type');
            }else{
                $this->error('更新失败');
            }
        }
    }

    /**
     * 删除分类
     */
    public function delFunny_type(){
        if(IS_GET){
            $ids = I('get.check');
        }else{
            $ids = I('post.check');
        }
        if(isset($ids)){
            $funny = M('funny_type');
            $id = '';
            if(is_array($ids)){
                foreach($ids as $val){
                    $id.=$val.',';
                    $path = $funny->field('icon')->where('id='.$val)->find();
                    //删除文件
                    $this->deluploadfile($path['icon']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $id = $ids;
                $path = $funny->field('icon')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['icon']);
            }

            $delete = $funny->delete($id);
            if($delete){
                $this->success('删除成功',U('Funny_type'));
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
    public function addFunny_type(){
        if(IS_POST){
            $data = [];
            $data['type_name'] = I('post.name');
            $data['language'] = I('post.language');
            $info = $this->upload('Funny');
            $data['icon'] = 'Public/'.$info['image']['savepath'].$info['image']['savename'];
            $add = M('funny_type')->data($data)->add();
            if($add){
                $this->success('添加成功',U('Funny_type'));
            }else{
                $this->error('添加失败');
            }
            return false;
        }
    }

    /**
     * 搞笑列表
     */
    public function Funnylist(){
        $funny_list=new \Admin\Model\Funny_listModel();
        $count=$funny_list->count();
        $page=new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show = $page->show();
        $res=$funny_list->getList($page->firstRow, $page->listRows);
        $funny_type=new \Admin\Model\Funny_typeModel();
        $type_res=$funny_type->typeList();
        $this->assign('cate',$type_res);
        $this->assign('lang',$this->languagelisets);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->display('funnylist');
    }

    /**
     * 添加搞笑信息
     */
    public function addFunny_list(){
        $funny_type = M('funny_type');
        $type_res = $funny_type ->field('id,type_name') ->select();
        if(IS_POST){
            $funny_list = M('funny_list');
            $tid = I('post.cate');
            $tit_name = I('post.tit_name');
            $mimtype = I('post.type');
            $txt_content = I('post.introduction');
            $link_address = I('post.link_address');
            
            if($mimtype=='txt'){
                $icon_image='';
            }else{
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
            }

            $data['tid'] = $tid;
            $data['tit_name'] = $tit_name;
            $data['mimtype'] = $mimtype;
            $data['txt_content'] = $txt_content;
            $data['link_address'] = $link_address;
            $data['icon_image'] = $icon_image;
            $data['is_put'] = I('is_put');

            $status = $funny_list ->data($data) ->add();

            if($status){
                $this->success('添加成功',U('Funny/'.($data['is_put']==1?'funnylist':'off_put_list')));
            }else{
                $this->error('添加失败');
            }
            exit;
        }
        $this->assign('cate',$type_res);
        $this->assign('lang',$this->languagelisets);
        $this->display('funnyadd');
    }

    /**
     * 添加搞笑时的语言切换选择分类
     */
    public function langType(){
        if(IS_POST){
            $lang = I('post.language');
            $data['language'] = $lang;
            $ft = M('funny_type');
            $res = $ft ->where($data)->select();
            if($res){
                $this->ajaxReturn($res);
            }else{
                $this->ajaxReturn('');
            }
        }
    }

    /**
     * 删除搞笑信息
     */
    public function delFunny_list(){
        $funny_list = M('funny_list');
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
                    $path = $funny_list->field('icon_image')->where('id='.$idval)->find();
                    //删除文件
                    $this->deluploadfile($path['icon_image']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $arr_id[]=$ids;
                $id = $ids;
                $path = $funny_list->field('icon_image')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['icon_image']);
            }

        }
        $status = $funny_list->delete($id);
        if($status){
            $act=strtolower(I('get.act'));
            if($act=='impotaddlist'){
                $ses=array_diff(session('funnyAddId'), $arr_id);
                if(count($ses)>0){
                    session('funnyAddId',$ses);
                }else{
                    session('funnyAddId',null);
                }
            }
            $this->success('删除成功',U('Funny/'.$act));
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 更新搞笑列表
     */
    public function updateFunny(){
        if(IS_POST){
            $id=I('post.id');
            $name=I('post.name');
            $desc=I('post.desc');
            $cate=I('post.cate');
            $mimtype=I('post.mimtype');
            $link=I('post.link');
            $pic=@$_FILES['pic'];
            
            $data=array(
                'tid'=>$cate,
                'tit_name'=>$name,
                'txt_content'=>$desc,
                'link_address'=>$link
            );
            $funnylistObj=new \Admin\Model\Funny_listModel;
            $images=array();
            $picname=array();
            if($pic){
                $pic_tmp=$pic['tmp_name'];
                $pic_name=$pic['name'];
                foreach($pic_tmp as $key=>$val){
                    if($val){
                        $images[]=$val;
                        $picname[]=$pic_name[$key];
                    }
                }
            }
            if($images){
                $imgs=array();
                $filepath=$this->dir;
                if(!is_dir($filepath)){
                        mkdir($filepath,0755,true);
                }
                for($i=0;$i<count($images);$i++){
                   $filename=date("YmdHis").mt_rand(111111,999999).substr($picname[$i],strrpos($picname[$i],'.'));
                   $pathname=$filepath.'/'.$filename;
                   $save=move_uploaded_file($images[$i], $pathname);
                   if($save){
                       $imgs[]=$pathname;
                   }
                }
                if($imgs){
                    $del_old_img=$funnylistObj->delImages($id);
                    if($del_old_img){
                        $data['icon_image']=implode(',', $imgs);
                    }
                }
            }
            $update=$funnylistObj->update($data,$id);
            if($update){
                $this->success(L('ModifySuccess'),U('Funny/funnylist'));
            }else{
                $this->error(L('ModifyFailure'),U('Funny/funnylist'));
            }
        }
    }

    //csv文件导入数据
    public function csvfile(){
        if(empty($_FILES) || empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['size']<1){
            $this->error('没有文件上传',U('Funny/Funnylist'),3);
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
                        $dir="Public/Upload/Funny/".$formattime;
                        if(!is_dir($dir)){
                            mkdir($dir,0755,true);
                        }
                        if(empty($val[3])){
                            $arrdata[$key][3]=$val[3]='中文简体';
                        }
                        $navi=$langtype->istype_funny($val[3],$val[4]);

                        if(!$navi){
                            $errRow[]=$key;
                            unset($arrdata[$key]);
                        }else{

                            if(empty($val[2]) || empty($val[1]) || empty($val[0])){
                                $errRow[]=$key;
                                unset($arrdata[$key]);
                            }else{

                                if($val[7]=='txt'){
                                    //文本类型跳过图片处理
                                    $imgPath = '';
                                    $imagUrl_err=false;
                                }else{
                                    $imagUrl=explode(';',$val[1]);
                                    $imagUrl_err=true; //记录imagUrl地址是否全部无效。
                                    $imgPath=array();
                                    if(!is_dir($dir)){
                                        mkdir($dir,0755,true);
                                    }
                                    for($i=0;$i<4;$i++){
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
                                }

                                if($imagUrl_err) $errRow[]=$key;
                                if(!empty($imgPath)){
                                    $data=array(
                                        'tid'=>$navi[0]['tid'],
                                        'tit_name'=>$val[0],
                                        'icon_image'=>implode(',', $imgPath),
                                        'link_address'=>$val[2],
                                        'txt_content'=>$val[4],
                                        'praise' => $val[5],
                                        'mimtype' => $val[7],
                                        'is_put'=>'0'
                                    );

                                    $result[]=M('funny_list')->data($data)->add();
                                }
                                //文本类型数据添加
                                if($val[7]=='txt'){
                                    $data=array(
                                        'tid'=>$navi[0]['tid'],
                                        'tit_name'=>$val[0],
                                        'icon_image'=>'',
                                        'link_address'=>$val[2],
                                        'txt_content'=>$val[4],
                                        'praise' => $val[5],
                                        'mimtype' => $val[7],
                                        'is_put'=>'0'
                                    );

                                    $result[]=M('funny_list')->data($data)->add();
                                }

                            }
                        }
                    }

                }
                if(empty($arrdata)) $flag=false;
            }

            if(!$flag){
                $this->error('文件格式有误或空数据文件',U('Funny/Funnylist'),3);
            }else{
                $errRowNum=empty($errRow)? 0 : count($errRow);
                if(!empty($result)){
                    $idArr=array();
                    $idArr['id_count']=count($result);
                    for($i=0;$i<count($result);$i++){
                        $idArr['arr_id'.$i]=$result[$i];
                    }
                    if($errRowNum>0){
                        $this->success('上传数据成功'.count($result).'条,失败'.$errRowNum.'条', U('Funny/ImpotAddList',$idArr), 3);
                    }else{
                        $this->success('全部数据上传成功', U('Funny/ImpotAddList',$idArr), 3);
                    }
                }else{
                    $this->error('上传数据失败',U('Funny/Funnylist'),3);
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
            if(!empty($arr_id)) session('funnyAddId',$arr_id);
        }
        if(empty(session('funnyAddId'))) exit('暂无数据');

        $funny_list=new \Admin\Model\Funny_listModel();
        $count=count(session('funnyAddId'));
        $page=new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show = $page->show();
        $res=$funny_list->getList($page->firstRow, $page->listRows, session('funnyAddId'), '0');
        $funny_type=new \Admin\Model\Funny_typeModel();
        $type_res=$funny_type->typeList();
        $this->assign('cate',$type_res);
        $this->assign('lang',$this->languagelisets);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->display('funnylist');
    }
    
    public function off_put_list(){
        $funny_list=new \Admin\Model\Funny_listModel();
        $is_put='0';
        $count=$funny_list->count($is_put);
        $page=new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show = $page->show();
        $res=$funny_list->getList($page->firstRow, $page->listRows, array(), $is_put);
        $funny_type=new \Admin\Model\Funny_typeModel();
        $type_res=$funny_type->typeList();
        $this->assign('cate',$type_res);
        $this->assign('lang',$this->languagelisets);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->display('funnylist');
    }
    
     //发布
    public function put(){
        $arr_id=I('post.check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $funnyList=new \Admin\Model\Funny_listModel;
        $res=$funnyList->put_on($arr_id,'0');
        if($res){
            $this->success("发布成功",U('Funny/funnylist'));
        }else{
            $this->error("发布失败");
        }
    }
    
    //下架处理
    public function removed(){
        $arr_id=I('check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $funnyObj=new \Admin\Model\Funny_listModel;
        $res=$funnyObj->put_off($arr_id);
        if($res){
            $this->success("处理成功",U('Funny/funnylist'));
        }else{
            $this->error("处理失败");
        }
    }


}