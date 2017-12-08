<?php
/**
 *
 * 功能说明：前台控制器演示。
 *
 **/
namespace Wx\Controller;
use Think\Controller;

class RandomController extends Controller
{
    public function lists($p=0)
    {   
        //获取科目
        $treeDb = M('tree');
        $tree = $treeDb->field('id,name')->where('pid=0')->select();
        $this->assign('tree',$tree);

        //查询自定义试卷
        $examinDb = M('examin');
        $where['qw_examin.type'] = 3;

        $count = $examinDb->where($where)->count();
        $this->assign('count',$count);

        $examin = $examinDb->field('qw_examin.id,qw_examin.difficulty,qw_examin.nickname,qw_examin.usenum,max(qw_score.score) as score,min(qw_score.usetime) as usetime,avg(qw_assess.star) as star')->join('qw_score ON qw_examin.id=qw_score.exmainid','LEFT')->join('qw_assess ON qw_assess.e_id=qw_examin.id','LEFT')->where($where)->order($order)->page($p.',5')->group('qw_score.exmainid')->select();

        $this->assign('examin',$examin);
        $this->display();
    }

    public function random()
    {   
        //获取科目
        $tree = $this->getObj();
        $this->assign('tree',$tree);

        $this->display();
    }

    // 查询科目
    public function getObj()
    {
        $treeDb = M('tree');

        $where['pid'] = 0;
        if(I('post.id')){
            $where['pid'] = I('post.id');
        }

        //查询当前val同父类的个数
        $arr['id'] = array('in',I('post.val'));
        $res = $treeDb->field('pid')->where($arr)->select();
        
        foreach($res as $v){
            $result[$v['pid']] += 1;
        }

        // 查询科目
        $i = 0;
        if(I('post.type') == 'zsd'){
            $trees = $treeDb->field('id,name,pid')->where($where)->find();

            $tree = explode('，',$trees['name']);
            foreach($tree as $v){
                $data[$i]['name'] = $v;
                $data[$i]['pid'] = $trees['pid'];
                $i++;
            }

        }else{
            $tree = $treeDb->field('id,name,pid')->where($where)->select();
            foreach($tree as $v){
                $data[$i]['name'] = $v['name'];
                $data[$i]['id'] = $v['id'];
                $data[$i]['pid'] = $v['pid'];
                $data[$i]['num'] = isset($result[$v['id']]) ? $result[$v['id']] : 0;

                $i++;
            }
        }



        if(IS_AJAX){
            $this->ajaxReturn(json_encode($data));
        }else{
            return json_encode($data);
        }
    }

    public function getAssess($id)
    {
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

        return $result;
    }
}