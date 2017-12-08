<?php
/**
 *
 * 功能说明：前台控制器演示。
 *
 **/
namespace Home\Controller;

class ExaminController extends ComController
{
    public function index()
    {   
        
        $examinDb = M('examin');
        $questDb = M('quest');
        $scoreDb = M('score');
        $treeDb = M('tree');

        //获取课程名
        $id = base64_decode(I('get.code'));
        $name = $treeDb->where('id='.$id)->getField('name');
        $this->assign('name',$name);

        $eWhere['kid'] = array('like','%,'.$id.',%');


        $count      = $examinDb->where($eWhere)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,30);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show       = $Page->show();// 分页显示输出
        $this->assign('page',$show);

        //获取该课程下试卷
        $examin = $examinDb->where($eWhere)->limit($Page->firstRow.','.$Page->listRows)->select();

        $examin = $this->getInfo($examin);
        
        $this->assign('examin',$examin);
        
        //获取课程和知识点个数
        $count = $this->getCount($id);
        $this->assign('p_count',$count['point']);
        $this->assign('q_count',$count['quest']);

        //相关的视频 定制 和历届打榜
        


        $this->display();
    }

    protected function getInfo($data)
    {   
        $questDb = M('quest');
        $scoreDb = M('score');

        foreach($data as &$v){
            // 查询难点个数
            $ids = $v['paper1'].$v['paper2'].$v['paper3'];
            $where['id'] = array('in',$ids);
            $where['difficulty'] = '难';
            $count = $questDb->where($where)->count();
            $v['count'] = $count;

            //查询改试卷下最短用时和最高得分
            $score = $scoreDb->field('score,usetime')->where('exmainid='.$v['id'])->order('score desc,sntime')->find();
            $v['score'] = $score['score'];
            $v['time'] = $score['usetime'];
        }

        return $data;
    }

    public function getCount($id)
    {
        $treeDb = M('tree');
        $questDb = M('quest');
        //获取该课程下知识点个数
        $where['cid'] = $id;
        $where['zid'] = array('neq','');
        $res = $treeDb->field('name')->where($where)->select();
        foreach($res as $v){
            $point = explode('，',trim($v['name'],'，'));
            $count['point'] += count($point);
        }
        //获取该课程下题数
        $arr['kid'] = array('like','%'.$id.'%');
        $count['quest'] = $questDb->where($arr)->count();
        
        return $count;
    } 

}