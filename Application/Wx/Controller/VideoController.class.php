<?php


namespace Wx\Controller;


class VideoController extends ComController{
    //put your code here
     //热点视频
    public function index()
    {   
        //搜索关键字
        $wantsee= isset($_POST['wantsee']) ? $_POST['wantsee'] : '';

        $p = isset($_GET['p']) ? $_GET['p'] : 0;

        if($_GET['id']){
            $id=$_GET['id'];
            $map['qw_video.id']=$id;
            M('video')->where($map)->setInc('views', 1);
            //查看评论
            $comment = $this->getComment($id,$p);
            $this->assign('id',$id);
        }  else {
            //默认打开视频页面时
            $maxviews=M('video')->order('views desc')->limit(1)->find();
            $map['qw_video.id']=$maxviews['id'];
            $firstid=$map['qw_video.id'];
            $this->assign('id',$map['qw_video.id']);

        }
        $wheres['qw_video.title']=array('like','%'.$wantsee.'%');
        $hotvideo = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($wheres)->order('views desc')->limit(6)->select();
        $hotvideoid = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($map)->order('views desc')->find();

        $this->assign('hotvideo',$hotvideo);
        $this->assign('hotvideoid',$hotvideoid);
        $this->assign('searchwords',$searchwords);
        $this->assign('firstid',$firstid);
        $this->display();
    }
    public function online(){
        $wantsee= isset($_POST['wantsee']) ? $_POST['wantsee'] : '';
        $type= isset($_GET['type']) ? $_GET['type'] : '';
        $where['qw_video.title']=array('like','%'.$wantsee.'%');
        if($type==1){
               $video = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($where)->order('addtime desc')->select();
  
        }  elseif ($type==2) {
            
              $video = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($where)->order('views desc')->select();
        }  else {
            
        }
        
        //var_dump($newvideo);die;
        $this->assign('video',$video);
        $this->display();
    }
    public function play(){
        $vid= isset($_GET['vid']) ? $_GET['vid'] : '';
        $where['qw_video.id']=$vid;
        $videoid = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($where)->find();
        $this->assign('videoid',$videoid);
        $this->display();
    }
}
