<?php
/**
 *
 * 功能说明：后台登录控制器。
 *
 **/

namespace Admin\Controller;

use Admin\Controller\ComController;

class LoginController extends ComController
{
    public function index()
    {
        $flag = $this->check_login();
        if ($flag) {
            $this->error('您已经登录,正在跳转到主页', U("index/index"));
        }

        $this->display();
    }
    public function indexregister()
    {
        $flag = $this->check_login();
        if ($flag) {
            $this->error('您已经登录,正在跳转到主页', U("index/index"));
        }
        $province = M('tree')->where(array('pid' => 0))->select();
        $this->assign('province', $province);
        $this->display('register');
    }
    public function login()
    {
        $verify = isset($_POST['verify']) ? trim($_POST['verify']) : '';
        if (!$this->check_verify($verify, 'login')) {
            $this->error('验证码错误！', U("login/index"));
        }

        $username = isset($_POST['user']) ? trim($_POST['user']) : '';
        $password = isset($_POST['password']) ? password(trim($_POST['password'])) : '';
        $remember = isset($_POST['remember']) ? $_POST['remember'] : 0;
        if ($username == '') {
            $this->error('用户名不能为空！', U("login/index"));
        } elseif ($password == '') {
            $this->error('密码必须！', U("login/index"));
        }

        $model = M("Member");
        $user = $model->field('uid,user')->where(array('user' => $username, 'password' => $password))->find();

        if ($user) {
            $salt = C("COOKIE_SALT");
            $ip = get_client_ip();
            $ua = $_SERVER['HTTP_USER_AGENT'];
            session_start();
            session('uid',$user['uid']);
            session('user',$user['user']);
            //加密cookie信息
            $auth = password($user['uid'].$user['user'].$ip.$ua.$salt);
            if ($remember) {
                cookie('auth', $auth, 3600 * 24 * 365);//记住我
            } else {
                cookie('auth', $auth);
            }
            
            addlog('登录成功。');
            $url = U('index/index');
            header("Location: $url");
            exit(0);
        } else {
            addlog('登录失败。', $username);
            $this->error('登录失败，请重试！', U("login/index"));
        }
    }
    public function register()
    {
        $verify = isset($_POST['verify']) ? trim($_POST['verify']) : '';
        if (!$this->check_verify($verify, 'login')) {
            $this->error('验证码错误！', U("login/indexregister"));
        }

        $data['user'] = isset($_POST['user']) ? htmlspecialchars($_POST['user'], ENT_QUOTES) : '';
        $data['head'] = isset($_POST['course']) ? $_POST['course'] : '';
         
        if (!$data['head']) {
            $this->error('请选择擅长课程！');
        }
        $password = isset($_POST['password']) ? $_POST['password'] : false;
        $repassword = isset($_POST['repassword']) ? $_POST['repassword'] : false;
        if($password!=$repassword){
            $this->error('前后密码不一致！');
        }
        if ($password) {
            $data['password'] = password($password);
        }
        $data['sex'] = isset($_POST['sex']) ? $_POST['sex'] : 0;
        $data['phone'] = isset($_POST['phone']) ? $_POST['phone']: '';
        $data['qq'] = isset($_POST['qq']) ? $_POST['qq'] : '';
        $data['email'] = isset($_POST['email']) ? $_POST['email'] : '';
        $data['t'] = time();
        //var_dump($data);die;
       
            if ($data['user'] == '') {
                $this->error('用户名称不能为空！');
            }
            if (!$password) {
                $this->error('用户密码不能为空！');
            }
            $user=$data['user'];
            if (M('member')->where("user='$user'")->count()) {
                $this->error('用户名已被占用！');
            }
    
            $uid = M('member')->data($data)->add();
            $group_id=8;
            $access=M('auth_group_access')->data(array('group_id' => $group_id, 'uid' => $uid))->add();
            if($access){
                 addlog('新增会员，会员UID：' . $uid);
                 $this->success('操作成功！');
            }
           
    
        }
    function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    public function verify()
    {
        $config = array(
            'fontSize' => 14, // 验证码字体大小
            'length' => 4, // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
            'imageW' => 100,
            'imageH' => 30,
        );
        $verify = new \Think\Verify($config);
        $verify->entry('login');
    }
}
