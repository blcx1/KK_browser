<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Page;
use Think\Upload;

/**
 * 游戏管理2017-10-10
 */
class GameController extends DefaultInitController {
    public $dir=''; //图片目录
    public function __construct() {
        parent::__construct();
        $formattime=date("Y-m-d");
        $this->dir="Public/Upload/Game/".$formattime;
    }
    /**
     * 游戏推荐
     */
    public function recom(){
        $game = M('game_recom');

        $count      = $game->count();
        $Page       = new \Think\Page($count,20);
        $show       = $Page->show();
        $game_recom = $game->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($game_recom as $key=>$val){
            $game_recom[$key]['languages'] = L($val['language']);
        }
        $this->assign('page',$show);
        $this->assign('recom',$game_recom);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('recom');
    }

    /**
     * 添加推荐
     */
    public function addrecom(){
        if(IS_POST){
            $data = [];
            $data1 = [];
            $data['gamename'] = I('post.tit_name');
            $data['introduction'] = I('post.introduction');
            $data['language'] = I('post.language');
            $data['link'] = I('post.link_address');
            $data1['RPG'] = I('post.rpg');
            $data1['size'] = I('post.size');
            $data1['type'] = I('post.type');

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
                if($_FILES['image']['tmp_name']){
                    $filename=date("YmdHis").strval(rand(111111,999999));
                    $pathname=$dir.'/'.$filename;
                    $res=move_uploaded_file($_FILES['image']['tmp_name'],$pathname);
                    if($res){
                        $filePath[]=$pathname;
                    }
                }
            }
            if(empty($filePath)) $this->error('图片获取错误');
            $icon_image= implode(',', $filePath);

            $data['icon'] = $icon_image;
            $data['keyword'] = serialize($data1);
            $add = M('game_recom')->data($data)->add();
            if($add){
                $this->success('添加成功',U('recom'));
            }else{
                $this->error('添加失败');
            }
            return false;
        }
        $this->assign('lang',$this->languagelisets);
        $this->display('recomadd');
    }

    /**
     * 删除推荐
     */
    public function delrecom(){
        if(IS_GET){
            $ids = I('get.check');
        }else{
            $ids = I('post.check');
        }
        if(isset($ids)){
            $game = M('game_recom');
            $id = '';
            if(is_array($ids)){
                $arr_id=$ids;
                foreach($ids as $val){
                    $id.=$val.',';
                    $path = $game->field('icon')->where('id='.$val)->find();
                    //删除文件
                    $this->deluploadfile($path['icon']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $id = $ids;
                $arr_id[]=$ids;
                $path = $game->field('icon')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['icon']);
            }

            $delete = $game->delete($id);
            if($delete){
                $act=strtolower(I('get.act'));
                if($act=='impotaddlist1') {
                    $ses = array_diff(session('gamerecomAddId'), $arr_id);
                    if (count($ses) > 0) {
                        session('gamerecomAddId', $ses);
                    } else {
                        session('gamerecomAddId', null);
                    }
                }
                $this->success('删除成功',U('Game/'.$act));
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('还没有勾选');
        }
    }


    /**
     * 分类列表
     */
    public function game_type(){
        $game = M('game_type');

        $count      = $game->count();
        $Page       = new \Think\Page($count,20);
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show       = $Page->show();
        $game_type = $game->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($game_type as $key=>$val){
            $game_type[$key]['languages'] = L($val['language']);
        }
        $this->assign('page',$show);
        $this->assign('game_type',$game_type);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('game_type');
    }

    /**
     * 删除分类
     */
    public function delGame_type(){
        if(IS_GET){
            $ids = I('get.check');
        }else{
            $ids = I('post.check');
        }

        if(isset($ids)){
            $game = M('game_type');
            $id = '';
            if(is_array($ids)){
                foreach($ids as $val){
                    $id.=$val.',';
                }
                $id = mb_substr($id,0,-1);
            }else{
                $id = $ids;
            }

            $delete = $game->delete($id);
            if($delete){
                $this->success('删除成功',U('game_type'));
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
    public function addgame_type(){
        if(IS_POST){
            $data = [];
            $data['type_name'] = I('post.name');
            $data['language'] = I('post.language');
            $add = M('game_type')->data($data)->add();
            if($add){
                $this->success('添加成功',U('game_type'));
            }else{
                $this->error('添加失败');
            }
            return false;
        }
    }

    /**
     * 游戏列表
     */
    public function gameList(){
        $game_list=new \Admin\Model\Game_listModel;
        $count=$game_list->count();
        $page=new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show=$page->show();
        $res=$game_list->getList($page->firstRow, $page->listRows);
        $game_type=new \Admin\Model\Game_typeModel;
        $type_res=$game_type->getTypeAll();
        $this->assign('cate',$type_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('lang',$this->languagelisets);
        $this->display('gamelist');
    }


    /**
     * 添加游戏
     */
    public function addGame_list(){
        $game_type = M('game_type');
        $type_res = $game_type ->field('id,type_name') ->select();
        if(IS_POST){
            $game_list = M('game_list');
            $tid = I('post.cate');
            $tit_name = I('post.tit_name');
            $txt_content = I('post.introduction');
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
            $data['tit_name'] = $tit_name;
            $data['subtitle'] = $txt_content;
            $data['link_address'] = $link_address;
            $data['icon'] = $icon_image;
            $data['is_put'] = I('post.is_put');

            $status = $game_list ->data($data) ->add();

            if($status){
                $this->success('添加成功',U('Game/'.($data['is_put']==1?'gamelist':'off_put_list')));
            }else{
                $this->error('添加失败');
            }
            exit;
        }
        $this->assign('lang',$this->languagelisets);
        $this->assign('cate',$type_res);
        $this->display('gameadd');

    }

    /**
     * 添加游戏时的语言切换选择分类
     */
    public function langType(){
        if(IS_POST){
            $lang = I('post.language');
            $data['language'] = $lang;
            $ft = M('game_type');
            $res = $ft ->where($data)->select();
            if($res){
                $this->ajaxReturn($res);
            }else{
                $this->ajaxReturn('');
            }
        }
    }

    /**
     * 删除游戏
     */
    public function delGame_list(){
        $game_list = M('game_list');
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
                    $path = $game_list->field('icon')->where('id='.$idval)->find();
                    //删除文件
                    $this->deluploadfile($path['icon']);
                }
                $id = mb_substr($id,0,-1);
            }else{
                $arr_id[]=$ids;
                $id = $ids;
                $path = $game_list->field('icon')->where('id='.$id)->find();
                //删除文件
                $this->deluploadfile($path['icon']);
            }
        }
        $status = $game_list->delete($id);
        if($status){
            $act=strtolower(I('get.act'));
            if($act=='impotaddlist') {
                $ses = array_diff(session('gameAddId'), $arr_id);
                if (count($ses) > 0) {
                    session('gameAddId', $ses);
                } else {
                    session('gameAddId', null);
                }
            }
            $this->success('删除成功',U('Game/'.$act));
        }else{
            $this->error('删除失败');
        }
    }

    /**
     *更新游戏分类
     */
    public function updateGameType(){
        if(IS_POST){
            $gametype_object = M('game_type');
            $data = [];
            $data['id'] = I('post.id');
            $data['type_name'] = I('post.name');
            $data['language'] = I('post.language');
            if($gametype_object->data($data)->save()){
                $this->success('更新成功','game_type');
            }else{
                $this->error('更新失败');
            }
        }
        exit;
    }

    /**
     * 更新游戏
     */
    public function updateGame(){
        if(IS_POST){
            $gamelist_object = M('game_list');
            $data=[];
            $data['id'] =I('post.id');
            $data['tid'] =I('post.tid');
            $data['tit_name'] =I('post.name');
            $data['subtitle'] =I('post.subname');
            $data['link_address'] = I('post.link');
            //dump($data);
            //exit;
            $info = $this->upload('Game');

            $img1 = $info['pic']['savepath'].$info['pic']['savename'];
            $images = $gamelist_object->field('icon')->where('id='.$data['id'])->select();
            $images = $images[0]['icon'];

            if($img1!=''){
                @unlink('./'.$images);
                $images = 'Public/'.$img1;
            }

            $data['icon'] = $images;

            $game = $gamelist_object->data($data)->save();
            if($game){
                $this->success('更新成功');
            }else{
                $this->error('更新失败');
            }
        }
    }

    /**
     * 更新游戏推荐
     */
    public function updateRecom(){
        if(IS_POST){

            $data = [];
            $data1 = [];
            $data['id'] = I('post.id');
            $data['gamename'] = I('post.name');
            $data['introduction'] = I('post.desc');
            $data['language'] = I('post.language');
            $data['link'] = I('post.link');
            $data1['RPG'] = I('post.rpg');
            $data1['size'] = I('post.size');
            $data1['type'] = I('post.type');

            $info = $this->upload('Game');

            $img1 = $info['pic']['savepath'].$info['pic']['savename'];

            $images = M('game_recom')->field('icon')->where('id='.$data['id'])->select();
            $images = $images[0]['icon'];

            if($img1!=''){
                @unlink('./'.$images);
                $images = 'Public/'.$img1;
            }

            $data['icon'] = $images;

            $data['keyword'] = serialize($data1);
            $save = M('game_recom')->data($data)->save();
            if($save){
                $this->success('更新成功');
            }else{
                $this->error('更新失败');
            }
            return false;
        }
    }

    //csv文件导入数据
    public function csvfile(){
        if(empty($_FILES) || empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['size']<1){
            $this->error('没有文件上传',U('Game/gameList'),3);
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
                $langtype=new \Admin\Model\Game_typeModel;
                foreach($arrdata as $key=>$val){
                    if(count($val)<4){
                        $errRow[]=$key;
                        unset($arrdata[$key]);
                    }else{
                        $formattime=date("Y-m-d");
                        $dir="Public/Upload/Game/".$formattime;
                        if(!is_dir($dir)){
                            mkdir($dir,0755,true);
                        }
                        if(empty($val[4])){
                            $arrdata[$key][4]=$val[4]='中文简体';
                        }
                        if(empty($val[5])){
                            $arrdata[$key][5]=$val[5]='射击';
                        }
                        $navi=$langtype->is_type($val[4], $val[5]);
                        if(!$navi){
                            $errRow[]=$key;
                            unset($arrdata[$key]);
                        }else{
                            if(empty($val[2]) || empty($val[1]) || empty($val[0])){
                                $errRow[]=$key;
                                unset($arrdata[$key]);
                            }else{
                                $imagUrl=$val[1];
                                $imagUrl_err=true; //记录imagUrl地址是否无效。
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
                                    $imagUrl_err=false;
                                    $data=array(
                                        'tid'=>$navi[0]['id'],
                                        'tit_name'=>$val[0],
                                        'subtitle'=>$val[3],
                                        'icon'=>$pathname,
                                        'link_address'=>$val[2],
                                        'is_put'=>0
                                    );
                                    $result[]=M('game_list')->data($data)->add();
                                }
                                if($imagUrl_err){
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
                $this->error('文件格式有误或空数据文件',U('Game/gameList'),3);
            }else{
                $errRowNum=empty($errRow)? 0 : count($errRow);
                if(!empty($result)){
                    $idArr=array();
                    $idArr['id_count']=count($result);
                    for($i=0;$i<count($result);$i++){
                        $idArr['arr_id'.$i]=$result[$i];
                    }
                    if($errRowNum>0){
                        $this->success('上传数据成功'.count($result).'条,失败'.$errRowNum.'条', U('Game/ImpotAddList',$idArr), 3);
                    }else{
                        $this->success('全部数据上传成功', U('Game/ImpotAddList',$idArr), 3);
                    }
                }else{
                    $this->error('上传数据失败',U('Game/gameList'),3);
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
            if(!empty($arr_id)) session('gameAddId',$arr_id);
        }
        if(empty(session('gameAddId'))) exit('暂无数据');

        $game_list=new \Admin\Model\Game_listModel;
        $is_put="0";
        $count=count(session('gameAddId'));
        $page=new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show=$page->show();
        $res=$game_list->getList($page->firstRow, $page->listRows, session('gameAddId'), $is_put);
        $game_type=new \Admin\Model\Game_typeModel;
        $type_res=$game_type->getTypeAll();
        $this->assign('cate',$type_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('lang',$this->languagelisets);
        $this->display('gamelist');
    }


    //csv文件导入数据(推荐)
    public function csvfile1(){
        if(empty($_FILES) || empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['size']<1){
            $this->error('没有文件上传',U('Game/recom'),3);
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
                foreach($arrdata as $key=>$val){
                    if(count($val)<6){
                        $errRow[]=$key;
                        unset($arrdata[$key]);
                    }else{
                        $formattime=date("Y-m-d");
                        $dir="Public/Upload/Game/".$formattime;
                        if(!is_dir($dir)){
                            mkdir($dir,0755,true);
                        }
                        $langtype=new \Admin\Model\LanguageModel;
                        $navi=$langtype-> istype($val[7]);
                        if(!$navi){
                            $errRow[]=$key;
                            unset($arrdata[$key]);
                        }else{

                            if(empty($val[2]) || empty($val[1]) || empty($val[0])){
                                $errRow[]=$key;
                                unset($arrdata[$key]);
                            }else{

                                $imagUrl=explode(';',$val[3]);
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
                                    $data1=[];
                                    $data1['RPG'] = $val[4];
                                    $data1['size'] = $val[5];
                                    $data1['type'] = $val[6];
                                    $data=array(
                                        'gamename'=>$val[0],
                                        'introduction'=>$val[1],
                                        'icon'=>implode(',', $imgPath),
                                        'link'=>$val[2],
                                        'language'=>$navi[0]['iso_code'],
                                        'keyword' =>serialize($data1),
                                    );
                                    $result[]=M('game_recom')->data($data)->add();
                                }

                            }
                        }
                    }

                }
                if(empty($arrdata)) $flag=false;
            }

            if(!$flag){
                $this->error('文件格式有误或空数据文件',U('Game/recom'),3);
            }else{
                $errRowNum=empty($errRow)? 0 : count($errRow);
                if(!empty($result)){
                    $idArr=array();
                    $idArr['id_count']=count($result);
                    for($i=0;$i<count($result);$i++){
                        $idArr['arr_id'.$i]=$result[$i];
                    }
                    if($errRowNum>0){
                        $this->success('上传数据成功'.count($result).'条,失败'.$errRowNum.'条', U('Game/ImpotAddList1',$idArr), 3);
                    }else{
                        $this->success('全部数据上传成功', U('Game/ImpotAddList1',$idArr), 3);
                    }
                }else{
                    $this->error('上传数据失败',U('Game/recom'),3);
                }
            }
        }
    }

    /**
     * 批量添加成功后进入的列表页面(推荐)
     */
    public function ImpotAddList1(){
        if(!empty(I('arr_id0'))){
            $arr_id=array();
            $id_count=I('id_count');
            for($i=0;$i<$id_count;$i++){
                $arr_id[]=I('arr_id'.$i);
            }
            if(!empty($arr_id)) session('gamerecomAddId',$arr_id);
        }
        if(empty(session('gamerecomAddId'))) exit('暂无数据');

        $game = M('game_recom');
        $count      = count(session('gamerecomAddId'));
        $Page       = new \Think\Page($count,20);
        $show       = $Page->show();
        $game_recom = $game->order('id desc')->where('id in ('.implode(',', session('gamerecomAddId')).')')->select();
        foreach($game_recom as $key=>$val){
            $game_recom[$key]['languages'] = L($val['language']);
        }
        $this->assign('page',$show);
        $this->assign('recom',$game_recom);
        $this->assign('lang',$this->languagelisets);
        $this->assign('count',$count);
        $this->display('recom');
    }
    
    /*
     * 未发布列表
     */
    public function off_put_list(){
        $game_list=new \Admin\Model\Game_listModel;
        $is_put="0";
        $count=$game_list->count($is_put);
        $page=new \Think\Page($count,20);
        $page->setConfig( "prev", L("PreviousPage"));//上一页
        $page->setConfig( "next", L("NextPage"));//下一页
        $show=$page->show();
        $res=$game_list->getList($page->firstRow, $page->listRows, array(), $is_put);
        $game_type=new \Admin\Model\Game_typeModel;
        $type_res=$game_type->getTypeAll();
        $this->assign('cate',$type_res);
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('lang',$this->languagelisets);
        $this->display('gamelist');
    }
    
    //发布
    public function put(){
        $arr_id=I('post.check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $gameList=new \Admin\Model\Game_listModel;
        $res=$gameList->put_on($arr_id,'0');
        if($res){
            $this->success("发布成功",U('Game/gamelist'));
        }else{
            $this->error("发布失败");
        }
    }
    
    //下架处理
    public function removed(){
        $arr_id=I('check');
        if(empty($arr_id)) $this->error('没有选择数据！');
        $gameObj=new \Admin\Model\Game_listModel;
        $res=$gameObj->put_off($arr_id);
        if($res){
            $this->success("处理成功",U('Game/gamelist'));
        }else{
            $this->error("处理失败");
        }
    }

}