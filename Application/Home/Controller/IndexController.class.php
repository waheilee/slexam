<?php
/**
 *
 * 功能说明：前台控制器演示。
 *
 **/
namespace Home\Controller;

use Vendor\Page;

class IndexController extends ComController
{
    public function index()
    {   
        $videoDb = M('video');
        //查询课程视频
        $video['course'] = $videoDb->field('id,title')->where('groom=1')->order('addtime desc')->limit(4)->select();
        $video['groom'] = $videoDb->field('id,title')->where('groom=2')->order('addtime desc')->limit(4)->select();
        $this->assign('video',$video);

        //查询课程数
        $treeDb = M('tree');
        $tree = $treeDb->field('id,name')->where('pid=0')->select();
        $other = A('Examin');
        foreach($tree as $k=>$v){
        	$count = $other->getCount($v['id']);
        	$data[$k]['name'] = $v['name'];
        	$data[$k]['id'] = $v['id'];
        	$data[$k]['point'] = $count['point'];
        	$data[$k]['quest'] = $count['quest'];
        }
        
        $this->assign('data',$data);

        $this->display();
    }


}