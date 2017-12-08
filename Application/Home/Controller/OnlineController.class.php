<?php
/**
 * Created by PhpStorm.
 * User: 华阳
 * Date: 2017/11/14
 * Time: 14:46
 */

namespace Home\Controller;


class OnlineController extends ComController
{
    public function index()
    {
        $where['id']=1;     //session
        $user=M('users')->where($where)->find();
        $province = M('areas')->where(array('parent_id' => 1))->select();
        $list = $this->listMsg();//站内消息
        $questions = $this->questions();
        $this->assign('course', $questions);
        $this->assign('list',$list);
        $this->assign('province', $province);
        $this->assign('user', $user);
        $this->display();
    }


    //省市级三级联动
    public function getRegion()
    {
        $Region = M("areas");
        $str = '<option>请选择</option>';
        if(I('post.type') == 'province'){
            $map['parent_id'] = 1;
        }elseif(I('post.id') == 1){
            $this->ajaxReturn($str);
        }else{
            $map['parent_id'] = I('post.id');
        }
        $list = $Region->where($map)->select();

        foreach($list as $v){
            if($v['area_id'] == I('post.check')){
                $str .= '<option selected value="'.$v['area_id'].'">'.$v['area_name'].'</option>';
            }else{
                $str .= '<option value="'.$v['area_id'].'">'.$v['area_name'].'</option>';
            }
        }
        $this->ajaxReturn($str);
    }
    //返回查询相关用户
    public function postRegion()
    {
//        dump(I('post.id'));die;
        $id = I('post.id');
        $area = M('areas')->where('area_id='.$id)->find();
        switch ($area){
            case $area['area_type'] == 1:
                $map['province'] = $id;
                break;
            case $area['area_type'] == 2:
                $map['city'] = $id;
                break;
        }
        $user = M('users')->field('id,username,head')->where($map)->select();
        $this->ajaxReturn($user);
    }

    //查询一周榜单用户
    public function week()
    {
        $s = $_GET['s'];
        $M =M('score');
        switch ($s){
            case $s == 1:
                $map['u_id'] = array(array('egt',strtotime("-1 week ")),array('elt',time())) ;//条件大于等于一周前的时间，小于等于当前时间
                break;
            case $s == 2:
                $map['u_id'] = array(array('egt',strtotime("-1 mouth ")),array('elt',time())) ;//条件大于等于一周前的时间，小于等于当前时间
                break;
            case $s == 3:
                $this->avg();
        }

        $str = $M->distinct(true)->field('people')->where($map)->order('score')->select();//查询出本周时间内所有参与考试的用户，且去除重复id，按成绩排序
        $a='';
        //将二维数组转换为字符串
        foreach ($str as $k => $v){
            $a.=$v['people'].',';
        }
        $a=substr($a,0,strlen($a)-1);
//        dump($str);die;
        if ($s ==1 || $s == 2){
            $where['id']  = array('in',$a);//拿到本周考试用户id，去用户表里查询出对应的用户
            $res = M('users')->field('id,username,head')->where($map)->select();

        }else{
            $res = M('users')->field('id,username,head')->order('avg desc')->select();
        }
        $this->ajaxReturn($res);
    }

    //获取用户大榜平均成绩
    public function avg()
    {
        $user = M('users')->field('id')->select();//获取到用户id
        //循环用户id
        foreach ($user as $u){

            $score =M('score')->where('people='.$u['id'])->avg('score');//使用用户id查询打榜成绩表里的平均成绩
            $data['avg'] = $score;
            M('users')->data($data)->where('id=' . $u['id'])->save();//将获取的到的平均成绩存入用户表里
        }
    }
    
    //用户发送消息
    public function message()
    {

        $map['senderid']   = I('post.sid');
        $map['receiverid'] = I('post.rid');
        $map['content']    = I('post.text');
        $map['sendtime']   = time();
        $map['status']     = 1;
        M('message')->data($map)->add();
//        dump($map);
    }
    //站内消息
    public function listMsg()
    {
        $map = 1;//模拟获取当前用户id
        $where = 'a.senderid=b.id and a.receiverid='.$map;
        $list=M()->table('qw_message a,qw_users b')
                 ->where($where)
                 ->field('b.username,b.head,a.*')
                 ->order('a.sendtime desc' )
                 ->select();
        return $list;
    }
    public function questions() {
        $map['pid'] = 0;
        $map['type'] = 1;
        $course = M('tree')->where($map)->select();
        return $course;
        //var_dump($course);die;

    }
}