<?php
/**
 * Created by PhpStorm.
 * User: 华阳
 * Date: 2017/11/13
 * Time: 10:55
 */

namespace Home\Controller;


class VideoController extends ComController
{
    //热点视频
    public function index()
    {   
        //搜索关键字
        $wantsee= isset($_POST['wantsee']) ? $_POST['wantsee'] : '';
     if($wantsee){
        $data['searchtime']=time();
        $data['wantsee']=$wantsee;
        $data['people']=1;   //session
        $data['views']=1;     //搜索次数
        $data['type']=1;
        //var_dump($data);die;
        $where['wantsee']=$wantsee;
        $where['type']=1;
        $want=M('wantsee')->where($where)->find();
        if($want){
            M('wantsee')->where($where)->setInc('views',1); // 搜索次数加1
        }else{
            M('wantsee')->data($data)->add();
        }
     }
        $searchwords=M('wantsee')->where('type=1')->limit(3)->order('views desc')->select();

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

            //查看评论
            $comment = $this->getComment($maxviews['id'],$p);
        }
        $wheres['qw_video.title']=array('like','%'.$wantsee.'%');
        $hotvideo = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($wheres)->order('views desc')->limit(6)->select();
        $hotvideoid = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($map)->order('views desc')->find();

        $this->assign('hotvideo',$hotvideo);
        $this->assign('hotvideoid',$hotvideoid);
        $this->assign('wantsee',$wantsee);
        $this->assign('searchwords',$searchwords);
        $this->assign('firstid',$firstid);
        $this->assign('comment',$comment);

        //查询最新的视频
        $new = $this->getNew();
        $this->assign('new',$new);

