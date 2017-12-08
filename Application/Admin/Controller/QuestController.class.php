<?php

namespace Admin\Controller;

class QuestController extends ComController {

    //put your code here
    public function index() {

        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';

        $knowledge= isset($_GET['knowledge']) ? $_GET['knowledge'] : '';
        $keywords= isset($_GET['keywords']) ? $_GET['keywords'] : '';
        $knowledgeid= isset($_GET['knowledgeid']) ? $_GET['knowledgeid'] : '';
        
        $pagesize = 20; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
       
        if($knowledge){
        $where['did']=array('like', '%'.$knowledge.'%');
        }
        if($keywords){
        $where['content']=array('like', '%'.$keywords.'%');
        }
        $quest = M('quest');
        $count = $quest->where($where)->count();
        $list = $quest->where($where)->limit($offset . ',' . $pagesize)->select();
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

        $map['id'] = array('in', $aids);
        if (M('quest')->where($map)->delete()) {
            addlog('删除文章ID：' . $aids);
            $this->success('恭喜，题目删除成功！');
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
            $where['id']=$uid;
            $quest = M('quest');
            $quest= $quest->where($where)->find();
            $did=rtrim($quest['did'], "，");    //去掉开头和结尾的逗号
            $did=ltrim($did, "，");
            //var_dump($did);die;
        } else {
            $this->error('参数错误！');
        }
       
         $string=explode("+",$quest['answer']);

        //var_dump($string);die;
        $this->assign('quest', $quest);
        $this->assign('string', $string);
        $this->assign('did', $did);
        $this->display('form');
    }

    public function update() {

        $uid = isset($_POST['id']) ? intval($_POST['id']) : false;

        $knowledge= isset($_POST['knowledge']) ? $_POST['knowledge'] : '';

        $data['content']= isset($_POST['content']) ? $_POST['content'] : '';
        $data['aa']= isset($_POST['aa']) ? $_POST['aa'] : '';
        $data['bb']= isset($_POST['bb']) ? $_POST['bb'] : '';
        $data['cc']= isset($_POST['cc']) ? $_POST['cc'] : '';
        $data['dd']= isset($_POST['dd']) ? $_POST['dd'] : '';
        $data['multiple']= isset($_POST['multiple']) ? $_POST['multiple'] : '';
        $answers= isset($_POST['answer']) ? $_POST['answer'] : '';
        $data['keywords']= isset($_POST['keywords']) ? $_POST['keywords'] : '';
        $data['difficulty']= isset($_POST['difficulty']) ? $_POST['difficulty'] : '';
        $data['explain']= isset($_POST['explain']) ? $_POST['explain'] : '';
        $data['aexplain']= isset($_POST['aexplain']) ? $_POST['aexplain'] : '';
        $data['bexplain']= isset($_POST['bexplain']) ? $_POST['bexplain'] : '';
        $data['cexplain']= isset($_POST['cexplain']) ? $_POST['cexplain'] : '';
        $data['dexplain']= isset($_POST['dexplain']) ? $_POST['dexplain'] : '';
        $questtime= isset($_POST['questtime']) ? $_POST['questtime'] : '';
        $data['questtime']=strtotime($questtime);
        $data['addtime']= time();
        $data['model']= isset($_POST['model']) ? $_POST['model'] : '';
        $data['people']= $_SESSION['uid'];    //记录上传试卷人
       // var_dump($answer);die;
        $answer='';
        $answern='';
        foreach ($answers as $key => $value) {
            if($value=='aa'){
                $answer.=$data['aa'].'+';
                $answern.='a'.'+';
            }
            if($value=='bb'){
                $answer.=$data['bb'].'+';
                $answern.='b'.'+';
            }
            if($value=='cc'){
                $answer.=$data['cc'].'+';
                $answern.='c'.'+';
            }
            if($value=='dd'){
                $answer.=$data['dd'].'+';
                $answern.='d'.'+';
            }
        }
        $data['answer']=substr($answer, 0, -1);
        $data['answern']=substr($answern, 0, -1);
        //拆分知识点字符串
        $know = explode('，',$knowledge);      //中文状态下的逗号
       
        //var_dump($knowledge);die;
        $tree=M('tree')->where('type=4')->select();
         $kid='';
         $zid='';
         $jid='';
        foreach ($tree as $key => $value) {
             $name=$value['name'];
             $namearray = explode('，',$name);     //tree里面的知识点字符串拆分成数组
             //var_dump($know);die;
              foreach ($namearray as $key => $val) {
                  foreach ($know as $key => $vall) {
                     if(trim($vall)==trim($val)){
                        $kid.=','.$value['cid'] ;   //课程id
                        $zid.=','.$value['zid'] ;   //章id
                        $jid.=','.$value['pid'] ;   //节id
                     }
                 }
              }
        }
        $data['kid'] = $kid.',';  //英文的
        $data['zid'] = $zid.',';  //英文的
        $data['jid'] = $jid.',';  //英文的
        $data['did'] = '，'.$knowledge.'，';//中文的
        //var_dump($data);die;
        if ($uid) {
            $quest = M('quest')->where('id=' . $uid)->data($data)->save();
            if ($quest) {
                addlog('编辑题目：' . $data['content']);
                $this->success('恭喜，题目信息修改成功！');
                exit(0);
            } else {
                $this->success('未修改内容');
            }
        } else {
            
            $quest = M('quest')->add($data);
            if ($quest) {
                addlog('新增题目：' . $data['content']);
                $this->success('恭喜，题目新增成功！');
                exit(0);
            } else {
                $this->success('题目新增失败');
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
      
        $str = "<option value=''>请选择</option>";

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
