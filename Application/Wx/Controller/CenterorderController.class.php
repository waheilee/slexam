<?php

namespace Wx\Controller;

class CenterorderController extends ComController{
    //put your code here
    public function indexp(){
        $typeid= isset($_GET['typeid']) ? $_GET['typeid'] : '';
        
        $where='a.kid=b.id  and a.pay=2 and a.people=1';     //session
        
        if($typeid==''){    //全部
        $custompri = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where($where)->order('a.id desc' )->select();
        
        }
        if($typeid==2){     //已完成
          $where.=' and status=2' ;
          $custompri = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where($where)->order('a.id desc' )->select();
        
        }
        if($typeid==3){     //等待完成
           $where.=' and status!=2' ; 
           $custompri = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where($where)->order('a.id desc' )->select();
        
        }
         $custompricount = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.kid=b.id  and a.pay=2 and a.people=1')->order('a.id desc' )->select();  //session
         $count=  count($custompricount);
         $custompricount2 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.kid=b.id  and a.pay=2 and a.people=1 and status=2')->order('a.id desc' )->select();  //session
         $count2=  count($custompricount2);
         $custompricount3 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.kid=b.id  and a.pay=2 and a.people=1 and status!=2')->order('a.id desc' )->select();  //session
         $count3=  count($custompricount3);
      //var_dump($custompri);die;
       
        $comcount=$this->countc();
        $comcountall=$comcount[0];   //众筹所有
        $comcounts=$comcount[1];   //众筹已完成
        $this->assign('custompri',$custompri);
        $this->assign('count',$count);
        $this->assign('count2',$count2);
        $this->assign('count3',$count3);
        $this->assign('comcountall',$comcountall);
        $this->assign('comcounts',$comcounts);
        $this->display();
    }
    public function indexc(){
       $typeid= isset($_GET['typeid']) ? $_GET['typeid'] : '';
        
        $where='a.kid=b.id  and a.pay=1 and a.people=1';     //session
        $map='a.kid=b.id  and a.id=c.orderid and a.pay=1 and c.peopleid=1';//session
        //发起的定制和参与的定制
        if($typeid==''){
           $customcom1 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where($where)->order('a.id desc' )->select();
           $customcom2 = M('orderlist')->table('qw_orderlist a, qw_tree b,qw_handlist c')->field('a.*,b.name')->where($map)->order('a.id desc' )->select();
           $customcom=array_merge($customcom1,$customcom2);
        }
        if($typeid==2){
          $where.=' and status=2' ;
           $map.=' and status=2' ;
           $customcom1 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where($where)->order('a.id desc' )->select();
           $customcom2 = M('orderlist')->table('qw_orderlist a, qw_tree b,qw_handlist c')->field('a.*,b.name')->where($map)->order('a.id desc' )->select();
           $customcom=array_merge($customcom1,$customcom2);
        }
        if($typeid==3){
           $where.=' and status!=2' ; 
           $map.=' and status!=2' ;
           $customcom1 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where($where)->order('a.id desc' )->select();
           $customcom2 = M('orderlist')->table('qw_orderlist a, qw_tree b,qw_handlist c')->field('a.*,b.name')->where($map)->order('a.id desc' )->select();
           $customcom=array_merge($customcom1,$customcom2);
        }
        
           $customcom1count = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.kid=b.id  and a.pay=1 and a.people=1')->order('a.id desc' )->select();    //session
           $customcom2count = M('orderlist')->table('qw_orderlist a, qw_tree b,qw_handlist c')->field('a.*,b.name')->where('a.kid=b.id  and a.id=c.orderid and a.pay=1 and c.peopleid=1')->order('a.id desc' )->select(); //session
           $customcomcount=array_merge($customcom1count,$customcom2count);
           $count=  count($customcomcount);
           //var_dump($customcom2count);die;
           $customcom1count2 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.kid=b.id  and a.pay=1 and a.people=1 and status=2')->order('a.id desc' )->select(); //session
           $customcom2count2 = M('orderlist')->table('qw_orderlist a, qw_tree b,qw_handlist c')->field('a.*,b.name')->where('a.kid=b.id  and a.id=c.orderid and a.pay=1 and c.peopleid=1 and status=2')->order('a.id desc' )->select(); //session
           $customcomcount2=array_merge($customcom1count2,$customcom2count2);
           $count2=  count($customcomcount2);
           $customcom1count3 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.kid=b.id  and a.pay=1 and a.people=1 and status!=2')->order('a.id desc' )->select(); //session
           $customcom2count3 = M('orderlist')->table('qw_orderlist a, qw_tree b,qw_handlist c')->field('a.*,b.name')->where('a.kid=b.id  and a.id=c.orderid and a.pay=1 and c.peopleid=1 and status!=2')->order('a.id desc' )->select(); //session
           $customcomcount3=array_merge($customcom1count3,$customcom2count3);
           $count3=  count($customcomcount3);
         //var_dump($customcom2);die;
          
        $pricount=$this->countp();
        $pricountall=$pricount[0];   //众筹所有
        $pricounts=$pricount[1];   //众筹已完成
        $this->assign('customcom',$customcom);
        $this->assign('count',$count);
        $this->assign('count2',$count2);
        $this->assign('count3',$count3);
        $this->assign('pricount',$pricount);
        $this->assign('pricountall',$pricountall);
        $this->assign('pricounts',$pricounts);
        $this->display();
        // return  $count;
    }
    public function countp(){
        //总的
        $custompricount = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.kid=b.id  and a.pay=2 and a.people=1')->order('a.id desc' )->select();  //session
         $count=  count($custompricount);
        //已完成的
         $custompricount2 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.kid=b.id  and a.pay=2 and a.people=1 and status=2')->order('a.id desc' )->select();  //session
         $count2=  count($custompricount2);
         return array($count,$count2);
    }
    public function countc(){
          $customcom1count = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.kid=b.id  and a.pay=1 and a.people=1')->order('a.id desc' )->select();    //session
           $customcom2count = M('orderlist')->table('qw_orderlist a, qw_tree b,qw_handlist c')->field('a.*,b.name')->where('a.kid=b.id  and a.id=c.orderid and a.pay=1 and c.peopleid=1')->order('a.id desc' )->select(); //session
           $customcomcount=array_merge($customcom1count,$customcom2count);
           $count=  count($customcomcount);
           //var_dump($customcom2count);die;
           $customcom1count2 = M('orderlist')->table('qw_orderlist a, qw_tree b')->field('a.*,b.name')->where('a.kid=b.id  and a.pay=1 and a.people=1 and status=2')->order('a.id desc' )->select(); //session
           $customcom2count2 = M('orderlist')->table('qw_orderlist a, qw_tree b,qw_handlist c')->field('a.*,b.name')->where('a.kid=b.id  and a.id=c.orderid and a.pay=1 and c.peopleid=1 and status=2')->order('a.id desc' )->select(); //session
           $customcomcount2=array_merge($customcom1count2,$customcom2count2);
           $count2=  count($customcomcount2);
           return array($count,$count2);
    }
}
