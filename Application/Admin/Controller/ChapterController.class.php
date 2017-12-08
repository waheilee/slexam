<?php

namespace Admin\Controller;

class ChapterController extends ComController {

    //put your code here
    public function index() {

        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';

        $keywords = isset($_GET['keywords']) ? htmlentities($_GET['keywords']) : '';
        $courseid = isset($_GET['course']) ? $_GET['course'] : '';
        $tree = M('tree');
        $pagesize = 20; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $where['type'] = 2;
        if ($courseid) {
            $where['pid'] = $courseid;
        }
        if ($keywords) {
            $where['name'] = $keywords;
        }
        $count = $tree->where($where)->count();
        $list = $tree->where($where)->limit($offset . ',' . $pagesize)->order('id desc')->select();
        //$user->getLastSql();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();

        $map['pid'] = 0;
        $map['type'] = 1;
        $course = M('tree')->where($map)->select();
        //var_dump($course);die;
        $this->assign('course', $course);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    public function del() {

       $aids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        if (!$aids) {
            $this->error('参数错误！未勾选');
        }
        //var_dump($aids);die;
        $map['id'] = array('in', $aids);
        //var_dump(is_array($map['id'][1]));die;
        if(is_array($map['id'][1])){
            $abc=$map['id'][1];
        }  else {
            $abc=$map['id'];
           array_shift($abc); 
 
        }
        //var_dump($abc);die;
       foreach ($abc as $key => $value) {
       
                $gep['pid']=$value;
                $chapter=M('tree')->where($gep)->select();  //jie
                //var_dump($chapter);die;
                if($chapter){
                  foreach ($chapter as $key => $valu) {
                   $zip['pid']=$valu['id'];
                   
                   $point=M('tree')->where($zip)->select();//zhishidian
                   //var_dump($point);die;
                   foreach ($point as $key => $val) {
                       $sap['id']=$val['id'];
                       $sap['type']=4;
                       // var_dump($sap);die;
                       $poi=M('tree')->where($sap)->delete();
                   }
                   $chapterdel=M('tree')->where($gep)->delete();
                }
            }
       }
        if (M('tree')->where($map)->delete()) {

            addlog('删除章ID：' . $aids);
            $this->success('恭喜，章删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function add() {
        $map['pid'] = 0;
        $map['type'] = 1;
        $course = M('tree')->where($map)->select();
        //var_dump($course);die;
        $this->assign('course', $course);
        $this->display('add');
    }

    public function edit() {

        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        if ($uid) {

            $chapter = M('tree');
            $where['id'] = $uid;
            $chapter = $chapter->where($where)->find();
        } else {
            $this->error('参数错误！');
        }
        $province = M('tree')->where(array('pid' => 0))->select();
        $this->assign('province', $province);
        $this->assign('chapter', $chapter);
        $this->display('form');
    }

    public function update() {

        $uid = isset($_POST['id']) ? intval($_POST['id']) : false;
        $courseid = isset($_POST['courses']) ? $_POST['courses'] : '';
        $chapter = isset($_POST['chapter']) ? $_POST['chapter'] : '';
        if(empty($courseid)){
            $this->error('请选择分类');
        }
        foreach ($chapter as $key => $value) {
            if($value==''){
            $this->error('请填写章节');
            }
        }
        
        $map['type'] = 2;
        //var_dump($courseid);die;
        foreach ($chapter as $key => $value) {
            $data[$key]['name'] = $value;
            $data[$key]['pid'] = $courseid;
            $data[$key]['type'] = $map['type'];
        }
        //var_dump($data);die;
        if ($uid) {
            $gep['pid']= $courseid;
            $gep['name']=$chapter[0];
            //var_dump($gep);die;
            $users = M('tree')->where('id=' . $uid)->data($gep)->save();
            if ($users) {
                addlog('编辑章：' . $chapter);
                $this->success('恭喜，章修改成功！');
                exit(0);
            } else {
                $this->success('未修改内容');
            }
        } else {

            $chapter = M('tree')->addAll($data);
            if ($chapter) {
                addlog('新增章：' . $data['username']);
                $this->success('恭喜，章新增成功！');
                exit(0);
            } else {
                $this->success('章新增失败');
            }
        }
    }


    public function getRegion() {
        $Region = M("tree");

        if (I('post.type') == 'province') {
            $map['pid'] = 0;
        } else {
            $map['pid'] = I('post.id');
        }
        $list = $Region->where($map)->select();

        foreach ($list as $v) {
            if ($v['id'] == I('post.check')) {
                $str .= '<option selected value="' . $v['id'] . '">' . $v['name'] . '</option>';
            } else {
                $str .= '<option value="' . $v['id'] . '">' . $v['name'] . '</option>';
            }
        }

        $this->ajaxReturn($str);
    }

}
