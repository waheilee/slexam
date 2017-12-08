<?php

namespace Admin\Controller;

class QuestionController extends ComController {

    //put your code here
    public function index() {

        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';


        $knowledge= isset($_GET['knowledge']) ? $_GET['knowledge'] : '';
        $keywords= isset($_GET['keywords']) ? $_GET['keywords'] : '';
        $story= isset($_GET['story']) ? $_GET['story'] : '';


        $pagesize = 20; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量

         if($knowledge){
        $where['did']=array('like', '%'.$knowledge.'%');

        }

        if($keywords){
        $where['content']=array('like', '%'.$keywords.'%');

        }

        if($story){
        $where['story']=array('like', '%'.$story.'%');

        }


        $quest = M('question');
        $count = $quest->where($where)->count();
        $list = $quest->where($where)->limit($offset . ',' . $pagesize)->select();
        //$user->getLastSql();
        //var_dump($list);die;
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();

        foreach ($list as $k => &$v) {
            if (PD($v['story'], $list[$k + 1]['story']) == 1) {
                $v['shi'] = 'true';
            } else {
                $v['shi'] = 'false';
            }
        }



        $this->assign('list', $list);
        $this->assign('tree', $tree);
        $this->assign('page', $page);
        $this->display();
    }

    public function del() {

        $aids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        if (!$aids) {
            $this->error('参数错误！未勾选');
        }

        $map['id'] = array('in', $aids);
        if (M('question')->where($map)->delete()) {
            addlog('删除题目ID：' . $aids);
            $this->success('恭喜，题目删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function add() {

        $this->display('add');
    }

    public function edit() {

        $uid = isset($_GET['uid']) ? intval($_GET['uid']) : false;
        if ($uid) {
            $where['id'] = $uid;
            $question = M('question');

            $question = $question->where($where)->find();
            $did=rtrim($question['did'], "，");    //去掉开头和结尾的逗号
            $did=ltrim($did, "，");

        } else {
            $this->error('参数错误！');
        }

        $str = explode("+", $question['answer']);

        //var_dump($str);die;
        //var_dump($question);die;
        $this->assign('question', $question);
        $this->assign('str', $str);
        $this->assign('did', $did);
        $this->display('form');
    }

    public function update() {

        $uid = isset($_POST['id']) ? intval($_POST['id']) : false;

        $story = isset($_POST['story']) ? $_POST['story'] : '';
        $questiontime = isset($_POST['questiontime']) ? $_POST['questiontime'] : '';
        $questiontime = strtotime($questiontime);

        $contentcount = isset($_POST['contentcount']) ? $_POST['contentcount'] : '';
        $knowledge = isset($_POST['knowledge']) ? $_POST['knowledge'] : '';
        $model=isset($_POST['model']) ? $_POST['model'] : '';
        //var_dump($contentcount);die;

        //拆分知识点字符串
        $know = explode('，',$knowledge);      //中文状态下的逗号
       
        //var_dump($know);die;
        $tree=M('tree')->where('type=4')->select();
         $kid='';
         $zid='';
         $jid='';
        foreach ($tree as $key => $value) {
             $name=$value['name'];
             $namearray = explode('，',$name);     //tree里面的知识点字符串拆分成数组
             //var_dump($namearray);die;
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

        if (!$contentcount) {

            $this->error('请选择试题数目');
        }

        $data = array();
        if ($uid) {                //判断是修改还是增加
            $contentcounts = 1;
        } else {
            $contentcounts = $contentcount;

        }


        for ($i = 1; $i <= $contentcounts; $i++) {

            $data[$i - 1]['story'] = $story;
            $data[$i - 1]['people'] = $_SESSION['uid'];
            $data[$i - 1]['questiontime'] = $questiontime;
            $data[$i - 1]['contentcount'] = $contentcount;
            $data[$i - 1]['did'] = '，'.$knowledge.'，';       //知识点
            $data[$i - 1]['kid'] = $kid.',';
            $data[$i - 1]['zid'] = $zid.',';
            $data[$i - 1]['jid'] = $jid.',';
            $data[$i - 1]['model'] = $model[0];
            $content = 'content' . $i;
            $data[$i - 1]['content'] = isset($_POST[$content]) ? $_POST[$content] : '';

            //var_dump($data['content']);die;

            $aa = 'aa' . $i;
            $data[$i - 1]['aa'] = isset($_POST[$aa]) ? $_POST[$aa] : '';
            $bb = 'bb' . $i;
            $data[$i - 1]['bb'] = isset($_POST[$bb]) ? $_POST[$bb] : '';
            $cc = 'cc' . $i;
            $data[$i - 1]['cc'] = isset($_POST[$cc]) ? $_POST[$cc] : '';
            $dd = 'dd' . $i;
            $data[$i - 1]['dd'] = isset($_POST[$dd]) ? $_POST[$dd] : '';
            $multiple = 'multiple' . $i;     //单选多选
            $data[$i - 1]['multiple'] = isset($_POST[$multiple][0]) ? $_POST[$multiple][0] : '';
            $ans = 'answer' . $i;
            // var_dump($ans);die;
            $answers = isset($_POST[$ans]) ? $_POST[$ans] : '';
            //var_dump($data[$i-1]['multiple']);die;
           
            $mul = $data[$i - 1]['multiple'];
             //var_dump($mul);die;
            if ($mul[0] == 1) {
                $anscount=  count($answers);
               
                if ($anscount > 1) {
                    $this->error('所选答案数与选择单双选项不一致!');
                }
               // var_dump($anscount);die;
                switch ($answers[0]) {
                    case 'aa':
                        $data[$i - 1]['answer'] = $data[$i - 1]['aa'];
                        $data[$i - 1]['answern'] = 'a';
                        break;
                    case 'bb':
                        $data[$i - 1]['answer'] = $data[$i - 1]['bb'];
                        $data[$i - 1]['answern'] = 'b';
                        break;
                    case 'cc':
                        $data[$i - 1]['answer'] = $data[$i - 1]['cc'];
                        $data[$i - 1]['answern'] = 'c';
                        break;
                    default:
                        $data[$i - 1]['answer'] = $data[$i - 1]['dd'];
                        $data[$i - 1]['answern'] = 'd';
                }
            } else {
                $answer = '';
                $answern = '';
                //var_dump($answers[1]);die;  
                if(empty($answers[1])){
                           $this->error('所选答案数与选择单双选项不一致...'); 
                        }


                foreach ($answers as $key => $value) {
                    if ($value == 'aa') {
                        $answer.=$data[$i - 1]['aa'] . '+';
                        $answern.='a' . '+';
                    }
                    if ($value == 'bb') {
                        $answer.=$data[$i - 1]['bb'] . '+';
                        $answern.='b' . '+';
                    }
                    if ($value == 'cc') {
                        $answer.=$data[$i - 1]['cc'] . '+';
                        $answern.='c' . '+';
                    }
                    if ($value == 'dd') {
                        $answer.=$data[$i - 1]['dd'] . '+';
                        $answern.='d' . '+';
                    }
                }

                $data[$i - 1]['answer'] = substr($answer, 0, -1);
                $data[$i - 1]['answern'] = substr($answern, 0, -1);
                //var_dump($data[$i-1]['answer']);die;
            }

            $keywords = 'keywords' . $i;
            $data[$i - 1]['keywords'] = isset($_POST[$keywords]) ? $_POST[$keywords] : '0';
            $difficulty = 'difficulty' . $i;
            $data[$i - 1]['difficulty'] = isset($_POST[$difficulty][0]) ? $_POST[$difficulty][0] : '0';
            $explain = 'explain' . $i;
            $data[$i - 1]['explain'] = isset($_POST[$explain]) ? $_POST[$explain] : '0';
            $data[$i - 1]['addtime'] = time();
            $aexplain = 'aexplain' . $i;
            $data[$i - 1]['aexplain'] = isset($_POST[$aexplain]) ? $_POST[$aexplain] : '0';
            $bexplain = 'bexplain' . $i;
            $data[$i - 1]['bexplain'] = isset($_POST[$bexplain]) ? $_POST[$bexplain] : '0';
            $cexplain = 'cexplain' . $i;
            $data[$i - 1]['cexplain'] = isset($_POST[$cexplain]) ? $_POST[$cexplain] : '0';
            $dexplain = 'dexplain' . $i;
            $data[$i - 1]['dexplain'] = isset($_POST[$dexplain]) ? $_POST[$dexplain] : '0';

        }

        $datas=array_walk($data,"fun");    //替换数组中元素为0 的。因为tp的addall不能存在空元素否则会报错。$datas返回true或者false
        //var_dump($data);die;

        if ($uid) {

            $data = array_reduce($data, 'array_merge', array());
            $sel['id'] = $uid;
            $story = M('question')->where($sel)->find();   //判断再修改时是否修改了故事内容
            // var_dump($story);die;
            if ($story['story'] != $data['story']) {
                $map['story'] = $story['story'];
                $storys = M('question')->where($map)->select();
                //var_dump($storys);die;
                foreach ($storys as $key => $value) {
                    $gep['story'] = $data['story'];
                    $gep['id'] = $value['id'];
                    //var_dump($sto['id']);die;
                    $newstory = M('question')->data($gep)->save();
                }
            }
            //var_dump($data);die;
            $question = M('question')->where('id=' . $uid)->data($data)->save();
            //var_dump($question);die;
            if ($question) {
                addlog('编辑故事题目：' . $data[1]['content']);
                $this->success('恭喜，故事题目修改成功！');
                exit(0);
            } else {
                $this->success('未修改内容');
            }
        } else {
     
            $count = count($data) - 1;
            if (empty($data[$count]['content'])) {
                $this->error('所填题目数与所录题目数不匹配');
            }
            //var_dump($data);die;
            if ($count == 0) {//判断录的故事题数。用add还是addall
                $question = M('question')->add($data);
            } else {
                $question = M('question')->addAll($data);
            }


            if ($question) {
                addlog('新增故事题目：' . $data[1]['content']);
                $this->success('恭喜，故事题目新增成功！');
                exit(0);
            } else {
                $this->success('故事题目新增失败');
            }
        }
    }

    public function getRegion() {
        $Region = M("tree");
        $str = '<option>请选择</option>';
        if (I('post.type') == 'province') {
            $map['pid'] = 0;
        } elseif (I('post.id') == 0) {
            $this->ajaxReturn($str);
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
