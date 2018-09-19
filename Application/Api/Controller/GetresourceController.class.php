<?php
namespace Api\Controller;
use Think\Controller;
use andreskrey\Readability\Configuration;
import('phpQuery','./ThinkPHP/Library/phpQuery/phpQuery','.php');
class GetresourceController extends Controller{
    private $upload_dir='Public/Upload/';
    private $datatime=''; //时间
    private $come_from='新浪网';
    private $language='zh-cn';
    private $is_put=1;
    private $fx=['.jpg','.png','.gif','.jpeg','.mp4','.avi'];
    public function _initialize(){
        $this->datatime=date('Y-m-d H:i:s');
    }
    public function news(){
        $url='http://news.sina.com.cn/china/';
        $tag='.content .left .blk12 .blk121 a';
        $dir=$this->upload_dir.'News/'.substr($this->datatime,0,strpos($this->datatime,' '));
        \phpQuery::newDocumentFile($url);
        $length=pq($tag)->length;
        $href=[];
        if($length>=1){
            for($i=0;$i<$length;$i++){
                $href[]=pq($tag.':eq('.$i.')')->attr('href');
            }
        }
        $list=$this->getContent($href);
        $saveDates=false;
        if($list){
            foreach($list as $key=>$val){
                $list[$key]['imgurl']=$this->uploadImg($val['imgurl'],$dir);
                if($list[$key]['imgurl']) $list[$key]['icon_image']=implode(',', $list[$key]['imgurl']);
                $list[$key]['tit_name']=$val['title'];
                $list[$key]['create_date']=$this->datatime;
                $list[$key]['come_from']=$this->come_from;
                $list[$key]['language']=$this->language;
                $list[$key]['is_put']=$this->is_put;
                $list[$key]['news_nav_id']=rand(1,5);
                unset($list[$key]['imgurl']);
                unset($list[$key]['title']);
                unset($list[$key]['description']);
            }
            $newsListModel=new \Api\Model\News_listModel;
            $saveDates=$newsListModel->saveDates($list);
        }
        if($saveDates){
            echo '新闻数据自动获取ok!'.$this->datatime.PHP_EOL;
        }else{
            echo '新闻数据自动获取失败!'.$this->datatime.PHP_EOL;
        }
    }
    
    /*
     * 处理url组用于获取相关信息
     */
    private function getContent($href){
        if(!$href || !is_array($href)){
            return false;
        }
        $result=[];
        foreach($href as $v){
            $info=$this->getInfomation($v);
            if($info){
                $info['link_address']=$v;
                $result[]=$info;
            }
        }
        return $result;
    }
    
    /*
     * 获取网页页面各类信息
     */
    private function getInfomation($htmlurl){
        if(empty($htmlurl)){
            exit;
        }
        if(!startWith(strtolower($htmlurl),'http')) $htmlurl='http://'.$htmlurl;
        import('autoload','./ThinkPHP/Library/Readability','.php');
        $html = @file_get_contents($htmlurl);
        try {
            $readability = new \andreskrey\Readability\Readability(new Configuration());
            if(!$html) E('404');
            if(!$readability->parse($html)) E('404');
            $readall=$readability;
            if(!$readall) E('404');
            //$images=$readability->getImages();
        } catch (\Exception $e) {
            $msg=$e->getMessage();
            if($msg=='404'){
                exit;
            }
        }

        $imgleng=preg_match_all('/<img.*?src="(.*?)".*?>/is',$readall,$img);
        $imgurl=array();
        if(!empty($imgleng)){
            foreach($img[1] as $val){
                $imgurl[]= startWith($val, '//') ? 'http:'.$val : $val;
            }
        }
        if(count($imgurl)>2) unset($imgurl[count($imgurl)-1]);
        if(count($imgurl)>3) $imgurl=array_slice($imgurl,0,3);
        $result=false;
        if($imgurl){
            //获取title
            $title=$readability->getTitle()?$readability->getTitle():'';
            //获取描述
            $description=$readability->getExcerpt()?$readability->getExcerpt():'';
            $result=array('title'=>$title,'imgurl'=>$imgurl,'description'=>$description);
        }
        return $result;
    }
    
    /*
     * 保存图片
     */
    private function uploadImg($imgarr,$dir){
        $images=[];
        if(!$imgarr || !is_array($imgarr) || !$dir){
            return false;
        }
        if(!is_dir('./'.$dir)){
            mkdir($dir,0755,true);
        }
        foreach($imgarr as $v){
            if(startWith($v, '//')){
                $v='http:'.$v;
            }
            $getfile=file_get_contents($v);
            $fx=strrchr($v,'.');
            if(!in_array($fx,$this->fx)){
                $fx='.jpg';
            }
            if($getfile){
                $filename=$dir.'/'.uniqid().rand(100,999).$fx;
                $save=file_put_contents('./'.$filename, $getfile);
                if($save) $images[]=$filename;
                unset($getfile);
            }
        }
        return $images;
    }
    
    /*
     * 添加推荐
     */
    public function recommend(){
        $recommendModel=new \Api\Model\Recommend_listModel;
        $res=$recommendModel->putSource('zh-cn');
        if($res){
            echo '自动添加推荐Ok'.$this->datatime.PHP_EOL;
        }else{
            echo '自动添加推荐失败'.$this->datatime.PHP_EOL;
        }
    }
    
