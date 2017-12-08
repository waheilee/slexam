<?php
/**
 *
 * 功能说明：前台控制器演示。
 *
 **/
namespace Home\Controller;

class RandomController extends ComController
{
    public function custom()
    {   
        //查询课程
        $course = M('tree')->where('pid=0')->select();
        $this->assign('course',$course);
        $this->display();
    }

    public function analog()
    {
        $this->display();
    }

    public function select()
    {   

        $where['pid'] = array('in',trim(I('post.id'),','));
 
        if(I('post.type') == 'kid'){
            $res = M('tree')->field('name')->where($where)->select();
  			
            foreach($res as $v){
                $res = explode('，',$v['name']);
                foreach($res as $v){
                    $str .= '<li data="'.$v.'" data-name="'.$v.'" check="1"><div class="chose-rect left"></div><div class="chose-text left">'.$v.'</div><div class="left chose-arrow"><img src="'.__ROOT__.'/Public/Home/images/random-answer/chose-arrow.png" alt=""></div></li>';
                }
            }
            
        }else{
            $res = M('tree')->where($where)->select();

            foreach($res as $v){
                $str .= '<li data="'.$v['id'].'" data-name="'.$v['name'].'" check="1"><div class="chose-rect left"></div><div class="chose-text left">'.$v['name'].'</div><div class="left chose-arrow"><img src="'.__ROOT__.'/Public/Home/images/random-answer/chose-arrow.png" alt=""></div></li>';
            }
        }

        

        $this->ajaxReturn($str);
    }
}