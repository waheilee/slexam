<?php


namespace Admin\Controller;


class KnowledgeController extends ComController{
    //put your code here
     public function index() {

        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';

        $keywords = isset($_GET['keywords']) ? htmlentities($_GET['keywords']) : '';
        $courseid = isset($_GET['course']) ? $_GET['course'] : '';
        $chapterid = isset($_GET['chapter']) ? $_GET['chapter'] : '';
        $pointid = isset($_GET['point']) ? $_GET['point'] : '';
        $tree = M('tree');
        $pagesize = 20; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $where['type'] = 4;
        if ($courseid) {
            $where['cid'] = $courseid;
        }
        if ($chapterid) {
            $where['zid'] = $chapterid;
        }
        if ($pointid) {
            $where['pid'] = $pointid;
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
        $sop['type'] = 3;
        $point = M('tree')->where($sop)->select();
        $province = M('tree')->where(array('pid' => 0))->select();
        $this->assign('province', $province);
        $this->assign('course', $course);
        $this->assign('chapter', $chapter);
        $this->assign('point', $point);
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
        if (M('tree')->where($map)->delete()) {
            addlog('删除知识点ID：' . $aids);
            $this->success('恭喜，知识点删除成功！');
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


//        dump($_POST);die;
        $uid = isset($_POST['id']) ? intval($_POST['id']) : false;
        $courseid = isset($_POST['course']) ? $_POST['course'] : '';
        $chapterid = isset($_POST['chapter']) ? $_POST['chapter'] : '';
        $pointid = isset($_POST['point']) ? $_POST['point'] : '';
        if(empty($chapterid)||empty($courseid)||empty($pointid)){
            $this->error('请选择分类');
        }
        $knowledge = isset($_POST['knowledge']) ? $_POST['knowledge'] : '';
        //var_dump($point);die;
        if(empty($knowledge)){
             $this->error('请填写知识点');
        }
           $map['type'] = 4;
        //var_dump($courseid);die;

            $data['name'] = $knowledge;
            $data['pid'] = $pointid;
            $data['type'] = $map['type'];
            $data['cid'] = $courseid;
            $data['zid'] = $chapterid;
  
        //var_dump($data);die;
        if ($uid) {
            $gep['pid'] = $pointid;
            $gep['zid'] = $chapterid;
            $gep['cid'] = $courseid;
            $gep['name'] = $knowledge;
            //var_dump($gep);die;
            $point = M('tree')->where('id=' . $uid)->data($gep)->save();
            if ($point) {
                addlog('编辑知识点id：' . $chapterid);
//                $this->success('恭喜，知识点修改成功！');
                $data['status']  = 2;
                $this->ajaxReturn($data);
                exit(0);
            } else {
//                $this->success('未修改内容');
                $data['status']  = 0;
                $this->ajaxReturn($data);
            }
        } else {
            $wheres['pid']=$pointid;
            if(M('tree')->where($wheres)->find()){
//                $this->error('该章节已存在知识点，请勿重复添加');
                $data['status']  = 3;
                $this->ajaxReturn($data);
            }
            $point = M('tree')->add($data);
            if ($point) {
                addlog('新增知识点：' . $data['username']);
//                $this->success('恭喜，知识点新增成功！');
                $data['status']  = 1;
                $this->ajaxReturn($data);
                exit(0);
            } else {
//                $this->success('知识点新增失败');
                $data['status']  = 4;
                $this->ajaxReturn($data);
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

