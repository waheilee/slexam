<?php


namespace Admin\Controller;


class OrderController extends ComController{
    //put your code here
    public function index(){
         $map['type'] = 1;
        $course = M('tree')->where($map)->select();
        //var_dump($course);die;
        $gep['type'] = 2;
        $chapter = M('tree')->where($gep)->select();
        $sop['type'] = 3;
        $point = M('tree')->where($sop)->select();
        
        $where['handnum']=5;
        $vcr=M('orderlist')->where($where)->select();
        $this->assign('vcr',$vcr);
        $this->assign('course', $course);
        $this->assign('chapter', $chapter);
        $this->assign('point', $point);
        $this->display();
    }
    public function begin(){
        
        $map['type'] = 1;
        $course = M('tree')->where($map)->select();
        //var_dump($course);die;
        $gep['type'] = 2;
        $chapter = M('tree')->where($gep)->select();
        $sop['type'] = 3;
        $point = M('tree')->where($sop)->select();
        $begin=M('orderlist')->select();
        
        $this->assign('course', $course);
        $this->assign('chapter', $chapter);
        $this->assign('point', $point);
        $this->assign('begin', $begin);
        $this->display();
    }

    public function orderlist(){
        
        $map['type'] = 1;
        $course = M('tree')->where($map)->select();
        $gep['type'] = 2;
        $chapter = M('tree')->where($gep)->select();
        $sop['type'] = 3;
        $point = M('tree')->where($sop)->select();
        //$handlist=M('handlist')->field('qw_handlist.*')->join('qw_handlist ON qw_users.id = handlist.peopleid ')->select();    //见鬼了
        $handlist = M('handlist')->table('qw_handlist a, qw_users b,qw_orderlist c')->where('a.peopleid = b.id and a.orderid =c.id')->field('a.*,b.username,c.*')->order('a.id desc' )->select();
        //var_dump($handlist);die;
        $this->assign('handlist',$handlist);
        $this->assign('course', $course);
        $this->assign('chapter', $chapter);
        $this->assign('point', $point);
        $this->display();
    }
     public function status()
    {

        $id = I('id');
        $orderid=I('orderid');
        if (!$id&&!$orderid) {
            $this->error('参数错误！');
        }
        if ($id == 0) {
            $data['status']=1;
            $orderlist = M('orderlist')->where('id=' . $orderid)->save($data);
            if($orderlist){
                $this->success('更改状态成功');
            }  else {
                $this->error('更改状态失败');
            }
        }
        if ($id == 1) {
            $data['status']=2;
            $orderlist = M('orderlist')->where('id=' . $orderid)->save($data);
            if($orderlist){
                $this->success('更改状态成功');
            }  else {
                $this->error('更改状态失败');
            }
        }
        if ($id == 2) {
            $data['status']=1;
            $orderlist = M('orderlist')->where('id=' . $orderid)->save($data);
            if($orderlist){
                $this->success('更改状态成功');
            }  else {
                $this->error('更改状态失败');
            }
        }
    }
    public function del() {

        $aids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        if (!$aids) {
            $this->error('参数错误！未勾选');
        }

        $map['id'] = array('in', $aids);
        if (M('orderlist')->where($map)->delete()) {
            addlog('删除预定视频ID：' . $aids);
            $this->success('恭喜，预定视频删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }
    public function delorder() {

        $aids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        if (!$aids) {
            $this->error('参数错误！未勾选');
        }

        $map['id'] = array('in', $aids);
        if (M('orderlist')->where($map)->delete()) {
            addlog('删除预定视频记录ID：' . $aids);
            $this->success('恭喜，预定视频记录删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }
    public function delbegin() {

        $aids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        if (!$aids) {
            $this->error('参数错误！未勾选');
        }

        $map['id'] = array('in', $aids);
        if (M('orderlist')->where($map)->delete()) {
            addlog('删除发起定制视频记录ID：' . $aids);
            $this->success('恭喜，发起定制视频记录删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }
}
