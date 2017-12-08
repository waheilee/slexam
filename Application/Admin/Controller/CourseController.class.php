<?php


namespace Admin\Controller;


class CourseController extends ComController{
    //put your code here
    public function index() {

        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
       
        $keywords = isset($_GET['keywords']) ? htmlentities($_GET['keywords']) : '';

        $tree = M('tree');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $where['type']=1;
        $where['pid']=0;
        if($keywords){
        $where['name']=$keywords;
        }
        $count = $tree->where($where)->count();
        $list = $tree->where($where)->limit($offset . ',' . $pagesize)->select();
        //$user->getLastSql();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
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
       

                   $zip['pid']=$value;
                   //var_dump($value);die;
                   $point=M('tree')->where($zip)->select();//章
                   //var_dump($point);die;
                   foreach ($point as $key => $val) {
                       $sap['id']=$val['id'];
                       $sap['type']=2;
                       // var_dump($sap);die;

                       $lip['pid']=$val['id'];
                        $poin=M('tree')->where($lip)->select();//节
                        if($poin){
                            foreach ($poin as $key => $valuee) {
                                $aip['pid']=$valuee['id'];
                                $lap['id']=$valuee['id'];
                                $lap['type']=3;
                                $knowledge=M('tree')->where($aip)->select();  //知识点
                                foreach ($knowledge as $key => $vall) {
                                    $nap['id']=$vall['id'];
                                    $nap['type']=4;
                                    $knowdel=M('tree')->where($nap)->delete();
                                }
                               $pointdel=M('tree')->where($lap)->delete(); 
                            }
                              
                        }
  						$poi=M('tree')->where($sap)->delete();

                   }
 
       }
        if (M('tree')->where($map)->delete()) {

            addlog('删除课程ID：' . $aids);
            $this->success('恭喜，课程删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function edit() {

        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        if ($uid) {
            $where['id']=$uid;
            $course = M('tree');
            $course = $course->where($where)->find();
        } else {
            $this->error('参数错误！');
        }

        $this->assign('course', $course);
        $this->display('form');
    }

    public function update() {

        $uid = isset($_POST['id']) ? intval($_POST['id']) : false;
        $course = isset($_POST['course']) ? $_POST['course'] : '';
       foreach ($course as $key => $value) {
            if($value==''){
            $this->error('请填课程');
            }
        }
        $gep['name']=$course[0];
        $map['pid']=0;
        $map['type']=1;
        //var_dump($chapter);die;
        foreach ($course as $key => $value) { 
            $data[$key]['name']=$value;
            $data[$key]['pid']=$map['pid'];
            $data[$key]['type']=$map['type'];
        }
        //var_dump($data);die;
        if ($uid) {

            $course = M('tree')->where('id=' . $uid)->data($gep)->save();
            if ($course) {
                addlog('编辑课程名：' . $gep['name']);
                $this->success('恭喜，课程名修改成功！');
                exit(0);
            } else {
                $this->success('未修改内容');
            }
        } else {

            $course = M('tree')->addAll($data);
            if ($course) {
                addlog('新增课程：' . $data['username']);
                $this->success('恭喜，课程新增成功！');
                exit(0);
            } else {
                $this->success('课程新增失败');
            }
        }
 
    }

    public function add() {

        $province = M('tree')->where(array('pid' => 1))->select();
        $this->assign('province', $province);
        $this->display('add');
    }

    public function getRegion() {
        $Region = M("tree");
        $map['pid'] = $_REQUEST["pid"];
        $map['type'] = $_REQUEST["type"];
        $list = $Region->where($map)->select();
        echo json_encode($list);
    }

}
