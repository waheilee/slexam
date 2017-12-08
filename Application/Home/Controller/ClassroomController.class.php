<?php


namespace Home\Controller;


class ClassroomController extends ComController{
    //put your code here
    public function index(){
        
        
    }
    //课程收藏
    public function collect()
    {
        $this->display();
    }

    //课程定制
    public function customizationa()
    {   
        $peopleid=1;       //session
        $typeid= isset($_GET['typeid']) ? $_GET['typeid'] : '';
        
        //已经定制完成的
        if($typeid==1){ //全部 
        $custom1 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('a.orderid = b.id and b.zid=c.id and b.jid="" and b.handnum=5 and a.peopleid='.$peopleid.' and b.status=2')->order('a.id desc' )->select();
        $custom2 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('a.orderid = b.id and b.jid=c.id and b.jid!="" and b.did="" and b.handnum=5 and a.peopleid='.$peopleid.' and b.status=2')->order('a.id desc' )->select();
        $custom3 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('a.orderid = b.id and b.did !="" and b.handnum=5 and a.peopleid='.$peopleid.' and b.status=2')->order('a.id desc' )->select();
       
        $custom1=array_merge($custom1,$custom2,$custom3);
        $custom4 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.zid=b.id and a.jid=0 and a.people='.$peopleid.' and a.status =2 and a.handnum=5 and a.pay=2')->select();
        $custom5 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.jid=b.id and a.jid!=0 and a.did="" and a.people='.$peopleid.' and a.status =2 and a.handnum=5 and a.pay=2')->select();
        $custom6 = M('orderlist')->where('did!="" and people='.$peopleid.' and status =2 and handnum=5 and pay=2')->order('id desc' )->select();
        
        $custom2=array_merge($custom4,$custom5,$custom6);
        
        }
        if($typeid==2){   //章
        $custom1 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('a.orderid = b.id and b.zid=c.id and b.jid=0 and b.handnum=5 and a.peopleid='.$peopleid.' and b.status=2')->order('a.id desc' )->select();
        $custom2 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.zid=b.id and a.jid=0 and a.people='.$peopleid.' and a.status =2 and a.handnum=5 and a.pay=2')->order('id desc' )->select();
       
        }
        if($typeid==3){   //节
        $custom1 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('a.orderid = b.id and b.jid=c.id and b.did="" and b.jid!=0 and b.handnum=5 and a.peopleid='.$peopleid.' and b.status=2')->order('a.id desc' )->select();
        $custom2 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.jid=b.id and a.jid!=0 and a.did="" and a.people='.$peopleid.' and a.status =2 and a.handnum=5 and a.pay=2')->order('id desc' )->select();
       
        }
        if($typeid==4){   //知识点
        $custom1 = M('handlist')->table('qw_handlist a, qw_orderlist b')->where('a.orderid = b.id and b.did!="" and b.handnum=5 and a.peopleid='.$peopleid.' and b.status=2')->order('a.id desc' )->select();
        $custom2 = M('orderlist')->where('did!="" and people='.$peopleid.' and status =2 and handnum=5 and pay=2')->order('id desc' )->select();
       
        }
        $custom=   array_merge($custom1,$custom2);
         //var_dump($custom);die;
        //分页
        $count = count($custom);
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);
        $custom=array_slice($custom,$Page->firstRow,$Page->listRows);
        //var_dump($custom1);die;
        foreach ($custom as $k => &$v) {
            if ($v['jid']== 0 && $v['did']=='') {
                $v['shi'] = 'z';
            }elseif ($v['jid']!= '' && $v['did']=='') {
                $v['shi'] = 'j';
            }  else {
                 $v['shi'] = 'zsd';
            }
        }
        
       
        //课程章节知识点
        $map['type'] = 1;
        $course = M('tree')->where($map)->select();
        $gep['type'] = 2;
        $chapter = M('tree')->where($gep)->select();
        $sop['type'] = 3;
        $point = M('tree')->where($sop)->select();
        