        $this->display();
    }

    //查询最新上传
    protected function getNew()
    {
        $videoDb = M('video');
        $video = $videoDb->field('id,title,thumb,url,addtime,views')->order('addtime desc')->limit(3)->select();
        return $video;
    } 

    // 发表评论
    public function addComment()
    {   
        $answer = A('Answer');

        $commentDb = M('comment');
        $data['v_id'] = I('post.id');
        $data['u_id'] = $answer->getUserId();
        $data['comment'] = I('post.text');
        $data['time'] = time();

        $res = $commentDb->add($data);

        if($res){
            $user = M('users')->field('username,head')->where('id='.$answer->getUserId())->find();
            $this->ajaxReturn($user);
        }else{  
            $this->ajaxReturn('false');
        }
    }

    //获取评论
    public function getComment($id,$p)
    {   
        $commentDb = M('comment');
        if(IS_AJAX){
          $p = I('post.p');
          $id = I('post.id');
        }else{
          //获取总数和
          $count = $commentDb->where('qw_comment.v_id='.$id)->count();
          $this->assign('count',$count);
        }

        //关联查询评论和用户信息
        $res = $commentDb->field('qw_comment.comment,qw_comment.time,qw_users.head,qw_users.username')->join('qw_users ON qw_comment.u_id=qw_users.id')->where('qw_comment.v_id='.$id)->order('qw_comment.time desc')->page($p.',5')->select();

        if(IS_AJAX){
          foreach($res as $v){
            $str .= '<li><div class="left img"><img src="'.__ROOT__.$v['head'].'" alt="" /></div><div class="left text"><p><span class="name">'.$v['username'].'</span><span class="time">'.date('Y-m-d',$v['time']).'</span></p><p>'.$v['comment'].'</p></div><div class="clearfix"></div></li>';
          }

          $this->ajaxReturn($str);
        }else{
          return $res;
        }

        
    }

    //精品视频
    public function boutique()
    {   
         //搜索关键字
        $wantsee= isset($_POST['wantsee']) ? $_POST['wantsee'] : '';
     if($wantsee){
        $data['searchtime']=time();
        $data['wantsee']=$wantsee;
        $data['people']=1;   //session
        $data['views']=1;     //搜索次数
        $data['type']=2; 
        //var_dump($data);die;
        $where['wantsee']=$wantsee;
        $where['type']=2;
        $want=M('wantsee')->where($where)->find();
        if($want){
            M('wantsee')->where($where)->setInc('views',1); // 搜索次数加1
        }else{
            M('wantsee')->data($data)->add();
        }
     }
        $searchwords=M('wantsee')->where('type=2')->limit(3)->order('views desc')->select();
        
        if($_GET){
            $id=$_GET['id'];
            $map['qw_video.id']=$id;
            M('video')->where($map)->setInc('views', 1);
             //查看评论
            $comment = $this->getComment($id,$p);
            $this->assign('id',$id);
        }  else {
            //默认打开视频页面时
             $maxviews=M('video')->where('groom=2')->limit(1)->order('views desc')->find();
              $map['qw_video.id']=$maxviews['id'];
              $firstid=$map['qw_video.id'];
              
               //查看评论
            $comment = $this->getComment($maxviews['id'],$p);
        }
        $wheres['qw_video.title']=array('like','%'.$wantsee.'%');
        $wheres['groom']=2;    //推荐视频
        $hotvideo = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($wheres)->order($order)->limit(6)->select();
        $hotvideoid = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($map)->order($order)->find();
        //var_dump($hotvideoid);die;
        $this->assign('hotvideo',$hotvideo);
        $this->assign('hotvideoid',$hotvideoid);
        $this->assign('wantsee',$wantsee);
        $this->assign('searchwords',$searchwords);
         $this->assign('firstid',$firstid);
         $this->assign('comment',$comment);
          //查询最新的视频
        $new = $this->getNew();
        $this->assign('new',$new);
        $this->display();
    }

    //视频课堂
    public function videoClass()
    {   
         //搜索关键字
        $wantsee= isset($_POST['wantsee']) ? $_POST['wantsee'] : '';
     if($wantsee){
        $data['searchtime']=time();
        $data['wantsee']=$wantsee;
        $data['people']=1;   //session
        $data['views']=1;     //搜索次数
        $data['type']=3;
        //var_dump($data);die;
        $where['wantsee']=$wantsee;
        $where['type']=3;
        $want=M('wantsee')->where($where)->find();
        if($want){
            M('wantsee')->where($where)->setInc('views',1); // 搜索次数加1
        }else{
            M('wantsee')->data($data)->add();
        }
     }
        $searchwords=M('wantsee')->where('type=3')->limit(3)->order('views desc')->select();
        
        if($_GET){
            $id=$_GET['id'];
            $map['qw_video.id']=$id;
            M('video')->where($map)->setInc('views', 1);
             //查看评论
            $comment = $this->getComment($id,$p);
            $this->assign('id',$id);
        } else {
            //默认打开视频页面时
             $maxviews=M('video')->where('rec=2')->limit(1)->order('views desc')->find();
              $map['qw_video.id']=$maxviews['id'];
              $firstid=$map['qw_video.id'];
              //查看评论
            $comment = $this->getComment($maxviews['id'],$p);
        }
        $wheres['qw_video.title']=array('like','%'.$wantsee.'%');
        $wheres['rec']=2;    //录制课堂视频
        $hotvideo = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($wheres)->order($order)->limit(6)->select();
        $hotvideoid = M('video')->field('qw_video.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_video.kid')->where($map)->order($order)->find();
        //var_dump($hotvideoid);die;
        $this->assign('hotvideo',$hotvideo);
        $this->assign('hotvideoid',$hotvideoid);
        $this->assign('wantsee',$wantsee);
        $this->assign('searchwords',$searchwords);
         $this->assign('firstid',$firstid);
          $this->assign('comment',$comment);
          //查询最新的视频
        $new = $this->getNew();
        $this->assign('new',$new);
        $this->display();
    }

    //定制视频
    public function madeVideo()
    {   
         $typeid= isset($_GET['typeid']) ? $_GET['typeid'] : '';
         
         //获取定制视频
         if($typeid==1){      //章  
//           $map['zid']=array('neq','');
             $map['jid']=array('eq','');
             $map['pay']=1;
             $map['handnum']=array('lt',5);
            $orderlist = M('orderlist')->field('qw_orderlist.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_orderlist.zid')->where($map)->order($order)->select(); 
           
            }elseif ($typeid==2) {     //节
             $map['jid']=array('neq','');
             $map['did']=array('eq','');
             $map['pay']=1;
             $map['handnum']=array('lt',5);
            $orderlist = M('orderlist')->field('qw_orderlist.*,qw_tree.name')->join('qw_tree ON qw_tree.id=qw_orderlist.jid')->where($map)->order($order)->select();
      
            }  elseif ($typeid==3) {      //知识点
            $map['did']=array('neq','');
            $map['pay']=1;
            $map['handnum']=array('lt',5);
            $orderlist = M('orderlist')->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
  
            }  else {       //全部
            $orderlist1 = M('orderlist')->field('qw_orderlist.*,qw_tree.name')->where('jid="" and pay=1 and handnum <5')->join('qw_tree ON qw_tree.id=qw_orderlist.zid')->order($order)->select();
            $orderlist2 = M('orderlist')->field('qw_orderlist.*,qw_tree.name')->where('jid!="" and did="" and pay=1 and handnum <5')->join('qw_tree ON qw_tree.id=qw_orderlist.jid')->order($order)->select();
            $orderlist3 = M('orderlist')->where('did !="" and pay=1 and handnum <5')->order($order)->select();
            $orderlist = array_merge($orderlist1, $orderlist2, $orderlist3); 
            }
        //分页
        $count = count($orderlist);
        $Page = new \Think\Page($count,4);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);
        $orderlist=array_slice($orderlist,$Page->firstRow,$Page->listRows);
       
        foreach ($orderlist as $k => &$v) {
            if ($v['jid']== 0 && $v['did']=='') {
                $v['shi'] = 'z';
            }elseif ($v['jid']!= '' && $v['did']=='') {
                $v['shi'] = 'j';
            }  else {
                 $v['shi'] = 'zsd';
            }
        }
    //var_dump($orderlist);die;
        $province = M('tree')->where(array('pid' => 0))->select();
        $this->assign('province', $province);
        $this->assign('orderlist', $orderlist);

        $this->display();
    }
    //定制视频课堂
    public function orderpay(){
        $data['people']= 1;  //session
        $data['ordertime']= time();  //session
        $data['ordername']= isset($_POST['name']) ? $_POST['name'] : '';
        $data['mobile']= isset($_POST['mobile']) ? $_POST['mobile'] : '';
        $data['email']= isset($_POST['email']) ? $_POST['email'] : '';
        $data['kid']= isset($_POST['course']) ? $_POST['course'] : '';
        $course=M('tree')->where('id='.$data['kid'])->find();
        $data['course']=$course['name'];
        $data['zid']= isset($_POST['chapter']) ? $_POST['chapter'] : '';
        $data['jid']= isset($_POST['point']) ? $_POST['point'] : '';
        $data['did']= isset($_POST['know']) ? $_POST['know'] : '';
        $data['remark']= isset($_POST['remark']) ? $_POST['remark'] : '';
        $data['pay']= isset($_POST['pay']) ? $_POST['pay'] : '';
        if($data['jid']=='请选择'){
          $data['jid']='';  
        }
        if($data['zid']=='请选择'){
          $data['zid']='';  
        }
        //定制视频编码生成规则：年月日时分秒课程id章id节id三位随机数
        if( strlen($data['kid'])==1){
           $kid='00'.$data['kid']; 
        }  elseif(strlen($data['kid'])==2) {
           $kid='0'.$data['kid']; 
        }
        if( strlen($data['zid'])==1){
           $zid='00'.$data['zid']; 
        }  elseif(strlen($data['zid'])==2) {
           $zid='0'.$data['zid']; 
        }  else {
             $zid='000'; 
        }
        if( strlen($data['jid'])==1){
           $jid='00'.$data['jid']; 
        }  elseif(strlen($data['jid'])==2) {
           $jid='0'.$data['jid']; 
        }  else {
             $jid='000'; 
        }
        $data['ordernum']= date("YmdHis").$kid. $zid.$jid.rand(100, 999);
       //var_dump($data['ordernum']);die;
        if($data['pay']==1){    //微信支付还是自己支付,
              $data['handnum']= 1;
          } else {
              $data['handnum']= 5;
         }
         
         //判断扣多少钱然后走支付流程
         
         
         $orderpay=M('orderlist')->data($data)->add();
         if($orderpay){
             $this->success('定制课程支付成功！');
         }else {
             $this->error('定制课程支付失败！');
          }
        
    }
    //参与定制
    public function order(){
        if($_GET){
             $id= isset($_GET['id']) ? $_GET['id'] : '';
             if($id){
                 $ordernum=M('orderlist')->where('id='.$id)->find();   //判断众筹是否超过五次
                 if($ordernum['handnum']<5){
                     
                      //微信支付流程
                      //如果支付成功
                     //记录参与信息
                     $data['peopleid']=1;     //session获取
                     $data['orderid']=$id;
                     $data['pordertime']=  time();
                 $handlist=M('handlist')->data($data)->add();
                  //严格一点这里还可以查一下这个定制视频是否超了5个了   
                 $order=M('orderlist')->where('id='.$id)->setInc('handnum',1);
                 if($order){
                     $this->success('参与定制成功！');
                 }  else {
                     $this->error('参与定制失败');
                    }
                    
               }else {
                      $this->error('参与人数已够，定制失败');
                 }

             }
        }
        
    }
    //三级联动
    public function getRegion() {
        $Region = M("tree");
        $str = '<option>请选择</option>';
        if(I('post.type') == 'province'){
            $map['pid'] = 0;
        }elseif(I('post.id') == 0){
            $this->ajaxReturn($str);
        }else{
          $map['pid'] = I('post.id');  
        }
        $list = $Region->where($map)->select();
        
            if($list[0]['type']==4){        //判断最后一级知识点
                 $list = explode('，',$list[0]['name']);
                 foreach($list as $v){
                if($v == I('post.check')){
                    $str .= '<option selected value="'.$v.'">'.$v.'</option>';
                }else{
                    $str .= '<option value="'.$v.'">'.$v.'</option>';
                }     
          }
      }else{
        foreach($list as $v){
            if($v['id'] == I('post.check')){
                $str .= '<option selected value="'.$v['id'].'">'.$v['name'].'</option>';
            }else{
                $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
            }     
        }
      }
        $this->ajaxReturn($str);
    }
}