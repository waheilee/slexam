<?php
/**
 *
 * 功能说明：前台控制器演示。
 *
 **/
namespace Wx\Controller;
use Home\Controller\AnswerController;

class AnswersController extends AnswerController
{   
    //遍历数组，组合成json类型
    protected function ergodic($res,$type=1)
    {   
        $data = array();
        if($type == 1){
            foreach($res as $k=>$v){
                $data[$k]['name'] = $v['content'];
                $data[$k]['sign'] = $v['id'];
                $data[$k]['right'] = $v['answern'];
                $data[$k]['option'][0]['answer'] = $v['aa'];
                $data[$k]['option'][0]['sign'] = 'a';
                $data[$k]['option'][1]['answer'] = $v['bb'];
                $data[$k]['option'][1]['sign'] = 'b';
                $data[$k]['option'][2]['answer'] = $v['cc'];
                $data[$k]['option'][2]['sign'] = 'c';
                $data[$k]['option'][3]['answer'] = $v['dd'];
                $data[$k]['option'][3]['sign'] = 'd';
            }
        }else{
            $i = 0;
            foreach($res as $k=>$v){
                if(count($v) != count($v,1)){
                    foreach($v as $z=>$c){
                        $data[$i]['name'] = $k;
                        $data[$i]['option'][$z]['name'] = $c['content'];
                        $data[$i]['option'][$z]['sign'] = $c['id'];
                        $data[$i]['option'][$z]['right'] = $v['answern'];
                        $data[$i]['option'][$z]['option'][0]['answer'] = $c['aa'];
                        $data[$i]['option'][$z]['option'][0]['sign'] = 'a';
                        $data[$i]['option'][$z]['option'][1]['answer'] = $c['bb'];
                        $data[$i]['option'][$z]['option'][1]['sign'] = 'b';
                        $data[$i]['option'][$z]['option'][2]['answer'] = $c['cc'];
                        $data[$i]['option'][$z]['option'][2]['sign'] = 'c';
                        $data[$i]['option'][$z]['option'][3]['answer'] = $c['dd'];
                        $data[$i]['option'][$z]['option'][3]['sign'] = 'd';
                    } 
                }
                $i ++;
            }
        }
        $data = json_encode($data);
        return $data;
    }

    //验证得分
    public function Score()
    {   

        if(I('post.token') != session('token')){
            $this->ajaxReturn('false');
        }else{
            $_SESSION['token'] = null;

            $examinDb = M('examin');
            $scoreDb = M('score');
            
            // 单选得分
            $res = $this->getScore(I('post.radio'));

            //多选得分
            $res = $this->getScore(I('post.check'),$res['num'],$res['sum'],$res['story_error'],$res['sInfo'],$res['error'],$res['Info'],$res['paper']);

            //不定项选择
            $res = $this->getScore(I('post.term'),$res['num'],$res['sum'],$res['story_error'],$res['sInfo'],$res['error'],$res['Info'],$res['paper']);

            // 故事题
            $res = $this->getScore(I('post.story'),$res['num'],$res['sum'],$res['story_error'],$res['sInfo'],$res['error'],$res['Info'],$res['paper']);

            $num = $res['num'];
            $sum = $res['sum'];
            $error = $res['error'];
            $Info = $res['Info'];
            $story_error = $res['story_error'];
            $sInfo = $res['sInfo'];
            $paper = $res['paper'];

            //计算题个数
            $count = arrCount($paper['radio']) + arrCount($paper['check']) + arrCount($paper['term']) + arrCount($paper['story']);

            if(I('post.exmainid')){
                $res = I('post.exmainid');
                $examinDb->where('id='.I('post.exmainid'))->setInc('usenum'); 
            }else{
                $data['people'] = $this->getUserId();
                $data['paper1'] = $paper['radio'];
                $data['paper2'] = $paper['check'];
                $data['paper3'] = $paper['term'];
                $data['story'] = $paper['story'];
                $data['nickname'] = I('post.name');
                $data['kid'] = I('post.kid');
                $data['zid'] = I('post.zid');
                $data['jid'] = I('post.jid');
                $data['num'] = $count;
                $data['knum'] = count(explode(',',trim(I('post.kid'),',')));
                $data['did'] = I('post.did');
                $data['style'] = I('post.range');
                $data['difficulty'] = I('post.diffi');
                if(I('post.kid')){
                    $data['type'] = 3;
                }else{
                    $data['type'] = 2;
                }
                $data['createtime'] = time();
                $res = $examinDb->add($data);
            }
            if($res){
                $result['people'] = $this->getUserId();
                $result['exmainId'] = $res;
                $result['score'] = $sum;
                $result['usetime'] = I('post.time');
                $result['sntime'] = I('post.usetime');
                $result['error_num'] = $count-$num;
                $result['error'] = $error;
                $result['errorinfo'] = trim($Info,'/');
                $result['story_error'] = $story_error;
                $result['s_info'] = trim($sInfo,'/');
                $result['time'] = time();
            
                $res = $scoreDb->add($result);
                if($res){
                    $this->ajaxReturn($res);
                }
            }
            
        }
    }

