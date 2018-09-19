<?php
/**
 * 新闻管理2017-09-09
 */
namespace Admin\Controller;
use Think\Page;
class NewsController extends DefaultInitController{
	public $upload_pre=__ROOT__.'/'; //资源站点路径前缀
        public $dir=''; //图片目录
        public function __construct() {
            parent::__construct();
            $formattime=date("Y-m-d");
            $this->dir="Public/Upload/News/".$formattime;
        }
        public function Area(){
            $newsNavi=new \Admin\Model\News_naviModel;
            $co = $newsNavi->counts();
            $Page       = new \Think\Page($co,20);
            $Page->setConfig( "prev", L("PreviousPage"));//上一页
            $Page->setConfig( "next", L("NextPage"));//下一页
            $show       = $Page->show();
            $list=$newsNavi->listAll('',$Page->firstRow,$Page->listRows);
            foreach($list as $k=>$val){
                $list[$k]['language']=L($val['language']);
            }
            $listCount=$newsNavi->count();
            $this->assign('lang',$this->languagelisets);
            $this->assign('list', $list);
            $this->assign('count',$co);
            $this->assign('page',$show);
            $this->display('Area');
        }
        
        /**
         * 删除新闻版块
         */
        public function deleteEntByID(){
            $newsNavi=new \Admin\Model\News_naviModel;
            if(IS_GET){
                $arrayId=I('get.check');
            }else{
                $arrayId=I('post.check');
            }
            $res=$newsNavi->deleteEntByID($arrayId);
            if($res){
                $this->success(L("DeleteSuccess"), U('News/Area'), 3);
            }else{
                $this->error(L("DeleteFailure"),U('News/Area'),3);
            }
        }
        
        /**
         * 添加新闻版块
         */
        public function addnav(){
            if(IS_POST){
                if(I('post.name')){
                    $newsNavi=new \Admin\Model\News_naviModel;
                    $res=$newsNavi->addlist($_POST);
                    if($res){
                        $this->success(L("AddSuccess"), U('News/Area'), 3);
                    }else{
                        $this->error(L("AddFailure"),U('News/Area'),3);
                    }
                }
            }
        }

        /**
         * 更新新闻版块
         */
        public function updateNav(){
            if(IS_POST){
                if(I('post.name')){
                    $newsNavi=new \Admin\Model\News_naviModel;
                    $res=$newsNavi->updatelist($_POST);
                    if($res){
                        $this->success('更新成功', U('News/Area'), 3);
                    }else{
                        $this->error('更新失败',U('News/Area'),3);
                    }
                }
            }
        }
        
        /**
         * 新闻列表
         */
        public function newsList(){
           
            $pageNo = I("p"); 
            if (empty ( $pageNo )) {
                    $pageNo = 0;
            }            
            $pageSize = 20;
            $newsListObj=new \Admin\Model\News_listModel;
            $count=$newsListObj->where('is_put=1')->count();
            $page=new Page($count,$pageSize);
            $page->setConfig( "prev", L("PreviousPage"));//上一页
            $page->setConfig( "next", L("NextPage"));//下一页
            $show=$page->show();
            $list=$newsListObj->getList($pageNo,$pageSize);
            $nav = $newsListObj->getNavList();
            foreach($list as $key=>$val){
                $list[$key]['languages'] = L($val['language']);
            }
            $this->assign('lang',$this->languagelisets);
            $this->assign('nav',$nav);
            $this->assign('list',$list);
            $this->assign('page',$show);
            $this->assign('pageCount',$count);
            $this->display('newsList');
        }
        