        $this->assign('course', $course);
        $this->assign('chapter', $chapter);
        $this->assign('point', $point);
        $this->assign('custom', $custom);
        $this->assign('custompri', $custompri);
        $this->assign('customcom', $customcom);
        $this->display();
    }
    public function customizationb()
    {  
        $peopleid=1;       //session
        $typeid= isset($_GET['typeid']) ? $_GET['typeid'] : '';
        //私人定制中
        if($typeid==6){ //全部
        $custompri1 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.zid=b.id and a.jid=0 and a.people='.$peopleid.' and a.status !=2 and a.handnum=5 and a.pay=2')->order('a.id desc' )->select();
        $custompri2 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.jid=b.id and a.jid!=0 and a.did="" and   a.people='.$peopleid.' and a.status !=2 and a.handnum=5 and a.pay=2')->order('a.id desc' )->select();
        $custompri3 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where(' a.did!="" and a.people='.$peopleid.' and a.status !=2 and a.handnum=5 and a.pay=2')->order('a.id desc' )->select();
         
        $custompri=   array_merge($custompri1,$custompri2,$custompri3);
        }
        if($typeid==7){ //章
        $custompri = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.zid=b.id and a.jid=0 and  a.people='.$peopleid.' and a.status !=2 and a.handnum=5 and a.pay=2')->order('id desc' )->select();
        
        }
        if($typeid==8){ //节
        $custompri = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.jid=b.id and a.jid!=0 and a.did="" and a.people='.$peopleid.' and a.status !=2 and a.handnum=5 and a.pay=2')->order('id desc' )->select();
        
        }
        if($typeid==9){ //知识点
        $custompri = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where(' a.did!="" and a.people='.$peopleid.' and a.status !=2 and a.handnum=5 and a.pay=2')->order('id desc' )->select();
       
        }
        //分页
        $count = count($custompri);
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);
        $custompri=array_slice($custompri,$Page->firstRow,$Page->listRows);
        
        foreach ($custompri as $k => &$v) {
            if ($v['jid']== 0 && $v['did']=='') {
                $v['shi'] = 'z';
            }elseif ($v['jid']!= '' && $v['did']=='') {
                $v['shi'] = 'j';
            }  else {
                 $v['shi'] = 'zsd';
            }
        }
        //var_dump($custompri);die;
         //课程章节知识点
        $map['type'] = 1;
        $course = M('tree')->where($map)->select();
        $gep['type'] = 2;
        $chapter = M('tree')->where($gep)->select();
        $sop['type'] = 3;
        $point = M('tree')->where($sop)->select();
        
        $this->assign('course', $course);
        $this->assign('chapter', $chapter);
        $this->assign('point', $point);
        $this->assign('custompri', $custompri);
        $this->display();
    }
    public function customizationc()
    {   
        $peopleid=1;       //session
        $typeid= isset($_GET['typeid']) ? $_GET['typeid'] : '';
        //众筹定制中(包括发起的和参与的)
         if($typeid==11){ //全部
        $customcom1 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.zid=b.id and a.jid=0 and a.people='.$peopleid.' and a.status !=2  and a.pay=1')->order('a.id desc' )->select();
        $customcom2 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.jid=b.id and a.jid!=0 and a.did="" and a.people='.$peopleid.' and a.status !=2  and a.pay=1')->order('a.id desc' )->select();
        $customcom3 = M('orderlist')->where('did!="" and people='.$peopleid.' and status !=2  and pay=1')->order('id desc' )->select();
        $customcom4 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('b.zid=c.id and b.jid=0 and a.orderid = b.id  and a.peopleid='.$peopleid.' and b.status !=2 and b.pay=1')->group('ordernum')->order('a.id desc' )->select();
        $customcom5 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('b.jid=c.id and b.jid!=0 and b.did="" and a.orderid = b.id  and a.peopleid='.$peopleid.' and b.status !=2 and b.pay=1')->group('ordernum')->order('a.id desc' )->select();
        $customcom6 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('b.did!="" and a.orderid = b.id  and a.peopleid='.$peopleid.' and b.status !=2 and b.pay=1')->group('ordernum')->order('a.id desc' )->select();

        $customcom1=   array_merge($customcom1,$customcom2,$customcom3);
        $customcom2=   array_merge($customcom4,$customcom5,$customcom6);
       
         }
         if($typeid==12){ //章
        $customcom1 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.zid=b.id and a.jid=0 and a.people='.$peopleid.' and a.status !=2  and a.pay=1')->order('a.id desc' )->select();
        $customcom2 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('b.zid=c.id and b.jid=0 and a.orderid = b.id  and a.peopleid='.$peopleid.' and b.status !=2 and b.pay=1')->group('ordernum')->order('a.id desc' )->select();
       
         }
         if($typeid==13){ //节
        $customcom1 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.jid=b.id and a.jid!=0 and a.did="" and a.people='.$peopleid.' and a.status !=2  and a.pay=1')->order('a.id desc' )->select();
        $customcom2 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('b.jid=c.id and b.jid!=0 and b.did="" and a.orderid = b.id  and a.peopleid='.$peopleid.' and b.status !=2 and b.pay=1')->group('ordernum')->order('a.id desc' )->select();
       
         }
         if($typeid==14){ //知识点
        $customcom1 = M('orderlist')->where('did!="" and people='.$peopleid.' and status !=2  and pay=1')->order('id desc' )->select();
        $customcom2 = M('handlist')->table('qw_handlist a, qw_orderlist b,qw_tree c')->where('b.did!="" and a.orderid = b.id  and a.peopleid='.$peopleid.' and b.status !=2 and b.pay=1')->group('ordernum')->order('a.id desc' )->select();
        
         }
         $customcom=   array_merge($customcom1,$customcom2);
         //分页
        $count = count($customcom);
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);
        $customcom=array_slice($customcom,$Page->firstRow,$Page->listRows);
        
         foreach ($customcom as $k => &$v) {
            if ($v['jid']== 0 && $v['did']=='') {
                $v['shi'] = 'z';
            }elseif ($v['jid']!= '' && $v['did']=='') {
                $v['shi'] = 'j';
            }  else {
                 $v['shi'] = 'zsd';
            }
        }
        //var_dump($customcom);die;
        //课程章节知识点
        $map['type'] = 1;
        $course = M('tree')->where($map)->select();
        $gep['type'] = 2;
        $chapter = M('tree')->where($gep)->select();
        $sop['type'] = 3;
        $point = M('tree')->where($sop)->select();
        
        $this->assign('course', $course);
        $this->assign('chapter', $chapter);
        $this->assign('point', $point);
        $this->assign('customcom', $customcom);
        $this->display();
    }
    //课程分享
    public function share()
    {
        $this->display();
    }
}