    //验证各项题答案得分
    protected function getScore($data,$sum=0,$num=0,$story_error='',$sInfo='',$error='',$Info='',$paper = array())
    {   

        foreach($data as $v){
            $res = $this->getSum($v,$sum,$num,'',$error_num);

            if($res['type'] == 'radio'){
                $paper['radio'] .= $res['id'].',';      //单选题目id
            }elseif($res['type'] == 'check'){
                $paper['check'] .= $res['id'].',';      //多选题目id
            }elseif($res['type'] == 'story'){
                $paper['story'] .= $res['id'].',';      //多选题目id
            }else{
                $paper['term'] .= $res['id'].',';       //不定项题目id
            }

            if($res['type'] == 'story'){
                $story_error .= $res['error'];
                $sInfo .= $res['errorInfo'];
            }else{
                $error .= $res['error'];
                $Info .= $res['errorInfo'];
            }

            $sum = $res['sum'];         //答题得分
            $num = $res['num'];         //正确答题数
            // $error_num = $res['error_num'];
        }

        $data['paper'] = $paper;
        $data['sum'] = $sum;
        $data['num'] = $num;
        // $data['error_num'] = $error_num;
        $data['error'] = $error;
        $data['Info'] = $Info;
        $data['story_error'] = $story_error;
        $data['sInfo'] = $sInfo;

        return $data;
    }

    //获取总的分
    protected function getSum($data,$sum,$num,$error,$error_num)
    {
        $questDb = M('quest');
        $storyDb = M('question');

        $result['id'] = $data['name'];      //题目id
        $result['type'] = $data['type'];        //题目类型
        if($data['type'] == 'story'){
            $res = $storyDb->where('id='.$data['name'])->find();
        }else{
            $res = $questDb->where('id='.$data['name'])->find();
        }

        //判断是单选还是多选
        if($res['multiple'] == 1){
            if($data['answer'] == $res['answern']){
                $num ++;
                if($data['type'] == 'radio'){
                    $result['sum'] = $sum + 1;
                }else{
                    $result['sum'] = $sum + 2;
                } 
            }else{
                $error_num ++;
                $result['sum'] = $sum;
                $result['error'] .= $data['name'].',';
                $result['errorInfo'] = $data['name'].'_' .$data['answer'].'/';
            }

        }else{

            $anw = explode(',',$data['answer']);
            sort($anw);
            $anw2 = explode('+',$res['answern']);
            sort($anw2);
            //当$anw == $anw2为真时说明答案不一样，为错误
            if($anw == $anw2){
                $num ++;
                $result['sum'] = $sum + 2;

            }else{
                $error_num ++;
                $result['sum'] = $sum;
                $result['error'] .= $data['name'].',';
                $result['errorInfo'] .= $data['name'].'_' .$data['answer'].'/';
            }
        }
        $result['num'] = $num;
        $result['error_num'] = $error_num;
        return $result;
    }