        /**
         * 批量导入数据后进入的列表页
         */
        public function ImpotAddList(){
           if(!empty(I('arr_id0'))){
               $arr_id=array();
               $id_count=I('id_count');
               for($i=0;$i<$id_count;$i++){
                   $arr_id[]=I('arr_id'.$i);
               }
               if(!empty($arr_id)) session('newsAddId',$arr_id);
           }
           if(empty(session('newsAddId'))) exit('暂无数据');
            $pageNo = I("p"); 
            if (empty ( $pageNo )) {
                    $pageNo = 0;
            }            
            $pageSize = 20;
            $newsListObj=new \Admin\Model\News_listModel;
            $count=count(session('newsAddId'));
            $page=new Page($count,$pageSize);
            $page->setConfig( "prev", L("PreviousPage"));//上一页
            $page->setConfig( "next", L("NextPage"));//下一页
            $show=$page->show();
            $list=$newsListObj->getList($pageNo,$pageSize,session('newsAddId'),'0');
            $nav = $newsListObj->getNavList();
            foreach($list as $key=>$val){
                $list[$key]['languages'] = L($val['language']);
            }
            $this->assign('lang',$this->languagelisets);
            $this->assign('nav',$nav);
            $this->assign('list',$list);
            $this->assign('page',$show);
            $this->assign('pageCount',$count);
            $this->display('newsList');
        }
        
        //未发布的新闻
        public function off_put_list(){
            $pageNo = I("p"); 
            if (empty ( $pageNo )) {
                    $pageNo = 0;
            }            
            $pageSize = 20;
            $newsListObj=new \Admin\Model\News_listModel;
            $count=$newsListObj->where('is_put=0')->count();
            $page=new Page($count,$pageSize);
            $page->setConfig( "prev", L("PreviousPage"));//上一页
            $page->setConfig( "next", L("NextPage"));//下一页
            $show=$page->show();
            $list=$newsListObj->getList($pageNo,$pageSize,array(),'0');
            $nav = $newsListObj->getNavList();
            foreach($list as $key=>$val){
                $list[$key]['languages'] = L($val['language']);
            }
            $this->assign('lang',$this->languagelisets);
            $this->assign('nav',$nav);
            $this->assign('list',$list);
            $this->assign('page',$show);
            $this->assign('pageCount',$count);
            $this->display('newsList');
        }
        
        //添加新闻
        public function addNews(){
            $newsNavi=new \Admin\Model\News_naviModel;
            if(IS_POST){
                $result=false;
                $filePath=array();
                if(!empty($_FILES) or !empty($_POST['imageURL'])){
                    $dir=$this->dir;
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
                } 
                if(!empty($filePath) && !empty(I('post.tit_name'))){
                     $filePathStr=implode(',', $filePath);
                     $createdate=date("Y-m-d H:i:s");
                     $language=$newsNavi->field('language')->where('id='.I('post.news_nav_id'))->find()['language'];
                     $data=array(
                         'news_nav_id'=>I('post.news_nav_id'),
                         'tit_name'=>I('post.tit_name'),
                         'icon_image'=>$filePathStr,
                         'link_address'=>I('post.link_address'),
                         'come_from'=>I('post.come_from'),
                         'language'=>$language,
                         'create_date'=>$createdate,
                         'is_put'=>I('post.is_put')
                     );
                     $newsList=new \Admin\Model\News_listModel;
                     $result=$newsList->addlist($data);
                }
                if($result){
                    if($data['is_put']=='1'){
                        $this->success(L("AddSuccess"), U('News/newsList'));
                    }else{
                        $this->success(L("AddSuccess"), U('News/off_put_list'));
                    }
                }else{
                    $this->error(L("AddFailure"),U('News/newsList'));
                }
            }else{
                $langObj=new \Admin\Model\LanguageModel;
                $language=$langObj->getList();
                $this->assign('language',$language);
                $this->display('addNews');
            } 
        }
        
