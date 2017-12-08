<?php
/**
 *
 * 功能说明：前台控制器演示。
 *
 **/
namespace Wx\Controller;
use Home\Controller\BandController;

class BandsController extends BandController
{
    public function index($p=1)
    {   
        if(IS_AJAX){
            $p = I('get.p');
        }

        if(I('get.type')){
            $where['qw_examin.type'] = I('get.type');
            $this->assign('type',I('get.type'));
        }

        if(I('get.difficulty')){
            $where['qw_examin.difficulty'] = I('get.difficulty');
            $this->assign('difficulty',I('get.difficulty'));
        }

        if(I('get.style') == 2){
            $where['qw_examin.difficulty'] = 3;
            
        }elseif(I('get.style') ==3){
            $order['qw_examin.createtime'] = 'desc';

        }else{
            $order['qw_examin.usenum'] = 'desc';
        }

       //获取打榜试卷
        $scoreDb = M('score');
        $examinDb = M('examin');

        $where['qw_score.type'] = 2;

        $count = $scoreDb->field('exmainid')->join("qw_examin ON qw_examin.id=qw_score.exmainid",'LEFT')->where($where)->group('exmainid')->select();

        $order['qw_score.score'] = 'desc';
        $order['qw_score.sntime'] = 'asc';


        $band = $scoreDb->field('qw_examin.nickname,qw_examin.type,qw_examin.id,qw_examin.difficulty,qw_examin.usenum')->join('qw_examin ON qw_examin.id=qw_score.exmainid','LEFT')->where($where)->order($order)->page($p.',5')->group('qw_score.exmainid')->select();

        foreach($band as &$v){
            $res = $this->getAssess($v['id']);
            $v['star'] = $res['star'];
            $v['assess'] = $res['assess'];
            $v['score'] = $res['score'];
            $v['usetime'] = $res['usetime'];
        }


        if(IS_AJAX){
            
            foreach($band as $v){
                if($v['type'] == 1){
                    $str .= '<li data="z">'; 
                }elseif($v['type'] == 2){
                    $str .= '<li data="m">'; 
                }else{
                    $str .= '<li data="d">'; 
                }
                $str .= '<div class="oe-topmenu star_'.$p.'"><div class="wrap"><div class="left">综合评星：<span class="startnum" data="'.$v['star'].'"></span></div><div class="right"><a href="javascript:void(0)" onclick="shopNav(\'dzkt\')">订制课堂</a><span>丨</span><a href="javascript:void(0)">分享试卷</a><span>丨</span><a href="javascript:void(0)" class="blue">查看打榜</a></div></div></div><div class="oe-middlename wrap"><div class="left"><img src="'.__ROOT__.'/Public/Wx/images/makelist/zdypaper.png" alt=""/><p class="oem-name">'.$v['nickname'].'</p></div><div class="left oe-num"><span>'.$v['score'].'分</span></div><div class="right"><a href="'.U('Answers/examin',array('code'=>base64_encode($v['id']))).'"><img src="'.__ROOT__.'/Public/Wx/images/yearoldexam/penanswer.png" alt=""/></a></div></div><div class="oe-bottom"><div class="wrap"><span>难易程度：'.checkType($v['difficulty']).'</span><span>答题人数：'.$v['usenum'].'人</span><span>最快用时：'.$v['usetime'].'</span></div></div></li>';
            }
            $this->ajaxReturn($str);
        }else{
            $this->assign('count',count($count));
            $this->assign('band',$band);
            $this->assign('style',I('get.style'));
            $this->display();
        }
    }

    public function getAssess($id)
    {   
        $order['score'] = 'desc';
        $order['sntime'] = 'asc';

        //查询试卷最高得分和用时
        $scoreDb = M('score');
        $score = $scoreDb->field('usetime,score')->where('exmainid='.$id)->order($order)->find();
        

        //查询试卷评价和得星
        $assessDb = M('assess');

        $star = $assessDb->where('e_id='.$id)->avg('star');
        $res = $assessDb->field('assess')->where('e_id='.$id)->select();

        foreach($res as $v){
            $assess = explode('__',$v['assess']);
            foreach($assess as $z){
                $data[$z] += 1;
            }
        }
        
        $result['star'] = ceil($star);
        $result['assess'] = max(array_flip($data));
        $result['score'] = $score['score'];
        $result['usetime'] = $score['usetime'];

        return $result;
    }
}