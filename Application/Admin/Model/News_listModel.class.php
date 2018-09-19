<?php
namespace Admin\Model;
class News_listModel extends DefaultInitModel{
    public $uploadDirPre='./'; //资源物理路径前缀
    public $upload_pre=__ROOT__; //资源站点路径前缀

    public function addlist($data){
        $res=false;
        $res=$this->add($data);
        return $res;
    }

    public function getNavList(){
        $nav = $this->table('tb_news_navi')->select();
        return $nav;
    }

    public function getList($pageNo,$pageSize,$arrId=array(),$is_put='1'){
        if(empty($arrId)){
            $res=$this->where('is_put='.$is_put)->page($pageNo.','.$pageSize)->order('id desc')->select();
        }else{
            $res=$this->where('id in('.implode(',',$arrId).') and is_put='.$is_put)->page($pageNo.','.$pageSize)->order('id desc')->select();
        }
        
        $newsNaviObj=new News_naviModel;
        if($res){
            foreach($res as $key=>$val){
                $res[$key]['icon_image']= explode(',', $val['icon_image']);
                foreach($res[$key]['icon_image'] as $k=>$v){
                    $res[$key]['icon_image'][$k]=$this->upload_pre.'/'.$v;
                }
                $typeName=$newsNaviObj->field('name')->where('id='.$res[$key]['news_nav_id'])->find()['name'];
                $res[$key]['nav_name'] = $typeName ? $typeName : L('NoType');
            }
        }
        return $res;
    }
    
    public function delById($arrayId,$act=''){
        if(empty($arrayId)){
            return false;
        }else{
            if(is_array($arrayId)){
                $id=implode(',', $arrayId);
            }else{
                $id = $arrayId;
            }
        }

        $img=$this->field('icon_image')->where('id in('.$id.')')->select();
        $res=$this->delete($id);
        if($res){
            //删除资源图片
            foreach($img as $key=>$val){
                $img[$key]['icon_image']=explode(',',$val['icon_image']);
            }
            foreach($img as $val){
                foreach($val['icon_image'] as $v){
                    @unlink($this->uploadDirPre.$v);
                }
            }
            $act=strtolower($act);
            if($act=='impotaddlist'){
                $ses=array_diff(session('newsAddId'), explode(',', $id));
                if(count($ses)>0){
                    session('newsAddId',$ses);
                }else{
                    session('newsAddId',null);
                }
            }
        }
        return $res;
    }
    
}