        //csv文件导入数据
        public function csvfile(){
            if(empty($_FILES) || empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['size']<1){
                $this->error('没有文件上传',U('News/addNews'),3);
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
                    $newsNavi=new \Admin\Model\News_naviModel;
                    $newsObj=new \Admin\Model\News_listModel;
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
                            if(empty($val[5])){
                                $arrdata[$key][5]='头条';
                                $val[5]='头条';
                            }
                            $navi=$newsNavi->isType($val[4],$val[5]);
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
                                    $dir=$this->dir;
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
                                            'news_nav_id'=>$navi[0]['id'],
                                            'tit_name'=>$val[0],
                                            'icon_image'=>implode(',', $imgPath),
                                            'link_address'=>$val[2],
                                            'come_from'=>$val[3],
                                            'language'=>$navi[0]['iso_code'],
                                            'create_date'=>date("Y-m-d H:i:s"),
                                            'is_put'=>0
                                        );
                                        $result[]=$newsObj->addlist($data);
                                    }
                                }
                            }
                        }
                        
                    }
                    if(empty($arrdata)) $flag=false;
                }
                if(!$flag){
                    $this->error('文件格式有误或空数据文件',U('News/newsList'),3);
                }else{
                    $errRowNum=empty($errRow)? 0 : count($errRow);
                    if(!empty($result)){
                        $idArr=array();
                        $idArr['id_count']=count($result);
                        for($i=0;$i<count($result);$i++){
                            $idArr['arr_id'.$i]=$result[$i];
                        }
                        if($errRowNum>0){
                            $this->success('上传数据成功'.count($result).'条,失败'.$errRowNum.'条', U('News/ImpotAddList',$idArr), 3);
                        }else{
                            $this->success('全部数据上传成功', U('News/ImpotAddList',$idArr), 3);
                        }
                    }else{
                        $this->error('上传数据失败',U('News/addNews'),3);
                    }
                }
            }
        }
        
        //新闻按语言分类
        public function langType(){
            $language=I('post.language');
            if(!$language) exit;
            $newsNavi=new \Admin\Model\News_naviModel;
            $navi=$newsNavi->getList($language);
            if($navi){
                echo json_encode($navi);
            }
        }

        //更新新闻
        public function updateNews(){
            if(IS_POST){
                $newsListObj=new \Admin\Model\News_listModel;
                $data=[];
                $data['id'] = I('post.id');
                $data['tit_name'] = I('post.name');
                $data['language'] = I('post.language');
                $data['news_nav_id'] = I('post.cate');
                $data['link_address'] = I('post.link');
                $data['come_from'] = I('post.from');
                $info = $this->upload('News');
                //dump($info);
                $img1 = $info['pic1']['savepath'].$info['pic1']['savename'];
                $img2 = $info['pic2']['savepath'].$info['pic2']['savename'];
                $img3 = $info['pic3']['savepath'].$info['pic3']['savename'];

                $images = $newsListObj->field('icon_image')->where('id='.$data['id'])->select();
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

                if($newsListObj->data($data)->save()){
                    $this->success('更新成功');
                }else{
                    $this->success('更新失败');
                }
                return false;
            }
            exit;
        }
        
        //删除新闻
        public function deleteNews(){
            if(IS_GET){
                $id=I('get.check');
            }else{
                $id=I('post.check');
            }

            if(empty($id)){
                $this->error(L("DeleteFailure"));
            }else{
                $newsList=new \Admin\Model\News_listModel;
                $act=I('get.act');
                $res=$newsList->delById($id,$act);
                if($res){
                    $this->success(L("DeleteSuccess"),U('News/'.$act));
                }else{
                    $this->error(L("DeleteFailure"));
                }
            }
        }
        
        //发布
        public function put(){
            $arr_id=I('post.check');
            if(empty($arr_id)) $this->error('没有选择数据！');
            $newsList=new \Admin\Model\News_listModel;
            $res=$newsList->put_on($arr_id);
            if($res){
                $this->success("发布成功",U('News/newsList'));
            }else{
                $this->error("发布失败");
            }
        }
        
        //下架处理
        public function removed(){
            $arr_id=I('check');
            if(empty($arr_id)) $this->error('没有选择数据！');
            $entObj=new \Admin\Model\News_listModel;
            $res=$entObj->put_off($arr_id);
            if($res){
                $this->success("处理成功",U('News/newslist'));
            }else{
                $this->error("处理失败");
            }
        }
}