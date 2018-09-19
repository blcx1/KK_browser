<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Page;
use Think\Upload;

/**
 * 好物管理2017-10-10
 */
class ShopController extends DefaultInitController {
    public $dir=''; //图片目录
    public function __construct() {
        parent::__construct();
        $formattime=date("Y-m-d");
        $this->dir="Public/Upload/Shop/".$formattime;
    }
    /**
     * 分类列表
     */
    public function shop_type(){
        $shop = M('shop_type');

        $count      = $shop->count();
        $Page       = new \Think\Page($count,20);
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show       = $Page->show();
        $shop_type = $shop->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($shop_type as $key=>$val){
            $shop_type[$key]['languages'] = L($val['language']);
        }
        $this->assign('page',$show);
        $this->assign('game_type',$shop_type);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('shop_type');
    }

    /**
     * 删除分类
     */
    public function delShop_type(){
        if(IS_GET){
            $ids = I('get.check');
        }else{
            $ids = I('post.check');
        }

        if(isset($ids)){
            $shop = M('shop_type');
            $id = '';
            if(is_array($ids)){
                foreach($ids as $val){
                    $id.=$val.',';
                }
                $id = mb_substr($id,0,-1);
            }else{
                $id = $ids;
            }

            $delete = $shop->delete($id);
            if($delete){
                $this->success('删除成功',U('shop_type'));
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
    public function addshop_type(){
        if(IS_POST){
            $data = [];
            $data['type_name'] = I('post.name');
            $data['language'] = I('post.language');
            $add = M('shop_type')->data($data)->add();
            if($add){
                $this->success('添加成功',U('shop_type'));
            }else{
                $this->error('添加失败');
            }
            return false;
        }
    }

    /**
     * 更新分类
     */
    public function updateNavShop(){
        if(IS_POST){
            $data = [];
            $data['id'] = I('post.id');
            $data['type_name'] = I('post.name');
            $data['language'] = I('post.language');
            $s = M('shop_type')->data($data)->save();
            if($s){
                $this->success('更新成功',U('shop_type'));
            }else{
                $this->error('更新失败');
            }
            return false;
        }
    }

    /**
     * 好物列表
     */
    public function shopList(){
        $shop_list=new \Admin\Model\Shop_listModel;
        $count=$shop_list->count();
        $page=new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show=$page->show();
        $res=$shop_list->getList($page->firstRow,$page->listRows);
        $shop_type=new \Admin\Model\Shop_typeModel;
        $type_res=$shop_type->getTypeAll();
        $this->assign('cate',$type_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('lang',$this->languagelisets);
        $this->display('shoplist');
    }


    /**
     * 添加好物
     */
    public function addShop_list(){
        $shop_type = M('shop_type');
        $type_res = $shop_type ->field('id,type_name') ->select();
        if(IS_POST){
            $shop_list = M('shop_list');
            $tid = I('post.cate');
            $tit_name = I('post.tit_name');
            $link_address = I('post.link_address');
            $pric = I('post.pric');
            $num = I('post.num');

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
            

            $data['type_id'] = $tid;
            $data['shop_name'] = $tit_name;
            $data['link_address'] = $link_address;
            $data['shop_image'] = $icon_image;
            $data['shop_price'] = $pric;
            $data['out_count'] = $num;
            $data['is_put']=I('post.is_put');
            
            $status = $shop_list->data($data)->add();

            if($status){
                $this->success('添加成功',U('Shop/'.($data['is_put']==1?'shoplist':'off_put_list')));
            }else{
                $this->error('添加失败');
            }
            exit;
        }
        $this->assign('lang',$this->languagelisets);
        $this->assign('cate',$type_res);
        $this->display('shoplistadd');

    }

    /**
     * 添加好物时的语言切换选择分类
     */
    public function langType(){
        if(IS_POST){
            $lang = I('post.language');
            $data['language'] = $lang;
            $ft = M('shop_type');
            $res = $ft ->where($data)->select();
            if($res){
                $this->ajaxReturn($res);
            }else{
                $this->ajaxReturn('');
            }
        }
    }

    /**
     * 更新好物
     */
    public function updateShop(){
       if(IS_POST){
           $data=[];
           $data['id'] = I('post.id');
           $data['type_id'] = I('post.cate');
           $data['shop_name'] = I('post.name');
           $data['link_address'] = I('post.link');
           $data['shop_price'] = I('post.pric');
           $data['out_count'] = I('post.num');
           $data['recom'] = I('post.recom');

           $info = $this->upload('Shop');

           $img1 = $info['pic']['savepath'].$info['pic']['savename'];

           $images = M('shop_list')->field('shop_image')->where('id='.$data['id'])->select();
           $images = $images[0]['shop_image'];

           if($img1!=''){
               @unlink('./'.$images);
               $images = 'Public/'.$img1;
           }

           $data['shop_image'] = $images;

           $save = M('shop_list')->data($data)->save();
           if($save){
               $this->success('更新成功');
           }else{
               $this->error('更新失败');
           }
           return false;
       }
    }

    /**
     * 删除好物
     */
    public function delShop_list(){
        $shop_list = M('shop_list');
        if(IS_GET){
            $ids = I('get.check');
        }else{
            $ids = I('post.check');
        }

        if(empty($ids)){
            $this->error('还没有勾选');
        }else{
            $id = '';
            $arr_id = array();
            if(is_array($ids)){
                $arr_id=$ids;
                foreach($ids as $idval){
                    $id.=$idval.',';
                    $path = $shop_list->field('shop_image')->where('id='.$idval)->find();
                    //删除文件
                    $this->deluploadfile($path['shop_image']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $arr_id[]=$ids;
                $id = $ids;
                $path = $shop_list->field('shop_image')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['shop_image']);
            }

        }
        $status = $shop_list->delete($id);
        if($status){
            $act=strtolower(I('get.act'));
            if($act=='impotaddlist'){
                $ses=array_diff(session('shopAddId'), $arr_id);
                if(count($ses)>0){
                    session('shopAddId',$ses);
                }else{
                    session('shopAddId',null);
                }
            }
            $this->success('删除成功',U('Shop/'.$act));
        }else{
            $this->error('删除失败');
        }
    }

    //csv文件导入数据
    public function csvfile(){
        if(empty($_FILES) || empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['size']<1){
            $this->error('没有文件上传',U('Shop/shopList'),3);
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
                $langtype=new \Admin\Model\Shop_typeModel;
                foreach($arrdata as $key=>$val){
                    if(count($val)<4){
                        $errRow[]=$key;
                        unset($arrdata[$key]);
                    }else{
                        $formattime=date("Y-m-d");
                        $dir="Public/Upload/Shop/".$formattime;
                        if(!is_dir($dir)){
                            mkdir($dir,0755,true);
                        }
                        if(empty($val[4])){
                            $arrdata[$key][4]=$val[4]="中文简体";
                        }
                        if(empty($val[5])){
                            $arrdata[$key][5]=$val[5]="女装精品";
                        }
                        
                        $navi=$langtype->is_type($val[4],$val[5]);
                        if(!$navi){
                            $errRow[]=$key;
                            unset($arrdata[$key]);
                        }else{

                            if(empty($val[2]) || empty($val[1]) || empty($val[0] || empty($val[3]))){
                                $errRow[]=$key;
                                unset($arrdata[$key]);
                            }else{

                                $imagUrl=$val[1];
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
                                        'type_id'=>$navi[0]['id'],
                                        'shop_image'=>$pathname,
                                        'shop_name'=>$val[0],
                                        'shop_price'=>is_numeric($val[3])?$val[3]:'0',
                                        'out_count'=>(empty($val[6]) or !is_numeric($val[6])) ? '0': $val[6],
                                        'link_address'=>$val[2],
                                        'recom'=>(empty($val[7]) or !is_numeric($val[7])) ? '0' : $val[7],
                                        'is_put'=>'0'
                                    );
                                    $result[]=M('shop_list')->data($data)->add();
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
                $this->error('文件格式有误或空数据文件',U('Shop/shopList'),3);
            }else{
                $errRowNum=empty($errRow)? 0 : count($errRow);
                if(!empty($result)){
                    $idArr=array();
                    $idArr['id_count']=count($result);
                    for($i=0;$i<count($result);$i++){
                        $idArr['arr_id'.$i]=$result[$i];
                    }
                    if($errRowNum>0){
                        $this->success('上传数据成功'.count($result).'条,失败'.$errRowNum.'条', U('Shop/ImpotAddList',$idArr), 3);
                    }else{
                        $this->success('全部数据上传成功', U('Shop/ImpotAddList',$idArr), 3);
                    }
                }else{
                    $this->error('上传数据失败',U('Shop/shopList'),3);
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
            if(!empty($arr_id)) session('shopAddId',$arr_id);
        }
        if(empty(session('shopAddId'))) exit('暂无数据');

        $shop_list=new \Admin\Model\Shop_listModel;
        $is_put='0';
        $count=count(session('shopAddId'));
        $page=new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show=$page->show();
        $res=$shop_list->getList($page->firstRow,$page->listRows,session('shopAddId'),$is_put);
        $shop_type=new \Admin\Model\Shop_typeModel;
        $type_res=$shop_type->getTypeAll();
        $this->assign('cate',$type_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('lang',$this->languagelisets);
        $this->display('shoplist');
    }
    
    public function off_put_list(){
        $shop_list=new \Admin\Model\Shop_listModel;
        $is_put='0';
        $count=$shop_list->count($is_put);
        $page=new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show=$page->show();
        $res=$shop_list->getList($page->firstRow,$page->listRows,array(),$is_put);
        $shop_type=new \Admin\Model\Shop_typeModel;
        $type_res=$shop_type->getTypeAll();
        $this->assign('cate',$type_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('lang',$this->languagelisets);
        $this->display('shoplist');
    }

     //发布
    public function put(){
        $arr_id=I('post.check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $shopList=new \Admin\Model\Shop_listModel;
        $res=$shopList->put_on($arr_id,'0');
        if($res){
            $this->success("发布成功",U('Shop/shoplist'));
        }else{
            $this->error("发布失败");
        }
    }
    
    //下架处理
    public function removed(){
        $arr_id=I('check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $shopObj=new \Admin\Model\Shop_listModel;
        $res=$shopObj->put_off($arr_id);
        if($res){
            $this->success("处理成功",U('Shop/shoplist'));
        }else{
            $this->error("处理失败");
        }
    }
}