<?php

namespace Admin\Controller;
use Think\Page;
use Think\Upload;
/**
 * 娱乐管理2017-10-09
 */
class EntertainmentController extends DefaultInitController {
    public $imgPre=__ROOT__.'/'; //资源前缀
    public $dir=''; //图片目录
    public function __construct() {
        parent::__construct();
        $formattime=date("Y-m-d");
        $this->dir="Public/Upload/Entertainment/".$formattime;
    }
    /**
     * 娱乐列表
     */
    public function entertainment_list(){
        $entertainment_list = M('entertainment_list');
        $count      = $entertainment_list->where('is_put=1')->count();
        $Page       = new \Think\Page($count,20);
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show       = $Page->show();
        $res = $entertainment_list->where('is_put=1')->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
        if($res){
            foreach($res as $key=>$val){
                if($val['is_top']==1){
                    $res[$key]['is_top']='yes';
                }else{
                    $res[$key]['is_top']='no';
                }
                $res[$key]['icon_image']=explode(',', $val['icon_image']);
                if(!empty($res[$key]['icon_image'])){
                    foreach($res[$key]['icon_image'] as $k=>$v){
                        if(stristr($v,'http') || stristr($v,'https')){
                            $res[$key]['icon_image'][$k]=$v;
                        }else{
                            $res[$key]['icon_image'][$k]=$this->imgPre.$v;
                        }
                    }
                }
                $res[$key]['language']=L($val['language']);
                $res[$key]['languages'] = $val['language'];
            }
            
        }
        $this->assign('page',$show);
        $this->assign('lang',$this->languagelisets);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->display('entertainment_list');
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
            if(!empty($arr_id)) session('entAddId',$arr_id);
        }
        if(empty(session('entAddId'))) exit('暂无数据');
        
