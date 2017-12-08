<?php
/**
 *
 * 功能说明：前台控制器演示。
 *
 **/
namespace Home\Controller;

class BandController extends ComController
{
    public function index()
    {   
        //获取打榜试卷
        $scoreDb = M('score');
        $examinDb = M('examin');

        $where['qw_score.type'] = 2;

        $order['qw_score.score'] = 'desc';
        if(I('get.type') == base64_encode('难')){
            $where['qw_examin.difficulty'] = 3;
            $this->assign('type','难');
        }elseif(I('get.type') == base64_encode('新')){
            $order['qw_examin.createtime'] = 'desc';
            $this->assign('type','新');
        }else{
            $order['qw_examin.usenum'] = 'desc';
        } 
        $order['qw_score.sntime'] = 'asc';


        $count = $scoreDb->field('exmainid')->join("qw_examin ON qw_examin.id=qw_score.exmainid",'LEFT')->where($where)->group('exmainid')->select();

        $Page = new \Think\Page(count($count),10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);


        $band = $scoreDb->field('qw_examin.*,max(qw_score.score) as score,min(qw_score.usetime) as usetime')->join('qw_examin ON qw_examin.id=qw_score.exmainid','LEFT')->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->group('qw_score.exmainid')->select();

        foreach($band as &$v){
            $res = $this->getAssess($v['id']);
            $v['star'] = $res['star'];
            $v['assess'] = $res['assess'];
        }

        $this->assign('band',$band);
        $this->display();
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
        $userid = 1;

        //查询当前试卷的信息
        $id = base64_decode(I('get.code'));

        //查询试卷信息
        $examinDb = M('examin');
        $scoreDb = M('score');
        $assessDb = M('assess');
        $where['id'] = $id;

        $order = array('score'=>'desc','sntime'=>'asc');

        // 获取该试卷信息
        $one = $examinDb->field('id,nickname,difficulty,usenum,num')->where($where)->find();

        $arr['qw_score.exmainid'] = $id;
        $arr['qw_score.type'] = 2;
        //获取最高得分用时
        $score = $scoreDb->field('usetime,score')->where($arr)->order($order)->find();
        $one['usetime'] = $score['usetime'];
        $one['score'] = $score['score'];

        //获取改试卷平均星数
        $assess = $assessDb->where('e_id='.$id)->avg('star');
        $one['star'] = ceil($assess);

        $this->assign('one',$one);

        $count = $scoreDb->where($arr)->count();

        //手机ajax请求
        if(IS_AJAX){
            $p = $_GET['p'];
            //查询所有答题信息
            $examin = $scoreDb->field('qw_score.score,qw_score.usetime,qw_score.id,qw_score.laud_info,qw_score.error_num,qw_score.laud,qw_users.username,qw_users.head')->join('qw_users ON qw_users.id=qw_score.people','LEFT')->where('qw_score.exmainid='.$id)->order(array('qw_score.score'=>'desc','qw_score.sntime'=>'asc'))->page($p.',10')->select();

            foreach($examin as $v){
                $str .= '<div class="dbph-box"><div class="wrap"><div class="bor dbph-one left"><img src="'.$v['head'].'" alt=""/>'.$v['username'].'</div><div class="bor dbph-two left">'.$v['score'].'分</div><div class="bor dbph-three left">'.$v['usetime'].'</div><div class="bor dbph-four left">'.getBfb($one['num'],$v['error_num']).'%</div><div class="dbph-five left"><a href="javascript:void(0)" class="mr" data="yes" onclick="giveZz(this,'.$v['id'].')">';

                if(checkLaud($v['laud_info'],$userid) == 'yes'){
                    $str .= '<img src="'.__ROOT__.'/Public/Home/images/makelist/z.png" alt=""/>点赞';
                }else{
                    $str .= '<img src="'.__ROOT__.'/Public/Home/images/makelist/zed.png" alt=""/>已赞';
                }

                $str .= '</a></div></div>';

                $this->ajaxReturn($str);
            }
        }else{
            $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');
            $show = $Page->show();// 分页显示输出
            $this->assign('page',$show);

            //查询所有答题信息
            $examin = $scoreDb->field('qw_score.score,qw_score.usetime,qw_score.id,qw_score.laud_info,qw_score.error_num,qw_score.laud,qw_users.username,qw_users.head')->join('qw_users ON qw_users.id=qw_score.people','LEFT')->where($arr)->order(array('qw_score.score'=>'desc','qw_score.sntime'=>'asc'))->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('count',$count);
            $this->assign('exmain',$examin);
            $this->assign('userid',$userid);
            $this->display();
        }
    }

    //点赞
    public function laud()
    {   
        
        $scoreDb = M('score');
        $userid = 1;
        $id = I('post.id');
        $info = $scoreDb->field('laud_info,laud')->where('id='.$id)->find();

        if(checkLaud($info['laud_info'],$userid) == 'yes'){
            $data['id'] = $id;
            $data['laud'] = $info['laud'] + 1;
            $data['laud_info'] = rtrim($info['laud_info'],',').','.$userid.',';

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