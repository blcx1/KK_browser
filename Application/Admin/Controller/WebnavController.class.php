<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Page;
use Think\Upload;

/**
 * 网页导航条管理2017-10-14
 */
class WebnavController extends DefaultInitController {

        public function showList(){
            $webNavi=new \Admin\Model\Web_naviModel();
            $co = $webNavi->counts();
            $Page       = new \Think\Page($co,20);
            $Page->setConfig( "prev", L("PreviousPage"));//上一页
            $Page->setConfig( "next", L("NextPage"));//下一页

            $list=$webNavi->listAll($Page->firstRow,$Page->listRows);
            foreach($list as $key=>$val){
                $list[$key]['languages'] = L($val['language']);
            }
            $show       = $Page->show();
            $this->assign("count", $co);
            $this->assign("list",$list);
            $this->assign('page',$show);
            $this->display('showList');
        }
        
        /**
         * 删除web导航条目
         */
        public function deleteEntByID(){
            $id=I('post.check');
            $webNavi=new \Admin\Model\Web_naviModel();
            $res=$webNavi->deleteEntByID($id);
            if($res){
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }
        
        public function addnav(){
            exit("不可用");
            $this->display();
        }

        /**
         * 广告列表
         */
        public function adverList(){
            $nav = M('web_navi');
            $adv = M('advertisement');
            $co = $adv->field('tb_web_navi.name,tb_advertisement.*')->join('left join tb_web_navi on tb_web_navi.id=tb_advertisement.nid')->count();
            $Page       = new \Think\Page($co,20);
            $res = $adv->field('tb_web_navi.name,tb_web_navi.language,tb_advertisement.*')->join('left join tb_web_navi on tb_web_navi.id=tb_advertisement.nid')->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            $Page->setConfig( "prev", L("PreviousPage"));//上一页
            $Page->setConfig( "next", L("NextPage"));//下一页
            $show       = $Page->show();
            $nav_list = $nav->select();
            $this->assign('res',$res);
            $this->assign('count',count($res));
            $this->assign('page',$show);
            $this->assign('cate',$nav_list);
            $this->assign('lang',$this->languagelisets);
            $this->display('adverList');
        }

    /**
     *删除广告
     */
    public function deladver(){
        $adver = M('advertisement');
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
                    $path = $adver->field('icon_image')->where('id='.$idval)->find();
                    //删除文件
                    $this->deluploadfile($path['icon_image']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $arr_id[]=$ids;
                $id = $ids;
                $path = $adver->field('icon_image')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['icon_image']);
            }

        }
        $status = $adver->delete($id);
        //echo $adver->_sql();
        //exit;
        if($status){
            $act=strtolower(I('get.act'));
            if($act=='impotaddlist'){
                $ses=array_diff(session('adverlistAddId'), $arr_id);
                if(count($ses)>0){
                    session('adverlistAddId',$ses);
                }else{
                    session('adverlistAddId',null);
                }
            }
            $this->success('删除成功',U('Webnav/'.$act));
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 添加广告时的语言切换选择分类
     */
    public function langType(){
        if(IS_POST){
            $lang = I('post.language');
            $data['language'] = $lang;
            $ft = M('web_navi');
            $res = $ft ->where($data)->select();
            if($res){
                $this->ajaxReturn($res);
            }else{
                $this->ajaxReturn('');
            }
        }
    }

        /**
         * 添加广告
         */
        public function addAdver(){
            $nav = M('web_navi');
            if(IS_POST){
                $advertise = M('advertisement');
                $tit_name = I('post.tit_name');
                $link_address = I('post.link_address');
                $nid =  I('post.nid');
                if(empty($nid)){
                    $this->error('分类不能为空');
                    exit;
                }
                if(empty($link_address)){
                    $this->error('跳转链接不能为空');
                    exit;
                }

                //处理抓取图片
                if(!empty($_POST['imageURL'])){
                    $dir="Public/Upload/Advertisement/".date('Y-m-d');
                    if(!is_dir($dir)){
                        mkdir($dir,0755,true);
                    }
                    if(!empty($_POST['imageURL'])){
                        set_time_limit(120);
                        foreach($_POST['imageURL'] as $v){
                            $filename=date("YmdHis").strval(rand(111111,999999)).substr($v,strrpos($v,'.'));
                            $pathname=$dir.'/'.$filename;
                            if(startWith($v, '//')) $v='http:'.$v;
                            $fileCode=file_get_contents($v);
                            if($fileCode){
                                $res=file_put_contents($pathname,$fileCode);
                                if($res) $filePath[]=$pathname;
                            }
                        }
                    }
                }
                //处理上传的图片
                $info = $this->upload('Advertisement');
                $icon_image = [];
                if(count($info)>1){
                    foreach($info as $fval){
                        $icon_image[]= 'Public/'.$fval['savepath'].$fval['savename'];
                    }
                }else{
                    if(isset($info[0]['savename'])){
                        $icon_image[] ='Public/'.$info[0]['savepath'].$info[0]['savename'];
                    }
                }

                if(count($filePath)>0 && count($icon_image)>0){
                    $allpath = array_merge($filePath,$icon_image);
                }else if(count($filePath)>0){
                    $allpath = $filePath;
                }else if(count($icon_image)>0){
                    $allpath = $icon_image;
                }else{
                    $allpath='';
                }

                $data['tit_name'] = $tit_name;
                $data['link_address'] = $link_address;
                $data['icon_image'] = implode(',',$allpath);;
                $data['nid'] = $nid;

                $status = $advertise ->data($data) ->add();

                if($status){
                    $this->success('添加成功',U('Webnav/adverList'));
                }else{
                    $this->error('添加失败');
                }
                exit;
            }
            $nav_list = $nav->select();
            $this->assign('lang',$this->languagelisets);
            $this->assign('cate',$nav_list);
            $this->display('adverlistadd');
        }

        /**
         * 更新广告
         */
    public function updateAdver(){
        if(IS_POST){
            $advertisement_object = M('advertisement');
            $data=[];
            $data['id'] = I('post.id');
            $data['tit_name'] = I('post.name');
            $data['nid'] = I('post.nid');
            $data['link_address'] = I('post.link');

            $info = $this->upload('Advertisement');
            //dump($info);
            //exit;
            $img1 = $info['pic1']['savepath'].$info['pic1']['savename'];
            $img2 = $info['pic2']['savepath'].$info['pic2']['savename'];
            $img3 = $info['pic3']['savepath'].$info['pic3']['savename'];

            $images = $advertisement_object->field('icon_image')->where('id='.$data['id'])->select();
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

            if($advertisement_object->data($data)->save()){
                $this->success('更新成功');
            }else{
                $this->error('更新失败');
            }
            return false;
        }
        exit;
    }

    /**
     * api导航站点列表
     */
    public function apinav(){
        $nav = M('api_nav');
        $count=$nav->count();
        $Page=new \Think\Page($count,20);
        $res = $nav->order('sort asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show=$Page->show();
        foreach($res as $key=>$val){
            $res[$key]['languages'] = L($val['language']);
        }
        $this->assign('list',$res);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->display('apinav');
    }

    /**
     *添加api导航信息
     */
    public function addapinav(){
        if(IS_POST){
            $data = [];
            $data['tit_name'] = I('post.name');
            $data['link_address'] = I('post.link');
            $data['language'] = I('post.lang');
            $data['sort'] = I('post.sort');
            if(empty($data['sort'])){
                $data['sort']=255;
            }
            $info = $this->upload('Webnav');
            //dump($info);
            //exit;
            $icon_image ='Public/'.$info['pic']['savepath'].$info['pic']['savename'];
            $data['icon'] = $icon_image;
            if(M('api_nav')->data($data)->add()){
                $this->success('添加成功！',U('Webnav/apinav'));
            }else{
                $this->error('添加失败');
            }
            exit;
        }
        $this->assign('lang',$this->languagelisets);
        $this->display('apinavadd');
    }

    /**
     * 更新api导航
     */
    public function updateapinav(){
        if(IS_POST){
            $data = [];
            $data['id'] = I('post.id');
            $data['tit_name'] = I('post.name');
            $data['link_address'] = I('post.link');
            $info = $this->upload('Webnav');
            $pic = I('post.pic');
            if($info){
                $icon_image ='Public/'.$info['pic']['savepath'].$info['pic']['savename'];
                $data['icon'] = $icon_image;
                @unlink('./'.$pic);
            }else{
                $data['icon'] = I('post.pic');
            }
            $data['language'] = I('post.lang');
            $data['sort'] = I('post.sort');
            if(M('api_nav')->data($data)->save()){
                $this->success('更新成功！',U('Webnav/apinav'));
            }else{
                $this->error('更新失败');
            }
        }
    }

    /**
     * 删除api导航
     */
    public function delapinav(){
        $apinav = M('api_nav');
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
                foreach($ids as $idval){
                    $id.=$idval.',';
                    $path = $apinav->field('icon')->where('id='.$idval)->find();
                    //删除文件
                    $this->deluploadfile($path['icon']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $id = $ids;
                $path = $apinav->field('icon')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['icon']);
            }

        }
        $status = $apinav->delete($id);
        if($status){
            $this->success('删除成功',U('Webnav/apinav'));
        }else{
            $this->error('删除失败');
        }
    }

    //csv文件导入数据
    public function csvfile(){

        if(empty($_FILES) || empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['size']<1){
            $this->error('没有文件上传',U('Webnav/adverList'),3);
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
                    if(count($val)<3){
                        $errRow[]=$key;
                        unset($arrdata[$key]);
                    }else{
                        $formattime=date("Y-m-d");
                        $dir="Public/Upload/Advertisement/".$formattime;
                        if(!is_dir($dir)){
                            mkdir($dir,0755,true);
                        }
                        $navi=$langtype-> istype_adver($val[3]);
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
                                for($i=0;$i<3;$i++){
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
                                        'nid'=>$navi[0]['tid'],
                                    );

                                    $result[]=M('advertisement')->data($data)->add();
                                }

                            }
                        }
                    }

                }
                if(empty($arrdata)) $flag=false;
            }

            if(!$flag){
                $this->error('文件格式有误或空数据文件',U('Webnav/adverList'),3);
            }else{
                $errRowNum=empty($errRow)? 0 : count($errRow);
                if(!empty($result)){
                    $idArr=array();
                    $idArr['id_count']=count($result);
                    for($i=0;$i<count($result);$i++){
                        $idArr['arr_id'.$i]=$result[$i];
                    }
                    if($errRowNum>0){
                        $this->success('上传数据成功'.count($result).'条,失败'.$errRowNum.'条', U('Webnav/ImpotAddList',$idArr), 3);
                    }else{
                        $this->success('全部数据上传成功', U('Webnav/ImpotAddList',$idArr), 3);
                    }
                }else{
                    $this->error('上传数据失败',U('Webnav/adverList'),3);
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
            if(!empty($arr_id)) session('adverlistAddId',$arr_id);
        }
        if(empty(session('adverlistAddId'))) exit('暂无数据');

        $nav = M('web_navi');
        $adv = M('advertisement');
        $co  = count(session('adverlistAddId'));
        $Page       = new \Think\Page($co,20);
        $res = $adv->field('tb_web_navi.name,tb_advertisement.*')->join('left join tb_web_navi on tb_web_navi.id=tb_advertisement.nid')->where('tb_advertisement.id in ('.implode(',', session('adverlistAddId')).')')->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show       = $Page->show();
        $nav_list = $nav->select();
        $this->assign('res',$res);
        $this->assign('count',count($res));
        $this->assign('page',$show);
        $this->assign('cate',$nav_list);
        $this->display('adverList');

    }

}