        $entertainment_list = M('entertainment_list');
        $count      = count(session('entAddId'));
        $Page       = new \Think\Page($count,20);
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show       = $Page->show();
        $res = $entertainment_list->where('id in ('.implode(',', session('entAddId')).')')->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
        if($res){
            foreach($res as $key=>$val){
                if($val['is_top']==1){
                    $res[$key]['is_top']='yes';
                }else{
                    $res[$key]['is_top']='no';
                }
                $res[$key]['icon_image']=explode(',', $val['icon_image']);
                if(!empty($res[$key]['icon_image'])){
                    foreach($res[$key]['icon_image'] as $k=>$v){
                        if(stristr($v,'http') || stristr($v,'https')){
                            $res[$key]['icon_image'][$k]=$v;
                        }else{
                            $res[$key]['icon_image'][$k]=$this->imgPre.$v;
                        }
                    }
                }
                $res[$key]['language']=L($val['language']);
                $res[$key]['languages'] = $val['language'];
            }
            
        }
        $this->assign('page',$show);
        $this->assign('lang',$this->languagelisets);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->display('entertainment_list');
    }
    
    //未发布的新闻
    public function off_put_list(){
        $entertainment_list = M('entertainment_list');
        $count      = $entertainment_list->where('is_put=0')->count();
        $Page       = new \Think\Page($count,20);
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show       = $Page->show();
        $res = $entertainment_list->where('is_put=0')->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
        if($res){
            foreach($res as $key=>$val){
                if($val['is_top']==1){
                    $res[$key]['is_top']='yes';
                }else{
                    $res[$key]['is_top']='no';
                }
                $res[$key]['icon_image']=explode(',', $val['icon_image']);
                if(!empty($res[$key]['icon_image'])){
                    foreach($res[$key]['icon_image'] as $k=>$v){
                        if(stristr($v,'http') || stristr($v,'https')){
                            $res[$key]['icon_image'][$k]=$v;
                        }else{
                            $res[$key]['icon_image'][$k]=$this->imgPre.$v;
                        }
                    }
                }
                $res[$key]['language']=L($val['language']);
                $res[$key]['languages'] = $val['language'];
            }
            
        }
        $this->assign('page',$show);
        $this->assign('lang',$this->languagelisets);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->display('entertainment_list');
    }

    /**
     * 添加娱乐信息
     */
    public function addEntertainment_list(){
        if(IS_POST){
            $entertainment_list = M('entertainment_list');
            $tit_name = I('post.tit_name');
            $link_address = I('post.link_address');
            $come_from = I('post.come_from');
            $lang = I('post.language');
            $istop=I('post.istop');
            $is_put=I('is_put');
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
            $data['is_top'] = $istop;
            $data['is_put'] = $is_put;

            $status = $entertainment_list ->data($data) ->add();

            if($status){
                if($is_put=='1'){
                    $this->success('添加成功',U('Entertainment/entertainment_list'));
                }else{
                    $this->success('添加成功',U('Entertainment/off_put_list'));
                }
            }else{
                $this->error('添加失败');
            }
            exit;
        }
        $this->assign('lang',$this->languagelisets);
        $this->display('entertainmentadd');
    }

    //更新娱乐
    public function updateEmter(){
        if(IS_POST){
            $entertainment_object = M('entertainment_list');
            $data=[];
            $data['id'] = I('post.id');
            $data['tit_name'] = I('post.name');
            $data['language'] = I('post.language');
            $istop = I('post.cate');
            if($istop=='yes'){
                $istop=1;
            }else{
                $istop=0;
            }
            $data['is_top'] = $istop;
            $data['link_address'] = I('post.link');
            $data['come_from'] = I('post.from');
            $info = $this->upload('Entertainment');
            //dump($info);
            $img1 = $info['pic1']['savepath'].$info['pic1']['savename'];
            $img2 = $info['pic2']['savepath'].$info['pic2']['savename'];
            $img3 = $info['pic3']['savepath'].$info['pic3']['savename'];

            $images = $entertainment_object->field('icon_image')->where('id='.$data['id'])->select();
            $images = $images[0]['icon_image'];
            $images = explode(',',$images);

            if($img1!=''){
                @unlink('./'.$images[0]);
                $images[0] = 'Public/'.$img1;
            }
            if($img2!=''){
                @unlink('./'.$images[1]);
                $images[1] = 'Public/'.$img2;
            }
            if($img3!=''){
                @unlink('./'.$images[2]);
                $images[2] = 'Public/'.$img3;
            }

            $images = implode(',',$images);
            $data['icon_image'] = $images;

            if($entertainment_object->data($data)->save()){
                $this->success('更新成功');
            }else{
                $this->error('更新失败');
            }
            return false;
        }
        exit;
    }

    /**
     * 删除娱乐信息
     */
    public function delEntertainmentList(){
        $book_list = M('entertainment_list');
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
                    $path = $book_list->field('icon_image')->where('id='.$idval)->find();
                    //删除文件
                    $this->deluploadfile($path['icon_image']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $arr_id[]=$ids;
                $id = $ids;
                $path = $book_list->field('icon_image')->where('id='.$id)->find();
                $this->deluploadfile($path['icon_image']);
            }
        }
        $status = $book_list->delete($id);
        if($status){
            $act=strtolower(I('get.act'));
            if($act=='impotaddlist'){
                $ses=array_diff(session('entAddId'), $arr_id);
                if(count($ses)>0){
                    session('entAddId',$ses);
                }else{
                    session('entAddId',null);
                }
            }
            $this->success('删除成功',U('Entertainment/'.$act));
        }else{
            $this->error('删除失败');
        }
    }

    //csv文件导入数据
    public function csvfile(){
        if(empty($_FILES) || empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['size']<1){
            $this->error('没有文件上传',U('Entertainment/addEntertainment_list'),3);
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
                            $arrdata[$key][4]='中文简体';
                            $val[4]='中文简体';
                        }
                        if(empty($val[5]) or strtolower($val[5])!='yes'){
                            $arrdata[$key][5]='0';
                            $val[5]='0';
                        }else{
                            $arrdata[$key][5]='1';
                            $val[5]='1';
                        }
                        $navi=$langtype->istype($val[4]);
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
                                for($i=0;$i<count($imagUrl);$i++){
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
                                        'come_from'=>$val[3],
                                        'language'=>$navi[0]['iso_code'],
                                        'create_date'=>date("Y-m-d H:i:s"),
                                        'is_top' => $val[5],
                                        'is_put'=> 0
                                    );

                                    $result[]=M('entertainment_list')->data($data)->add();
                                }
                            }
                        }
                    }

                }
                if(empty($arrdata)) $flag=false;
            }
            if(!$flag){
                $this->error('文件格式有误或空数据文件',U('Entertainment/addEntertainment_list'),3);
            }else{
                $errRowNum=empty($errRow)? 0 : count($errRow);
                if(!empty($result)){
                    $idArr=array();
                    $idArr['id_count']=count($result);
                    for($i=0;$i<count($result);$i++){
                        $idArr['arr_id'.$i]=$result[$i];
                    }
                    if($errRowNum>0){
                        $this->success('上传数据成功'.count($result).'条,失败'.$errRowNum.'条', U('Entertainment/ImpotAddList',$idArr), 3);
                    }else{
                        $this->success('全部数据上传成功', U('Entertainment/ImpotAddList',$idArr), 3);
                    }
                }else{
                    $this->error('上传数据失败',U('Entertainment/entertainment_list'),3);
                }
            }
        }
    }
    
    //发布
    public function put(){
        $arr_id=I('post.check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $entObj=new \Admin\Model\Entertainment_listModel;
        $res=$entObj->put_on($arr_id);
        if($res){
            $this->success("发布成功",U('Entertainment/entertainment_list'));
        }else{
            $this->error("发布失败");
        }
    }
    
    //下架处理
    public function removed(){
        $arr_id=I('check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $entObj=new \Admin\Model\Entertainment_listModel;
        $res=$entObj->put_off($arr_id);
        if($res){
            $this->success("处理成功",U('Entertainment/entertainment_list'));
        }else{
            $this->error("处理失败");
        }
    }
}