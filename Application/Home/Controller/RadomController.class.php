<?php
/**
 *
 * 功能说明：前台控制器演示。
 *
 **/
namespace Home\Controller;

class RadmoController extends ComController
{
    public function index()
    {   
        //查询非故事题目数
        $quesCount = M('quest')->count();
        //查询故事题目数
        $questionCount = M('question')->group('story')->sum('contentcount');
        $count = $quesCount + $questionCount;
        $this->assign('count',$count);

        $videoDb = M('video');
        //查询课程视频
        $video['course'] = $videoDb->field('id,title')->where('groom=1')->order('addtime desc')->limit(4)->select();
        $video['groom'] = $videoDb->field('id,title')->where('groom=2')->order('addtime desc')->limit(4)->select();
        $this->assign('video',$video);

        //查询课程
        $course = M('tree')->where('pid=0')->select();
        $this->assign('course',$course);

        $this->display('index');
    }
}