    //答案结果页
    public function mark()
    {   
        //查询得分信息
        $scoreDb = M('score');
        $score = $scoreDb->where('id='.I('post.answer'))->find();
        $this->assign('score',$score);
        

        // 查询试卷信息
        $examinDb = M('examin');
        $examin = $examinDb->where('id='.$score['exmainid'])->find();
        $this->assign('examin',$examin);    //分配试卷名

        //查询试卷中各题数
        $r_count = explode(',',trim($examin['paper1'],','));
        $this->assign('r_count',count(array_filter($r_count)));

        $c_count = explode(',',trim($examin['paper2'],','));
        $this->assign('c_count',count(array_filter($c_count)));

        $t_count = explode(',',trim($examin['paper3'],','));

        $s_count = explode(',',trim($examin['story'],','));
        $this->assign('t_count',count(array_filter($t_count))+count(array_filter($s_count)));

        $this->assign('right',$examin['num']-$score['error_num']); //分配答对题数

        //查询打榜排名
        $arr['score'] = array('gt',$score['score']);
        $arr['sntime'] = array('lt',$score['sntime']);
        $arr['exmainId'] = $score['exmainid'];
        $arr['type'] = 2;
        $count = $scoreDb->where($arr)->count();

        $this->assign('ranking',$count+1);

        //查询本卷最佳
        $good = $scoreDb->field('score,usetime')->where('exmainid='.$examin['id'])->order('score desc,usetime')->limit(1)->find();
        $this->assign('good',$good);

        //查询该试卷中各科目得分
        $questDb = M('quest');
        $where['id'] = array('in',$examin['paper1'].$examin['paper2'].$examin['paper3']);

        //查询试卷中各科目下题数多少道
        $count = $questDb->field('kid,count(*) as count')->where($where)->group('kid')->select();


        // 查询错题中各科目题数多少道
        $eWhere['id'] = array('in',$score['error']);
        $error = $questDb->field('kid,count(*) as count')->where($eWhere)->group('kid')->select();

        if($examin['story']){
            //查询故事题中所属科目
            $storyDb = M('question');
            $sWhere['id'] = array('in',$examin['story']);
            $scount = $storyDb->field('kid,count(*) as count')->where($sWhere)->group('kid')->select();
        }
        if($score['story_error']){
            // 查询故事题错题中各科目题数多少道
            $sWhere['id'] = array('in',$score['story_error']);
            $serror = $storyDb->field('kid,count(*) as count')->where($sWhere)->group('kid')->select();
        }
        //获取各科目错误题占比
        foreach($serror as $v){
            $kid = explode(',',trim($v['kid'],','));
            foreach($kid as $z){
                $data[$z] += $v['count'];
            }
             
        }

        foreach($error as $v){
            $kid = explode(',',trim($v['kid'],','));
            foreach($kid as $z){
                $data[$z] += $v['count'];
            }
        }

       
        //获取各科目题数和
        foreach($scount as $v){
            $kid = explode(',',trim($v['kid'],','));
            foreach($kid as $z){
                if($z){
                    $result[$z]['count'] += $v['count'];
                }
            }
            
        }


        foreach($count as $v){
            $kid = explode(',',trim($v['kid'],','));

            foreach($kid as $z){
                if($z){
                    $result[$z]['count'] += $v['count'];
                }
            }

        }
        
        //获取试卷包含科目及错题占比
        foreach($result as $k=>$v){
            $result[$k]['error'] = $data[$k];
            $inc[$k]['ratio'] = number_format($v['count']/$examin['num'],2)*100;    //获取题目中科目占比
        }

        //查询课程,获取图形数据
        $treeDb = M('tree');
        $tree = $treeDb->field('name,id')->where('pid=0')->select();
        foreach($tree as $v){   
            if($result[$v['id']]['count']){
                // $record['course'][] = $v['name']."(".$result[$v['id']]['count']."道)";
                $record['course'][] = $v['name'];
                $record['data'][] = number_format(($result[$v['id']]['count']-$result[$v['id']]['error']) / $result[$v['id']]['count'],2) * 100;
            }

            $question[$v['id']] = $v['name'];
        }

        $this->assign('record',json_encode($record));

        //遍历inc,获取试卷中各科目题数占比

        foreach($inc as $k=>$v){
            if($question[$k]){
                $show[$k]['name'] = $question[$k];
                $show[$k]['percent'] = $v['ratio'];
                $show[$k]['error'] = $result[$k]['error'];
            }
        }
        $this->assign('show',$show);
        
        $this->display();
    }

    // 自定义试题封面
    public function show()
    {
        $this->display();
    }

    //历史题库
    public function lists()
    {   
        //获取科目
        $treeDb = M('tree');
        $tree = $treeDb->field('id,name')->where('pid=0')->select();
        $this->assign('tree',$tree);

        //查询试卷
        $examinDb = M('examin');
        $where['type'] = 3;
        $count = $examinDb->where($where)->count();
        $this->assign('count',$count);

        $examin = $examinDb->field('id,nickname,difficulty,usenum')->where($where)->order('createtime desc')->page('0,5')->select();
        foreach($examin as &$v){
            $res = $this->getAssess($v['id']);
            $v['star'] = $res['star'];
            $v['assess'] = $res['assess'];
            $v['score'] = $res['score'];
            $v['usetime'] = $res['usetime'];
        }

        $this->assign('examin',$examin);
        $this->display();
    }

