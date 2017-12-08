<?php
/**
 *
 * 功能说明：前台控制器演示。
 *
 **/
namespace Wx\Controller;
use Think\Controller;

class AnalogController extends Controller
{
    public function index()
    {   
        //查询模拟试卷
        $examinDb = M('examin');
        $order['qw_score.sntime'] = 'asc';
        $order['qw_examin.createtime'] = 'desc';

        $where['qw_examin.type'] = 2;

        if(I('get.range')){
            $range = I('get.range');
        }else{
            $range = 1;
        }

        $where['qw_examin.style'] = $range;

        //计算总数
        $count = $this->getCount();
        $this->assign('count',$count);

        $examin = $examinDb->field('qw_examin.*,max(qw_score.score) as score,min(qw_score.usetime) as usetime,avg(qw_assess.star) as star')->join('qw_score ON qw_examin.id=qw_score.exmainid','LEFT')->join('qw_assess ON qw_assess.e_id=qw_examin.id','LEFT')->where($where)->order($order)->page($p.',5')->group('qw_score.exmainid')->select();
        $this->assign('examin',$examin);
        
        $this->display();
    }

    protected function getCount($arr='')
    {
        $examinDb = M('examin');

        $where['qw_examin.type'] = 2;

        if($arr){
            $where['style'] = $arr;
            $count = $examinDb->where($where)->count();
            $res = $count;  
        }else{
            //卷一总数
            $where['style'] = 1;
            $count = $examinDb->where($where)->count();
            $res['Cone'] = $count;

            //卷二总数
            $where['style'] = 2;
            $count = $examinDb->where($where)->count();
            $res['Ctwo'] = $count;

            //卷三总数
            $where['style'] = 3;
            $count = $examinDb->where($where)->count();
            $res['Cthree'] = $count;
        }
        return $res;
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

    public function info()
    {   
        //查询当前试卷的信息
        $id = base64_decode(I('get.code'));

        //查询试卷信息
        $examinDb = M('examin');
        $scoreDb = M('score');
        $assessDb = M('assess');
        $where['id'] = $id;

        $order = array('score'=>'desc','sntime'=>'asc');

        // 获取该试卷信息
        $one = $examinDb->field('nickname,difficulty,usenum,num')->where($where)->find();

        //获取最高得分用时
        $score = $scoreDb->field('usetime,score')->where('exmainid='.$id)->order($order)->find();
        $one['usetime'] = $score['usetime'];

        //获取改试卷平均星数
        $assess = $assessDb->where('e_id='.$id)->avg('star');
        $one['star'] = ceil($assess);

        $this->assign('one',$one);

        $count = $scoreDb->where('exmainid='.$id)->count();

        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);

        //查询所有答题信息
        $examin = $scoreDb->field('qw_score.score,qw_score.usetime,qw_score.id,qw_score.laud_info,qw_score.error_num,qw_score.laud,qw_users.username,qw_users.head')->join('qw_users ON qw_users.id=qw_score.people','LEFT')->where('qw_score.exmainid='.$id)->order(array('qw_score.score'=>'desc','qw_score.sntime'=>'asc'))->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('exmain',$examin);
        $this->assign('userid',A('Answer/getUserid'));
        $this->display();
    }

    //点赞
    public function laud()
    {   
        cookie('userid',3);
        $scoreDb = M('score');

        $id = I('post.id');
        $info = $scoreDb->field('laud_info,laud')->where('id='.$id)->find();

        if(checkLaud($info['laud_info'],cookie('userid')) == 'yes'){
            $data['id'] = $id;
            $data['laud'] = $info['laud'] + 1;
            $data['laud_info'] = rtrim($info['laud_info'],',').','.cookie('userid').',';

            $res = $scoreDb->save($data);
            if($res){
                $this->ajaxReturn('true');
            }else{
                $this->ajaxReturn('false');
            }
        }else{
            $this->ajaxReturn('false');
        }

    }
}