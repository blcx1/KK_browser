<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Page;
use Think\Upload;

/**
 * 小说管理2017-10-09
 */
class BookController extends DefaultInitController {
    public $dir=''; //图片目录
    public function __construct() {
        parent::__construct();
        $formattime=date("Y-m-d");
        $this->dir="Public/Upload/Book/".$formattime;
    }
    /**
     * 分类列表
     */
    public function book_type(){
        $book = M('book_type');

        $count      = $book->count();
        $Page       = new \Think\Page($count,20);
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show       = $Page->show();
        $book_type = $book->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($book_type as $key=>$val){
            $book_type[$key]['languages'] = L($val['language']);
        }
        $this->assign('page',$show);
        $this->assign('book_type',$book_type);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('book_type');
    }

    /**
     * 删除分类
     */
    public function delBook_type(){
        if(IS_GET){
            $ids = I('get.check');
        }else{
            $ids = I('post.check');
        }
        if(isset($ids)){
            $book = M('book_type');
            $id = '';
            if(is_array($ids)){
                foreach($ids as $val){
                    $id.=$val.',';
                }
                $id = mb_substr($id,0,-1);
            }else{
                $id = $ids;
            }
            $delete = $book->delete($id);
            if($delete){
                $this->success('删除成功',U('book_type'));
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('还没有勾选');
        }
    }

    /**
     * 获取分类中指定语言的分类
     */
    public function getlnagcate(){
        if(IS_POST){
            $where = [];
            $lang = I('post.language');
            if(empty($lang)){
                $lang = 'zh-cn';
            }
            $where['language'] = $lang;
            $bt = M('book_type');
            $res = $bt->field('id,type_name')->where($where)->select();
            echo json_encode($res);
        }
    }

    /**
     * 更新分类
     */
    public function updateNavbook(){
        if(IS_POST){
            $data['id'] = I('post.id');
            $data['type_name'] = I('post.name');
            $data['language'] = I('post.language');
            $res = M('book_type')->data($data)->save();
            if($res){
                $this->success('更新成功','book_type');
            }else{
                $this->error('更新失败');
            }
        }
    }

    /**
     * 添加分类
     */
    public function addBook_type(){
        if(IS_POST){
            $data = [];
            $data['type_name'] = I('post.name');
            $data['language'] = I('post.language');
            $add = M('book_type')->data($data)->add();
            if($add){
                $this->success('添加成功',U('book_type'));
            }else{
                $this->error('添加失败');
            }
            return false;
        }
    }

    /**
     * 小说列表
     */
    public function book_list(){
        $book_list=new \Admin\Model\Book_listModel;
        $is_put='1';
        $pageSize=18;
        $count=$book_list->count($is_put);
        $page=new Page($count,$pageSize);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show=$page->show();
        $res=$book_list->getList($page->firstRow, $page->listRows);
        $book_type=new \Admin\Model\Book_typeModel;
        $book_res = $book_type ->getTypeAll();
        $this->assign('cate',$book_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('book_list');
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
            if(!empty($arr_id)) session('bookAddId',$arr_id);
        }
        if(empty(session('bookAddId'))) exit('暂无数据');
        
        $book_list=new \Admin\Model\Book_listModel;
        $is_put='0';
        $pageSize=18;
        $count=count(session('bookAddId'));
        $page=new Page($count,$pageSize);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show=$page->show();
        $res=$book_list->getList($page->firstRow, $page->listRows, session('bookAddId'), $is_put);
        $book_type=new \Admin\Model\Book_typeModel;
        $book_res = $book_type ->getTypeAll();
        $this->assign('cate',$book_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('book_list');
    }


    /**
     * 添加小说信息
     */
    public function addBook_list(){
        $book_type = M('book_type');
        $book_res = $book_type ->field('id,type_name')->select();
        if(IS_POST){
            $book_list = M('book_list');
            $tid = I('post.cate');
            $tit_name = I('post.tit_name');
            $introduction = I('post.introduction');
            $link_address = I('post.link_address');
            $come_from = I('post.come_from');
            $lang = I('post.language');
            //$getimg=I('post.imageURL');
            if(empty($tit_name)) $this->error('名称不能为空');
            if(empty($introduction)) $this->error('短描述不能为空');
            if(empty($link_address)) $this->error('链接地址不能为空');
            
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
            $data['tit_name'] = $tit_name;
            $data['introduction'] = $introduction;
            $data['link_address'] = $link_address;
            $data['icon_image'] = $icon_image;
            $data['come_from'] = $come_from;
            $data['create_date'] = date('Y-m-d H:i:s');
            $data['language'] = $lang;
            $data['read_count'] = 0;
            $data['is_done'] = 0;
            $data['is_put']=I('post.is_put');

            $status = $book_list ->data($data) ->add();

            if($status){
                $this->success('添加成功',U('Book/'.($data['is_put']==1 ? 'book_list' : 'off_put_list')));
            }else{
                $this->error('添加失败');
            }
            exit;
        }
        $this->assign('cate',$book_res);
        $this->assign('lang',$this->languagelisets);
        $this->display('book_add');

    }

    /**
     * 删除小说信息
     */
    public function delBook_list(){
        $book_list = M('book_list');
        if(IS_GET){
            $ids = I('get.check');
        }else{
            $ids = I('post.check');
        }
        if(empty($ids)){
            $this->error('还没有勾选');
        }else{
            $id = '';
            if(is_array($ids)){
                $arr_id=$ids;
                foreach($ids as $idval){
                    $id.=$idval.',';
                    $path = $book_list->field('icon_image')->where('id='.$idval)->find();
                    //删除文件
                    $this->deluploadfile($path['icon_image']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $arr_id[]=$ids;
                $id = $ids;
                $path = $book_list->field('icon_image')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['icon_image']);
            }
        }
        $status = $book_list->delete($id);
        if($status){
            $act=strtolower(I('get.act'));
            if($act=='impotaddlist'){
                $ses=array_diff(session('bookAddId'), $arr_id);
                if(count($ses)>0){
                    session('bookAddId',$ses);
                }else{
                    session('bookAddId',null);
                }
            }
            $this->success('删除成功',U('Book/'.$act));
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 更新小说
     */
    public function updateBook(){
        if(IS_POST){
            $data=[];
            $data['id'] =I('post.id');
            $data['tit_name'] =I('post.name');
            $data['introduction'] =I('post.desc');
            $data['tid'] = I('post.cate');
            $data['language'] = I('post.language');
            $data['link_address'] = I('post.link');
            $data['come_from'] = I('post.from');
            //dump($data);
            //exit;
            $info = $this->upload('Book');
            if($info){
                $img1 = $info['pic']['savepath'].$info['pic']['savename'];

                $images = M('book_list')->field('icon_image')->where('id='.$data['id'])->select();
                $images = $images[0]['icon_image'];

                if($img1!=''){
                    @unlink('./'.$images);
                    $images = 'Public/'.$img1;
                }
                $data['icon_image'] = $images;
            }
            $book = M('book_list')->data($data)->save();
            if($book){
                $this->success('更新成功');
            }else{
                $this->error('更新失败');
            }
        }
    }

    //csv文件导入数据
    public function csvfile(){
        if(empty($_FILES) || empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['size']<1){
            $this->error('没有文件上传',U('Book/book_list'),3);
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
                    if(count($val)<4){
                        $errRow[]=$key;
                        unset($arrdata[$key]);
                    }else{
                        $dir=$this->dir;
                        if(!is_dir($dir)){
                            mkdir($dir,0755,true);
                        }
                        if(empty($val[4])){
                            $arrdata[$key][4]=$val[4]='中文简体';
                        }
                        if(empty($val[5])){
                            if($val[4]=='中文简体'){
                                $arrdata[$key][5]=$val[5]='都市';
                            }else if($val[4]=='中文繁體'){
                                $arrdata[$key][5]=$val[5]='都市';
                            }else{
                                $arrdata[$key][5]=$val[5]='Urban';
                            }
                        }
                        if(empty($val[6]) || !is_numeric($val[6])){
                            $arrdata[$key][6]=$val[6]='0';
                        }else if(!is_integer($val[6])){
                            $arrdata[$key][6]=$val[6]=floor($val[6]);
                        }
                        if(empty($val[7])){
                            $arrdata[$key][7]='暂无描述';
                            $val[7]='暂无描述';
                        }
                        $navi=$langtype->istype_book($val[4],$val[5]);

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
                                        'tid'=>$navi[0]['tid'],
                                        'tit_name'=>$val[0],
                                        'icon_image'=>implode(',', $imgPath),
                                        'link_address'=>$val[2],
                                        'come_from'=>$val[3],
                                        'language'=>$navi[0]['iso_code'],
                                        'create_date'=>date("Y-m-d H:i:s"),
                                        'introduction' => $val[7],
                                        'is_put'=>0
                                    );
                                    $result[]=M('book_list')->data($data)->add();
                                }
                            }
                        }
                    }
                }
                if(empty($arrdata)) $flag=false;
            }
            if(!$flag){
                $this->error('文件格式有误或空数据文件',U('Book/book_list'),3);
            }else{
                $errRowNum=empty($errRow)? 0 : count($errRow);
                if(!empty($result)){
                    $idArr=array();
                    $idArr['id_count']=count($result);
                    for($i=0;$i<count($result);$i++){
                        $idArr['arr_id'.$i]=$result[$i];
                    }
                    if($errRowNum>0){
                        $this->success('上传数据成功'.count($result).'条,失败'.$errRowNum.'条', U('Book/ImpotAddList',$idArr), 3);
                    }else{
                        $this->success('全部数据上传成功', U('Book/ImpotAddList',$idArr), 3);
                    }
                }else{
                    $this->error('上传数据失败',U('Book/book_list'),3);
                }
            }
        }
    }
    
    public function off_put_list(){
        $book_list=new \Admin\Model\Book_listModel;
        $is_put='0';
        $pageSize=18;
        $count=$book_list->count($is_put);
        $page=new Page($count,$pageSize);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show=$page->show();
        $res=$book_list->getList($page->firstRow, $page->listRows, array(), $is_put);
        $book_type=new \Admin\Model\Book_typeModel;
        $book_res = $book_type ->getTypeAll();
        $this->assign('cate',$book_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('book_list');
    }
    
    //添加热门（最受追捧）
    public function addhot(){
        $id=I('check');
        if(empty($id)) $this->error('没有选择数据');
        $book_hot=new \Admin\Model\Book_hotModel;
        $res=$book_hot->createHot($id);
        if($res){
            $this->success('添加至热门栏成功');
        }else{
            $this->error('添加失败');
        }
    }
    
    //删除热门
    public function delHot_list(){
        $id=I('check');
        if(empty($id)) $this->error('没有选择数据');
        $book_hot=new \Admin\Model\Book_hotModel;
        $res=$book_hot->delList($id);
        if($res){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    
    //热门(最受追捧)
    public function hot(){
        $book_hot=new \Admin\Model\Book_hotModel;
        $pageSize=18;
        $count=$book_hot->count();
        $page=new Page($count,$pageSize);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show=$page->show();
        $res=$book_hot->getList($page->firstRow, $page->listRows);
        $this->assign('res',$res);
        $this->assign('page',$show);
        $this->assign('count',$count);
        $this->display('hot');
    }
    
    //发布
    public function put(){
        $arr_id=I('post.check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $bookList=new \Admin\Model\Book_listModel;
        $res=$bookList->put_on($arr_id);
        if($res){
            $this->success("发布成功",U('Book/book_list'));
        }else{
            $this->error("发布失败");
        }
    }
    
    //下架处理
    public function removed(){
        $arr_id=I('check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $bookObj=new \Admin\Model\Book_listModel;
        $res=$bookObj->put_off($arr_id);
        if($res){
            $this->success("处理成功",U('Book/book_list'));
        }else{
            $this->error("处理失败");
        }
    }

}