    /*
     * 添加娱乐新闻
     */
    public function entertainment(){
        $url='http://ent.qq.com/';
        $tag='#area2 div:eq(0) .ttMod .list:eq(0) div:eq(0) div:eq(0) .Q-tpList';
        $dir=$this->upload_dir.'Entertainment/'.substr($this->datatime,0,strpos($this->datatime,' '));
        \phpQuery::newDocumentFile($url);
        $length=pq($tag)->length;
        $list=[];
        if($length>=1){
            for($i=0;$i<$length;$i++){
                $link_address=pq($tag.':eq('.$i.') div div[class="text"] em:eq(0) a')->attr('href');
                $img=$this->getContent([$link_address]);
                if($img){
                    $title=pq($tag.':eq('.$i.') div div[class="text"] em:eq(0) a')->html();
                    $title=mb_convert_encoding($title,'ISO-8859-1','utf-8');
                    $list_count=count($list);
                    $imgarr=$this->uploadImg($list_count?$img[0]['imgurl']:[$img[0]['imgurl'][0]], $dir);
                    if($imgarr){
                        $list[]=[
                            'tit_name'=>mb_convert_encoding($title,'utf-8','gbk'),
                            'link_address'=>$link_address,
                            'icon_image'=>implode(',',$imgarr),
                            'come_from'=>'腾讯娱乐',
                            'language'=>$this->language,
                            'create_date'=>$this->datatime,
                            'is_top'=>$list_count?0:1,
                            'is_put'=>$this->is_put,
                        ];
                    }
                }
                unset($img);
            }
        }

        $newsListModel=new \Api\Model\Entertainment_listModel;
        $saveDates=$newsListModel->saveDates($list);
        if($saveDates){
            echo '娱乐数据自动获取ok!'.$this->datatime.PHP_EOL;
        }else{
            echo '娱乐数据自动获取失败!'.$this->datatime.PHP_EOL;
        }
    }
    
    /*
     * 添加汽车新闻
     */
    public function car(){
        $url='http://auto.sina.com.cn/';
        $tag='.mainbody:eq(0) div:eq(0) div[class="main fR"]:eq(0) div[class="focus-box clearfix"] div[class="headline fR"] ul[class="news"]:eq(0) li';
        $dir=$this->upload_dir.'Car/'.substr($this->datatime,0,strpos($this->datatime,' '));
        \phpQuery::newDocumentFile($url);
        $length=pq($tag)->length;
        $href=[];
        if($length>=1){
            for($i=0;$i<$length;$i++){
                $href[]=pq($tag.':eq('.$i.') a:eq(0)')->attr('href');
            }
        }
        $getcon=$this->getContent($href);
        $saveDates=false;
        $list=[];
        if($getcon){
            foreach($getcon as $key=>$val){
                $list_count=count($list);
                $img=$this->uploadImg($val['imgurl'],$dir);
                if($img){
                    $list[]=[
                        'tit_name'=>$val['title'],
                        'icon_image'=>$list_count?implode(',',$img):current($img),
                        'link_address'=>$val['link_address'],
                        'come_from'=>'新浪汽车',
                        'language'=>$this->language,
                        'create_date'=>$this->datatime,
                        'is_top'=>$list_count?0:1,
                        'is_put'=>$this->is_put,
                    ];
                }
            }
            unset($getcon);
        }
        $newsListModel=new \Api\Model\Car_listModel;
        $saveDates=$newsListModel->saveDates($list);
        if($saveDates){
            echo '汽车数据自动获取ok!'.$this->datatime.PHP_EOL;
        }else{
            echo '汽车数据自动获取失败!'.$this->datatime.PHP_EOL;
        }
    }
    
    /*
     * 添加视频新闻
     */
    public function video(){
        $url='http://video.sina.cn/';
        $tag='#__liveLayoutContainer div[class="-live-layout-container row-fuild"]:eq(0) .-live-page-widget:eq(1) .s_card_white ul[class="swiper-wrapper"] li';
        $dir=$this->upload_dir.'Video/'.substr($this->datatime,0,strpos($this->datatime,' '));
        \phpQuery::newDocumentFile($url);
        $length=pq($tag)->length;
        $list=[];
        if($length>=1){
            for($i=0;$i<$length;$i++){
                $icon_image=pq($tag.':eq('.$i.') a:eq(0) img:eq(0)')->attr('src');
                if(startWith($icon_image,'data:')){
                    continue;
                }else{
                    $list_count=count($list);
                    $icon_image=$this->uploadImg([$icon_image],$dir);
                    if($icon_image){
                        $link_address=pq($tag.':eq('.$i.') a:eq(0)')->attr('href');
                        $tit_name=
                        $list[]=[
                            'tit_name'=>pq($tag.':eq('.$i.') a:eq(0)')->attr('title'),
                            'icon_image'=>current($icon_image),
                            'link_address'=>pq($tag.':eq('.$i.') a:eq(0)')->attr('href'),
                            'time'=>'',
                            'come_from'=>'新浪视频',
                            'language'=>$this->language,
                            'create_date'=>$this->datatime,
                            'is_put'=>$this->is_put,
                        ];
                    }
                }
                
            }
        }
        $videoListModel=new \Api\Model\Video_listModel;
        $saveDates=$videoListModel->saveDates($list);
        if($saveDates){
            echo '视频数据自动获取ok!'.$this->datatime.PHP_EOL;
        }else{
            echo '视频数据自动获取失败!'.$this->datatime.PHP_EOL;
        }
    }
    
}