    public function getTree()
    {
        $treeDb = M('tree');

        $where['pid'] = array(in,trim(I('post.id'),','));

        if(I('post.id') == ''){
            $this->ajaxReturn('');
        }else{
            if(I('post.type') == 'd'){
                $tree = $treeDb->field('name')->where($where)->select();
                $arr = array();
                $i = 0;
                foreach($tree as $v){
                    $res = explode('，',$v['name']);
                    foreach($res as $z){
                        if(!in_array($z,$arr)) {
                            $data[$i]['id'] = $z;
                            $data[$i]['name'] = $z;
                            $arr[] = $z;
                            $i ++ ;
                        }   
                    }
                     
                }
            }else{
                $tree = $treeDb->field('id,name')->where($where)->select(); 
                $i = 0;
                foreach($tree as $v){
                    $data[$i]['name'] = $v['name'];
                    $data[$i]['id'] = $v['id'];
                    $i++;
                }
            }
                
            $this->ajaxReturn(json_encode($data));
        }
    }

    //ajax获取条件之后数据
    public function getRes()
    {
        $examinDb = M('examin');
        $where['type'] = 3;
        $p = I('post.page');

        if(I('post.kid')){
            $where['kid'] = $this->Bination(I('post.kid'));
        } 
        if(I('post.zid')){
            $where['zid'] = $this->Bination(I('post.zid'));
        }
        if(I('post.jid')){
            $where['jid'] = $this->Bination(I('post.jid'));
        }
        if(I('post.did')){
            $where['did'] = $this->Bination(I('post.did'),'did');
        }
        if(I('post.type')){
            $where['type'] = I('post.type');
        }
        if(I('post.difficulty')){
            $where['difficulty'] = I('post.difficulty');
        }

        if(!I('post.kid') && !I('post.difficulty') && !I('post.type')){
            $p = 2;
            $data['type'] = 'all';
            $data['page'] = $p;
        }else{
            $data['page'] = $p + 1;
        }

        $result = $examinDb->field('id,nickname,difficulty,usenum')->where($where)->order('createtime desc')->page($p.',5')->select();

        $count = $examinDb->where($where)->count();

        foreach($result as $v){
            $res = $this->getAssess($v['id']);
            $score = $res['score'] ? $res['score'] : 0;
            $str .= '<li><div class="oe-topmenu"><div class="wrap"><div class="left">综合评星：<span class="startnum" data="'.$res['star'].'"></span></div><div class="right"><a href="javascript:void(0)" onclick="shopNav(\'dzkt\')">订制课堂</a><span>丨</span><a href="javascript:void(0)">分享试卷</a><span>丨</span><a href="javascript:void(0)" class="blue">查看打榜</a></div></div></div><div class="oe-middlename wrap"><div class="left"><img src="'.__ROOT__.'/Public/Wx/images/makelist/zxpaper.png" alt=""/><p class="oem-name">'.$v['nickname'].'</p></div><div class="left oe-num"><span>'.$score.'分</span></div><div class="right"><a href="'.U('Answers/examin',array('code'=>base64_encode($v['id']))).'"><img src="'.__ROOT__.'/Public/Wx/images/yearoldexam/penanswer.png" alt=""/></a></div></div><div class="oe-bottom"><div class="wrap"><span>难易程度：'.checkType($v['difficulty']).'</span><span>答题人数：'.$v['usenum'].'人</span><span>最高得分用时：'.$res['usetime'].'</span></div></div></li>';
        }

        $data['msg'] = $str;
        $data['count'] = $count;
        

        $this->ajaxReturn($data);
    }

     //拆分章、节、知识点，组合where条件
    protected function Bination($data,$type='')
    {
        $data = explode(',',trim($data,','));

        if($type == 'did'){
            foreach($data as $v){
                $str .= '%，'.$v.'，%_';
            }
        }else{
            foreach($data as $v){
                $str .= '%,'.$v.',%_';
            }
        }
        $str = explode('_',trim($str,'_'));     //如需匹配空的将trim去掉即可

        if(count($data)>1){
            $where =array('like',$str,'OR');
        }else{
            $where = array('like',$str);
        }

        return $where;
    }

    protected function getAssess($id)
    {   
        $order['score'] = 'desc';
        $order['sntime'] = 'asc';

        //查询试卷最高得分和用时
        $scoreDb = M('score');
        $score = $scoreDb->field('usetime,score')->where('exmainid='.$id)->order($order)->find();
        

        //查询试卷评价和得星
        $assessDb = M('assess');

        $star = $assessDb->where('e_id='.$id)->avg('star');
        $res = $assessDb->field('assess')->where('e_id='.$id)->select();

        foreach($res as $v){
            $assess = explode('__',$v['assess']);
            foreach($assess as $z){
                $data[$z] += 1;
            }
        }
        
        $result['star'] = ceil($star);
        $result['assess'] = max(array_flip($data));
        $result['score'] = $score['score'];
        $result['usetime'] = $score['usetime'];

        return $result;
    }
}