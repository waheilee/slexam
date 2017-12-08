<?php

namespace Home\Controller;


class UsercenterController extends ComController{
    //put your code here
    public function index()
    {
        $where['id']=1;     //session
        $user=M('users')->where($where)->find();
        //var_dump($user);die;
        $province = M('areas')->where(array('parent_id' => 1))->select();
        $this->assign('province', $province);
        $this->assign('user', $user);
        $this->display();
    }

    public function indexx(){
        $where['id']=1;     //session
        $user=M('users')->where($where)->find();
        //var_dump($user);die;
        $province = M('areas')->where(array('parent_id' => 1))->select();
        $this->assign('province', $province);
        $this->assign('user', $user);
        $this->display();
    }
    public function register(){

            $data['username']= isset($_POST['username']) ? $_POST['username'] : false;
            $data['truename'] = isset($_POST['truename']) ? $_POST['truename'] : false;
            $data['mobile']= isset($_POST['mobile']) ? $_POST['mobile'] : false;
            $data['sex'] = isset($_POST['sex']) ? $_POST['sex'] : false;
            $data['birthday']= isset($_POST['birthday']) ? strtotime($_POST['birthday']) : false;
            $data['email'] = isset($_POST['email']) ? $_POST['email'] : false;
            $data['province']= isset($_POST['province']) ? $_POST['province'] : false;
            $data['city'] = isset($_POST['city']) ? $_POST['city'] : false;
            $data['town']= isset($_POST['town']) ? $_POST['town'] : false;
//            $data['head'] = $_REQUEST['head'];

//        var_dump($_FILES['head']);die;
            $model=M('users');
            $where['id']=1;    //session或者cookie
            //var_dump($_FILES['head']['name']);die;
             $gep['username']=  $data['username'];
             $gep['id']= array('neq',$where['id']);
             $username=M('users')->where($gep)->find();
             if($username){
                 $this->error('用户昵称已存在','indexx');
             
             }
            if($_FILES['head']!=''){
                    $Img = $this->saveimg($_FILES);
                    $data['head'] = $Img;
                }

            $users=$model->where($where)->save($data);
            if($users){
                $this->success('修改成功','indexx');
            }  else {
                $this->error('未修改内容','indexx');
            }
      
        
        //$this->display('index');
    }
   

    public function getRegion() {
        $Region = M("areas");
        $str = '<option>请选择</option>';
        if(I('post.type') == 'province'){
            $map['parent_id'] = 1;
        }elseif(I('post.id') == 1){
            $this->ajaxReturn($str);
        }else{
          $map['parent_id'] = I('post.id');  
        }
        $list = $Region->where($map)->select();
      
        foreach($list as $v){
            if($v['area_id'] == I('post.check')){
                $str .= '<option selected value="'.$v['area_id'].'">'.$v['area_name'].'</option>';
            }else{
                $str .= '<option value="'.$v['area_id'].'">'.$v['area_name'].'</option>';
            }     
        }
        
        $this->ajaxReturn($str);
    }

    //头像上传
    public function upload()
    {
//        dump($_FILES);die;
        $model=M('users');
        $where['id']=1;    //session或者cookie
        if($_FILES!=''){
            $Img =   $this->saveimg($_FILES);
            $data['head'] = $Img;
        }
        $model->where($where)->save($data);
        $user=M('users')->where($where)->find();
        $this->ajaxReturn(__ROOT__.$user['head']);

    }

    function addtwo(){
        //ajaxReturn(数据,'提示信息',状态)
        $model = M('users');
//        dump($_POST);die;
        if (IS_POST){
            $data['username'] = isset($_POST['username']) ? $_POST['username'] : false;
            $data['truename'] = isset($_POST['truename']) ? $_POST['truename'] : false;
            $data['mobile'] = isset($_POST['mobile']) ? $_POST['mobile'] : false;
            $data['sex'] = isset($_POST['sex']) ? $_POST['sex'] : false;
            $data['birthday'] = isset($_POST['birthday']) ? strtotime($_POST['birthday']) : false;
            $data['email'] = isset($_POST['email']) ? $_POST['email'] : false;
            $data['province'] = isset($_POST['province']) ? $_POST['province'] : false;
            $data['city'] = isset($_POST['city']) ? $_POST['city'] : false;
            $data['town'] = isset($_POST['town']) ? $_POST['town'] : false;
            //      $data['head'] = $_REQUEST['head'];

            $where['id'] = 1;    //session或者cookie
            $users = $model->where($where)->save($data);

            $this->ajaxReturn($users,'添加信息成功',1);
        }else{
            $this->ajaxReturn(0,'添加信息失败',0);
        }
    }

    
    

    

}
