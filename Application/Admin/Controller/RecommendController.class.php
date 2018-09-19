<?php

namespace Admin\Controller;
use Think\Page;
use Think\Upload;
/**
 * 推荐管理2017-10-10
 */
class RecommendController extends DefaultInitController {

    /**
     * 推荐列表
     */
    public function recomList(){
        $recommend_list = M('recommend_list');
        $count      = $recommend_list->count();
        $Page       = new \Think\Page($count,20);
        $Page->setConfig( "prev", L("PreviousPage"));//上一页
        $Page->setConfig( "next", L("NextPage"));//下一页
        $show       = $Page->show();
        $res = $recommend_list
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_recommend_list.language as l,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                ->order('tb_recommend_list.id desc')
                ->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($res as $key=>$val){
            $obj = M($val['link_table']);
            $a = $obj ->where('id='.$val['pid'])->find();
            $res[$key]['tit_name'] = $a['tit_name'];
            $res[$key]['language'] = L($val['language']);
            $res[$key]['link_address'] = $a['link_address'];
            unset($obj);
            unset($a);
        }
        $this->assign('page',$show);
        $this->assign('res',$res);
        $this->assign('count',$count);
        $this->assign('lang',$this->languagelisets);
        $this->display('recomList');
    }

    /**
     * 推荐列表搜索
     */
    public function recomm_search(){
        $key = I('get.key');
        $seartype = I('get.seartype');
        //新闻中文
        if($seartype==2){
            $news_cn = M('recommend_list');
            $news_cn_where['tb_recommend_list.nav_id'] = $seartype;
            $news_cn_where['tb_recommend_list.language'] = 'zh-cn';
            $news_cn_where['tb_news_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_news_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_news_list.tit_name')
                ->join('left join tb_news_list on tb_recommend_list.pid=tb_news_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where)->select();
        }
        //视频中文
        if($seartype==3){
            $news_cn = M('recommend_list');
            $news_cn_where['tb_recommend_list.nav_id'] = $seartype;
            $news_cn_where['tb_recommend_list.language'] = 'zh-cn';
            $news_cn_where['tb_video_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_video_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_video_list.tit_name')
                ->join('left join tb_video_list on tb_recommend_list.pid=tb_video_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where)->select();
        }
        //娱乐中文
        if($seartype==4){
            $news_cn = M('recommend_list');
            $news_cn_where['tb_recommend_list.nav_id'] = $seartype;
            $news_cn_where['tb_recommend_list.language'] = 'zh-cn';
            $news_cn_where['tb_entertainment_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_entertainment_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_entertainment_list.tit_name')
                ->join('left join tb_entertainment_list on tb_recommend_list.pid=tb_entertainment_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where)->select();
        }
        //新闻英文
        if($seartype==12){
            $news_cn = M('recommend_list');
            $news_cn_where['tb_recommend_list.nav_id'] = $seartype;
            $news_cn_where['tb_recommend_list.language'] = 'en-us';
            $news_cn_where['tb_news_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_news_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_news_list.tit_name')
                ->join('left join tb_news_list on tb_recommend_list.pid=tb_news_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where)->select();
        }
        //视频英文
        if($seartype==13){
            $news_cn = M('recommend_list');
            $news_cn_where['tb_recommend_list.nav_id'] = $seartype;
            $news_cn_where['tb_recommend_list.language'] = 'en-us';
            $news_cn_where['tb_video_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_video_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_video_list.tit_name')
                ->join('left join tb_video_list on tb_recommend_list.pid=tb_video_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where)->select();
        }
        //娱乐英文
        if($seartype==14){
            $news_cn = M('recommend_list');
            $news_cn_where['tb_recommend_list.nav_id'] = $seartype;
            $news_cn_where['tb_recommend_list.language'] = 'en-us';
            $news_cn_where['tb_entertainment_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_entertainment_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_entertainment_list.tit_name')
                ->join('left join tb_entertainment_list on tb_recommend_list.pid=tb_entertainment_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where)->select();
        }
        //新闻繁体
        if($seartype==22){
            $news_cn = M('recommend_list');
            $news_cn_where['tb_recommend_list.nav_id'] = $seartype;
            $news_cn_where['tb_recommend_list.language'] = 'zh-tw';
            $news_cn_where['tb_news_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_news_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_news_list.tit_name')
                ->join('left join tb_news_list on tb_recommend_list.pid=tb_news_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where)->select();
        }
        //视频繁体
        if($seartype==23){
            $news_cn = M('recommend_list');
            $news_cn_where['tb_recommend_list.nav_id'] = $seartype;
            $news_cn_where['tb_recommend_list.language'] = 'zh-tw';
            $news_cn_where['tb_video_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_video_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_video_list.tit_name')
                ->join('left join tb_video_list on tb_recommend_list.pid=tb_video_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where)->select();
        }
        //娱乐繁体
        if($seartype==24){
            $news_cn = M('recommend_list');
            $news_cn_where['tb_recommend_list.nav_id'] = $seartype;
            $news_cn_where['tb_recommend_list.language'] = 'zh-tw';
            $news_cn_where['tb_entertainment_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_entertainment_list.link_address,tb_recommend_list.language,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_entertainment_list.tit_name')
                ->join('left join tb_entertainment_list on tb_recommend_list.pid=tb_entertainment_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where)->select();
        }
        //全部搜索
        if($seartype=='all'){
            //新闻
            $news_cn = M('recommend_list');

            $news_cn_where1['tb_news_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res1 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_news_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_news_list.tit_name')
                ->join('left join tb_news_list on tb_recommend_list.pid=tb_news_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where1)->select();

            //视频
            $news_cn_where2['tb_video_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res2 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_video_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_video_list.tit_name')
                ->join('left join tb_video_list on tb_recommend_list.pid=tb_video_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where2)->select();

            //娱乐
            $news_cn_where3['tb_entertainment_list.tit_name'] = array('like',"%".$key."%");
            $news_con_res3 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_entertainment_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_entertainment_list.tit_name')
                ->join('left join tb_entertainment_list on tb_recommend_list.pid=tb_entertainment_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where3)->select();

            if(empty($news_con_res1))$news_con_res1=[];
            if(empty($news_con_res2))$news_con_res2=[];
            if(empty($news_con_res3))$news_con_res3=[];
            //合并三表搜索结果
            $news_con_res=array_merge($news_con_res1,$news_con_res2,$news_con_res3);

        }

        //中文所有搜
        if($seartype=='all_cn'){
            //新闻
            $news_cn = M('recommend_list');

            $news_cn_where1['tb_news_list.tit_name'] = array('like',"%".$key."%");
            $news_cn_where1['tb_recommend_list.language'] = 'zh-cn';
            $news_con_res1 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_news_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_news_list.tit_name')
                ->join('left join tb_news_list on tb_recommend_list.pid=tb_news_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where1)->select();

            //视频
            $news_cn_where2['tb_video_list.tit_name'] = array('like',"%".$key."%");
            $news_cn_where2['tb_recommend_list.language'] = 'zh-cn';
            $news_con_res2 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_video_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_video_list.tit_name')
                ->join('left join tb_video_list on tb_recommend_list.pid=tb_video_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where2)->select();

            //娱乐
            $news_cn_where3['tb_entertainment_list.tit_name'] = array('like',"%".$key."%");
            $news_cn_where3['tb_recommend_list.language'] = 'zh-cn';
            $news_con_res3 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_entertainment_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_entertainment_list.tit_name')
                ->join('left join tb_entertainment_list on tb_recommend_list.pid=tb_entertainment_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where3)->select();

            if(empty($news_con_res1))$news_con_res1=[];
            if(empty($news_con_res2))$news_con_res2=[];
            if(empty($news_con_res3))$news_con_res3=[];
            //合并三表搜索结果
            $news_con_res=array_merge($news_con_res1,$news_con_res2,$news_con_res3);
        }
        //英文所有搜
        if($seartype=='all_en'){
            //新闻
            $news_cn = M('recommend_list');

            $news_cn_where1['tb_news_list.tit_name'] = array('like',"%".$key."%");
            $news_cn_where1['tb_recommend_list.language'] = 'en-us';
            $news_con_res1 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_news_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_news_list.tit_name')
                ->join('left join tb_news_list on tb_recommend_list.pid=tb_news_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where1)->select();

            //视频
            $news_cn_where2['tb_video_list.tit_name'] = array('like',"%".$key."%");
            $news_cn_where2['tb_recommend_list.language'] = 'en-us';
            $news_con_res2 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_video_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_video_list.tit_name')
                ->join('left join tb_video_list on tb_recommend_list.pid=tb_video_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where2)->select();

            //娱乐
            $news_cn_where3['tb_entertainment_list.tit_name'] = array('like',"%".$key."%");
            $news_cn_where3['tb_recommend_list.language'] = 'en-us';
            $news_con_res3 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_entertainment_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_entertainment_list.tit_name')
                ->join('left join tb_entertainment_list on tb_recommend_list.pid=tb_entertainment_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where3)->select();

            if(empty($news_con_res1))$news_con_res1=[];
            if(empty($news_con_res2))$news_con_res2=[];
            if(empty($news_con_res3))$news_con_res3=[];
            //合并三表搜索结果
            $news_con_res=array_merge($news_con_res1,$news_con_res2,$news_con_res3);
        }
        //繁体所有搜
        if($seartype=='all_tw'){
            //新闻
            $news_cn = M('recommend_list');

            $news_cn_where1['tb_news_list.tit_name'] = array('like',"%".$key."%");
            $news_cn_where1['tb_recommend_list.language'] = 'zh-tw';
            $news_con_res1 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_news_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_news_list.tit_name')
                ->join('left join tb_news_list on tb_recommend_list.pid=tb_news_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where1)->select();

            //视频
            $news_cn_where2['tb_video_list.tit_name'] = array('like',"%".$key."%");
            $news_cn_where2['tb_recommend_list.language'] = 'zh-tw';
            $news_con_res2 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_video_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_video_list.tit_name')
                ->join('left join tb_video_list on tb_recommend_list.pid=tb_video_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where2)->select();

            //娱乐
            $news_cn_where3['tb_entertainment_list.tit_name'] = array('like',"%".$key."%");
            $news_cn_where3['tb_recommend_list.language'] = 'zh-tw';
            $news_con_res3 = $news_cn
                ->field('tb_recommend_list.id as oid,tb_recommend_list.language,tb_entertainment_list.link_address,tb_recommend_list.nav_id,tb_recommend_list.pid,tb_web_navi.*,tb_entertainment_list.tit_name')
                ->join('left join tb_entertainment_list on tb_recommend_list.pid=tb_entertainment_list.id')
                ->join('left join tb_web_navi on tb_recommend_list.nav_id=tb_web_navi.id')
                -> where($news_cn_where3)->select();

            if(empty($news_con_res1))$news_con_res1=[];
            if(empty($news_con_res2))$news_con_res2=[];
            if(empty($news_con_res3))$news_con_res3=[];
            //合并三表搜索结果
            $news_con_res=array_merge($news_con_res1,$news_con_res2,$news_con_res3);
        }

        foreach($news_con_res as $key=>$val){
            $news_con_res[$key]['language'] = L($val['language']);
        }
        $count = count($news_con_res);
        $this->assign('count',$count);
        $this->assign('res',$news_con_res);
        $this->assign('lang',$this->languagelisets);
        $this->display('recomList');
    }

    /**
     * 添加推荐
     */
    public function addrecomList(){
        if(IS_POST){
            $entertainment_list = M('recommend_list');
            $tit_name = I('post.name');
            $link_address = I('post.link');
            $come_from = I('post.come_from');
            $lang = I('post.language');

            if(empty($tit_name)){
                $this->error('名称不能为空');
                exit;
            }
            if(empty($link_address)){
                $this->error('跳转链接不能为空');
                exit;
            }

            $data['tit_name'] = $tit_name;
            $data['link_address'] = $link_address;
            $data['come_from'] = $come_from;
            $data['create_date'] = date('Y-m-d H:i:s');
            $data['language'] = $lang;
            $data['is_top'] = 0;

            $status = $entertainment_list ->data($data) ->add();

            if($status){
                $this->success('添加成功',U('recomList'));
                exit;
            }else{
                $this->error('添加失败');
                exit;
            }
            exit;
        }
        exit;

    }

    /**
     * ajax查询
     */
    public function search(){
        if(IS_POST){
            $name = I('name');
            $nav_id = I('nav_id');
            $language = I('language');
            $table ='';
            if($nav_id==2 || $nav_id==12 || $nav_id==22){
                $table = 'news_list';
            }
            if ($nav_id==3 || $nav_id==13 || $nav_id==23){
                $table = 'video_list';
            }
            if($nav_id==4 || $nav_id==14 || $nav_id==24){
                $table = 'entertainment_list';
            }

            if(isset($table)){
                $obj = M($table);
                $where['tit_name'] = array('like',"%".$name."%");
                $where['language'] = $language;
                $res = $obj->order('id desc')->where($where)->limit(15)->select();
                //echo $obj->_sql();
                if($res){
                    $this->ajaxReturn($res);
                }else{
                    $this->ajaxReturn('no data');
                }
            }
            exit;
        }
        exit;
    }

    /**
     * 添加排行
     */
    public function addRecomm(){
        if(IS_POST){
            $pid = I('post.pid');
            $nav_id = I('post.nav_id');
            $language = I('post.language');
            $recom = M('recommend_list');
            if(!stristr($pid,',')){
                $data['pid'] = $pid;
                $data['nav_id'] = $nav_id;
                $data['language'] = $language;
                $status = 0;
                if(!$recom->where($data)->find()){
                    if($recom->data($data)->add()){
                        $status = 1;
                    }else{
                        $status = 0;
                    }
                }else{
                    $status = 0;
                }
            }else{
                $str = explode(',',$pid);
                foreach($str as $val){
                    $data = [];
                    $data['pid'] = $val;
                    $data['nav_id'] = $nav_id;
                    $status = 0;
                    if(!$recom->where($data)->find()){
                        $data['language'] = $language;
                        if($recom->data($data)->add()){
                            $status = 1;
                        }else{
                            $status = 0;
                        }
                    }else{
                        $status = 0;
                    }
                }
            }
            if($status){
                $this->ajaxReturn('ok');
                exit;
            }else{
                $this->ajaxReturn('');
                exit;
            }
            exit;
        }
        exit;
    }

    /**
     * 更新推荐
     */
    public function updateRecomm(){
        $recommend_list = M('recommend_list');
        $oid = I('post.oid');
        $navid = I('post.navid');
        $lang = I('post.lang');
        $did = I('post.did');
        $data =[];
        $data['id'] = $oid;
        $data['pid'] = $did;

        if($recommend_list->where('pid='.$did)->find()){
            $this->ajaxReturn('repeat');
            exit;
        }
        //新闻类型和用户选择进行比较判断
        if($navid==2 || $navid==12 || $navid==22){
            $table = 'news_list';
            $data1['language'] = $lang;
            $data1['id'] = $did;
            if($res = M($table)->where($data1)->find()){
                if($res['language'] == 'zh-cn'){
                    $data['nav_id'] = 2;
                    $data['language'] = 'zh-cn';
                }else if($res['language'] == 'en-us'){
                    $data['nav_id'] = 12;
                    $data['language'] = 'en-us';
                }else if($res['language'] == 'zh-tw'){
                    $data['nav_id'] = 22;
                    $data['language'] = 'zh-tw';
                }

            }else{
                $this->ajaxReturn('error');
                exit;
            }
        }
        //视频类型和用户选择进行比较判断
        if ($navid==3 || $navid==13 || $navid==23){
            $table = 'video_list';
            $data1['language'] = $lang;
            $data1['id'] = $did;
            if($res = M($table)->where($data1)->find()){
                if($res['language'] == 'zh-cn'){
                    $data['nav_id'] = 3;
                    $data['language'] = 'zh-cn';
                }else if($res['language'] == 'en-us'){
                    $data['nav_id'] = 13;
                    $data['language'] = 'en-us';
                }else if($res['language'] == 'zh-tw'){
                    $data['nav_id'] = 23;
                    $data['language'] = 'zh-tw';
                }

            }else{
                $this->ajaxReturn('error');
                exit;
            }
        }
        //娱乐类型和用户选择进行比较判断
        if($navid==4 || $navid==14 || $navid==24){
            $table = 'entertainment_list';
            $data1['language'] = $lang;
            $data1['id'] = $did;
            if($res = M($table)->where($data1)->find()){
                if($res['language'] == 'zh-cn'){
                    $data['nav_id'] = 4;
                    $data['language'] = 'zh-cn';
                }else if($res['language'] == 'en-us'){
                    $data['nav_id'] = 14;
                    $data['language'] = 'en-us';
                }else if($res['language'] == 'zh-tw'){
                    $data['nav_id'] = 24;
                    $data['language'] = 'zh-tw';
                }

            }else{
                $this->ajaxReturn('error');
                exit;
            }
        }
        //dump($data);
        //exit;
        if($recommend_list->save($data)){
            $this->ajaxReturn('ok');
            exit;
        }else{
            $this->ajaxReturn('');
            exit;
        }
        exit;
    }

    /**
     * 删除推荐
     */
    public function delrecommendList(){
        $recommend_list = M('recommend_list');
        if(IS_POST){
            $ids = I('post.check');
        }
        if(IS_GET){
            $ids = I('get.check');
        }
        if(empty($ids)){
            $this->error('还没有勾选');
        }else{
            $id = '';
            if(is_array($ids)){
                foreach($ids as $idval){
                    $id.=$idval.',';
                }
                $id = mb_substr($id,0,-1);
            }else{
                $id = $ids;
            }
        }

        $status = $recommend_list->delete($id);

        if($status){
            $this->success('删除成功',U('recomList'));
        }else{
            $this->error('删除失败');
        }
    }
        

}