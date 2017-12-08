<?php

namespace Admin\Controller;

class PointController extends ComController {

    //put your code here
    public function index() {

        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';

        $keywords = isset($_GET['keywords']) ? htmlentities($_GET['keywords']) : '';
        $courseid = isset($_GET['course']) ? $_GET['course'] : '';
        $chapterid = isset($_GET['chapter']) ? $_GET['chapter'] : '';
        $tree = M('tree');
        $pagesize = 20; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $where['type'] = 3;
        if ($courseid) {
            $where['cid'] = $courseid;
        }
        if ($chapterid) {
            $where['pid'] = $chapterid;
        }
        if ($keywords) {
            $where['name'] = $keywords;
        }
        $count = $tree->where($where)->count();
        $list = $tree->where($where)->limit($offset . ',' . $pagesize)->select();
        //$user->getLastSql();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();

        $map['pid'] = 0;
        $map['type'] = 1;
        $course = M('tree')->where($map)->select();
        //$gep['pid']=1;
        $gep['type'] = 2;
        $chapter = M('tree')->where($gep)->select();
        //var_dump($course);die;
        $province = M('tree')->where(array('pid' => 0))->select();
        $this->assign('province', $province);
        $this->assign('course', $course);
        $this->assign('chapter', $chapter);
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    public function del() {

       $aids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        if (!$aids) {
            $this->error('参数错误！未勾选');
        }

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
       //var_dump($value);die;
                $gep['pid']=$value;
                
                if(M('tree')->where($gep)->find()){
                   M('tree')->where($gep)->delete();
                }
        }
        if (M('tree')->where($map)->delete()) {

            addlog('删除节ID：' . $aids);
            $this->success('恭喜，节删除成功！');

        } else {
            $this->error('参数错误！');
        }
    }

    public function add() {

        $province = M('tree')->where(array('pid' => 0))->select();
        $this->assign('province', $province);
        $this->display('add');
    }

    public function edit() {

        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        if ($uid) {

            $point = M('tree');
            $where['id'] = $uid;
            $point = $point->where($where)->find();
        } else {
            $this->error('参数错误！');
        }
        //var_dump($point);die;
        $province = M('tree')->where(array('pid' => 0))->select();
        $this->assign('province', $province);
        $this->assign('point', $point);
        $this->display('form');
    }

    public function update() {

        $uid = isset($_POST['id']) ? intval($_POST['id']) : false;
        $courseid = isset($_POST['course']) ? $_POST['course'] : '';
        $chapterid = isset($_POST['chapter']) ? $_POST['chapter'] : '';
        if(empty($chapterid)||empty($courseid)){
            $this->error('请选择分类');
        }
        $point = isset($_POST['point']) ? $_POST['point'] : '';
        //var_dump($point);die;
        foreach ($point as $key => $value) {
            if($value==''){
            $this->error('请填写节');
            }
        }
        //var_dump($chapterid);die;
        $map['type'] = 3;
        //var_dump($courseid);die;
        foreach ($point as $key => $value) {
            $data[$key]['name'] = $value;
            $data[$key]['pid'] = $chapterid;
            $data[$key]['type'] = $map['type'];
            $data[$key]['cid'] = $courseid;
        }
        //var_dump($data);die;
        if ($uid) {

            $gep['pid'] = $chapterid;
            $gep['cid'] = $courseid;
            $gep['name'] = $point[0];
            //var_dump($gep);die;
            $point = M('tree')->where('id=' . $uid)->data($gep)->save();
            if ($point) {
                addlog('编辑知识点id：' . $chapterid);
                $this->success('恭喜，节修改成功！');
                exit(0);
            } else {
                $this->success('未修改内容');
            }
        } else {

            $point = M('tree')->addAll($data);
            if ($point) {
                addlog('新增节：' . $data['username']);
                $this->success('恭喜，节新增成功！');
                exit(0);
            } else {
                $this->success('节新增失败');
            }
        }
    }

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
      
        foreach($list as $v){
            if($v['id'] == I('post.check')){
                $str .= '<option selected value="'.$v['id'].'">'.$v['name'].'</option>';
            }else{
                $str .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
            }     
        }
        
        $this->ajaxReturn($str);
    }


}
