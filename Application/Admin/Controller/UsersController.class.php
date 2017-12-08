<?php

/**
 *
 * 功能说明：用户控制器。
 *
 * */

namespace Admin\Controller;

class UsersController extends ComController {

    public function index() {

        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '';

        $prefix = C('DB_PREFIX');
        if ($order == 'asc') {
            $order = "{$prefix}member.t asc";
        } elseif (($order == 'desc')) {
            $order = "{$prefix}member.t desc";
        } else {
            $order = "{$prefix}member.uid asc";
        }
        if ($keyword <> '') {
            if ($field == 'user') {
                $where = "{$prefix}member.user LIKE '%$keyword%'";
            }
            if ($field == 'phone') {
                $where = "{$prefix}member.phone LIKE '%$keyword%'";
            }
            if ($field == 'qq') {
                $where = "{$prefix}member.qq LIKE '%$keyword%'";
            }
            if ($field == 'email') {
                $where = "{$prefix}member.email LIKE '%$keyword%'";
            }
        }


        $user = M('member');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $count = $user->field("{$prefix}member.*,{$prefix}auth_group.id as gid,{$prefix}auth_group.title")
                ->order($order)
                ->join("{$prefix}auth_group_access ON {$prefix}member.uid = {$prefix}auth_group_access.uid")
                ->join("{$prefix}auth_group ON {$prefix}auth_group.id = {$prefix}auth_group_access.group_id")
                ->where($where)
                ->count();

        $list = $user->field("{$prefix}member.*,{$prefix}auth_group.id as gid,{$prefix}auth_group.title")
                ->order($order)
                ->join("{$prefix}auth_group_access ON {$prefix}member.uid = {$prefix}auth_group_access.uid")
                ->join("{$prefix}auth_group ON {$prefix}auth_group.id = {$prefix}auth_group_access.group_id")
                ->where($where)
                ->limit($offset . ',' . $pagesize)
                ->select();
        //$user->getLastSql();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $group = M('auth_group')->field('id,title')->select();
        $this->assign('group', $group);
        $this->display();
    }

    public function del() {

        $uids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        //uid为1的禁止删除
        if ($uids == 1 or ! $uids) {
            $this->error('参数错误！');
        }
        if (is_array($uids)) {
            foreach ($uids as $k => $v) {
                if ($v == 1) {//uid为1的禁止删除
                    unset($uids[$k]);
                }
                $uids[$k] = intval($v);
            }
            if (!$uids) {
                $this->error('参数错误！');
                $uids = implode(',', $uids);
            }
        }

        $map['uid'] = array('in', $uids);
        if (M('member')->where($map)->delete()) {
            M('auth_group_access')->where($map)->delete();
            addlog('删除会员UID：' . $uids);
            $this->success('恭喜，用户删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function edit() {

        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        if ($uid) {
            //$member = M('member')->where("uid='$uid'")->find();
            $prefix = C('DB_PREFIX');
            $user = M('member');
            $member = $user->field("{$prefix}member.*,{$prefix}auth_group_access.group_id")->join("{$prefix}auth_group_access ON {$prefix}member.uid = {$prefix}auth_group_access.uid")->where("{$prefix}member.uid=$uid")->find();
        } else {
            $this->error('参数错误！');
        }

        $usergroup = M('auth_group')->field('id,title')->select();
        $this->assign('usergroup', $usergroup);

        $this->assign('member', $member);
        $this->display('form');
    }

    public function update() {

        $uid = isset($_POST['id']) ? intval($_POST['id']) : false;
        $data['username'] = isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES) : '';
        $data['userpass'] = isset($_POST['userpass']) ? $_POST['userpass'] : '';
        $repassword = isset($_POST['repassword']) ? $_POST['repassword'] : '';
        if ($data['userpass'] != $repassword) {
            $this->error('前后密码不一致！');
        }
        $data['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES) : '';
        $data['sex'] = isset($_POST['sex']) ? $_POST['sex'] : '';
        $data['birthday'] = isset($_POST['birthday']) ? strtotime($_POST['birthday']) : '';
        $data['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
        $data['class'] = isset($_POST['class']) ? trim($_POST['class']) : '';
        $data['level'] = isset($_POST['level']) ? trim($_POST['level']) : '';
        $data['point'] = isset($_POST['point']) ? trim($_POST['point']) : '';
        $data['introduce'] = isset($_POST['introduce']) ? trim($_POST['introduce']) : '';
        $data['addtime'] = time();

        $paths = 'users'; //设置头像上传子路径
        //var_dump($_FILES);die;
        if (!$uid) {
            if ($_FILES['file']['name'] != '') {

                $Img = $this->saveimgs($_FILES, $paths);
                $data['picture'] = $Img;
            } else {
                $data['picture'] = $_POST['files'];
            }
            $users = M('users')->where('id=' . $id)->data($data)->save();
            if ($users) {
                addlog('编辑注册会员用户名：' . $data['username']);
                $this->success('恭喜，会员信息修改成功！');
                exit(0);
            } else {
                $this->success('未修改内容');
            }
        } else {
            //图片上传

            $Img = $this->saveimgs($_FILES, $paths);
            $data['picture'] = $Img;
            var_dump($data['picture']);
            die;
            $users = M('users')->data($data)->add();
            if ($users) {
                addlog('新增注册会员用户名：' . $data['username']);
                $this->success('恭喜，会员新增成功！');
                exit(0);
            } else {
                $this->success('会员新增失败');
            }
        }
    }

    public function add() {

        $province = M('tree')->where(array('pid' => 1))->select();
        $this->assign('province', $province);
        $this->display('form');
    }

    public function getRegion() {
        $Region = M("tree");
        $map['pid'] = $_REQUEST["pid"];
        $map['type'] = $_REQUEST["type"];
        $list = $Region->where($map)->select();
        echo json_encode($list);
    }

}
