<?php
/**
 *
 * 功能说明：前台控制器演示。
 *
 **/
namespace Home\Controller;
use Home\Common\Answer\again;
use Home\Common\Answer\lists;
use Home\Common\Answer\mistake;
class AnswerController extends ComController
{	
    public function index()
    {   
        $questDb = M('quest');
        
        if(I('post.kid')){
            $where['kid'] = $this->Bination(I('post.kid'));
            
            $this->assign('kid',I('post.kid'));

            $this->assign('main','自定义模拟题库');
        }else{
        	$this->assign('main','模拟题库');
        }

 
        if(I('post.zid')){
            $where['zid'] = $this->Bination(I('post.zid'));
            $this->assign('zid',I('post.zid'));
        }
        if(I('post.jid')){
            $where['jid'] = $this->Bination(I('post.jid'));
            $this->assign('jid',I('post.jid'));
        }

        if(I('post.did')){
            $where['did'] = $this->Bination(I('post.did'),'did');
            $this->assign('did',I('post.did'));
        }

        if(I('post.nycd')){
            $where['difficulty'] = I('post.nycd');
            $this->assign('diffi',I('post.nycd'));
            $this->assign('difys',checkType(I('post.nycd')));
        }

        //前50道随机单选题
        $where['multiple'] = 1;
        //模拟试卷获取单选题知识点个数
        $radio = $questDb->field('id,did,content,aa,bb,cc,dd,answern')->where($where)->order('rand()')->limit(50)->select();

        $id = $this->getId($radio);

        $radio = $this->ergodic($radio);
        $this->assign('radio',$radio);

        //随机35道多项选择题
        $where['multiple'] = 2;
        //模拟试卷获取多项选择题知识点个数
        $check = $questDb->where($where)->order('rand()')->limit(35)->select();

        $ids = $this->getId($check,$id['did']);

        $check = $this->ergodic($check);
        $this->assign('check',$check);

        //随机数0-2，用于获取故事题数量
        $num = rand(0,2);
        unset($where['multiple']);
        $historys = json_encode(array());
        if($num > 0){
            $history = $this->getHistory($num,$where);
            $h_count = $history['count'];

            $historys = $this->ergodic($history['data'],2);
        }
        $this->assign('history',$historys);
        //随机(15-故事题数量)道不定项选择题数
        $num = 15 - $history['num'];
        $id = implode(',', $id['id']).','.implode(',', $ids['id']);

        $where['id'] = array('not in',$id);
        $term = $questDb->where($where)->order('rand()')->limit($num)->select();

        $tid = $this->getId($term,$ids['did']);

        $t_count = count($tid['did']);

        $term = $this->ergodic($term);

        $this->assign('term',$term);

        //获取数据库试卷最大id
        $examinDb = M('examin');
    	$treeDb = M('tree');	
        //随机试卷名称
        if(I('post.kid')){
            $examin = $examinDb->field('id')->where('type=3')->order('id desc')->limit(1)->find();
            $ddno = $examin['id']+1;
            if(count(explode(',',trim(I('post.kid'),','))) > 1){
                $nickname = "综合科目自定义试卷_".date("Ymd",time()).$ddno;
            }else{
                $km = $treeDb->where('id='.trim(I('post.kid'),','))->getField('name');
                $nickname = $km."自定义试卷_".date("Ymd",time()).$ddno;
            }
    	    
        }else{
            $examin = $examinDb->field('id')->where('type=2')->order('id desc')->limit(1)->find();
            $ddno = $examin['id']+1;
            $nickname = "模拟试卷_".date("Ymd",time()).$ddno;
            //模拟试卷卷数
            $this->assign('range',I('post.range'));
        }
    	$this->assign('nickname',$nickname);

    	//获取左侧试卷列表
    	$l_examin = $this->getLnav();
    	$this->assign('l_examin',$l_examin);

    	//分配知识点个数
    	$count = $t_count + $h_count;

    	$this->assign('t_count',$count);

        $token = base64_encode(time());
        session('token',$token);
        $this->assign('token',$token);

        $this->display();
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

    //遍历数组，组合成json类型
    protected function ergodic($res,$type=1)
    {   
        $data = array();
        if($type == 1){
            foreach($res as $k=>$v){
                $data[$k]['name'] = $v['content'];
                $data[$k]['sign'] = $v['id'];
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
                        $data[$i]['option'][$z]['type'] = 'story';
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

    //获取数据id和知识点数组方法
    protected function getId($res,$did = '')
    {	

    	$data = array();
        foreach($res as $v){
        	if(!in_array($v['did'],$did)){
        		$did[] = $v['did'];
        	}
            $data['id'][] = $v['id'];
        } 
        $data['did'] = $did;
        return $data;
    }

    //获取随机故事题数
    protected function getHistory($num,$where)
    {   

        $questtionDb = M('question');
        $history = $questtionDb->where($where)->group('story')->order('rand()')->limit($num)->select();

        if(!$history){
            return '';
        }

        $num = 0;
        //获取题目信息
        foreach($history as $v){
            $data[] = $v['story'];
            $count[] = $v['contentcount'];
            $num += $v['contentcount']; //获取故事题总题数
        }

        //查询故事题下所有题目
        $arr['contentcount'] = array('in',implode(',',$count));
        $arr['story'] = array('in',implode(',',$data));

        $history = $questtionDb->where($arr)->select();

        //获取该故事题所有项
        foreach($history as $v){
        	if(!in_array($v['kid'],$arr)){
        		$arr[] = $v['kid'];
        	}
            $res[$v['story']][] = $v;
        }
        
        $result['num'] = $num;
        $result['data'] = $res;
        $result['count'] = count($arr);

        return $result;
    }

    //获取故事题下总分数
    protected function getSn($res,$sum=0)
    {   
        global $id;
        foreach($res as $v){    
            if(count($v) != count($v,1)){
                $this->getSn($v,$sum);
            }else{
                $id[] = $v['id'];
            }
        }
        return $id;
    }

    //获取用户id
    public function getUserId()
    {
    	// return cookie('userid');
        return 1;
    }

    //验证得分
    public function Score()
    {	

        if(I('post.token') != session('token')){
            $this->ajaxReturn('false');
        }else{
            $_SESSION['token'] = null;

        	$answer = I('post.answer');
        	$examinDb = M('examin');
        	$scoreDb = M('score');
        	$sum = 0;
            $num = 0;
        	$error_num = 0;

        	foreach($answer as $v){
        		$res = $this->getSum($v,$sum,$num,'',$error_num);

        		if($res['type'] == 'radio'){
        			$paper['radio'] .= $res['id'].','; 		//单选题目id
        		}elseif($res['type'] == 'check'){
        			$paper['check'] .= $res['id'].','; 		//多选题目id
        		}elseif($res['type'] == 'story'){
        			$paper['story'] .= $res['id'].','; 		//多选题目id
        		}else{
        			$paper['term'] .= $res['id'].','; 		//不定项题目id
        		}

        		if($res['type'] == 'story'){
        			$story_error .= $res['error'];
        			$sInfo .= '/'.$res['errorInfo'];
        		}else{
        			$error .= $res['error'];
        			$Info .= '/'.$res['errorInfo'];
        		}
        		$sum = $res['sum'];			//答题得分
        		$num = $res['num'];			//正确答题数
                // $error_num = $res['error_num'];
        			
        	}
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
                $data['knum'] = count(explode(',',trim(I('post.kid'),',')));
                $data['zid'] = I('post.zid');
    	    	$data['jid'] = I('post.jid');
                $data['num'] = $count;
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

    //获取总的分
    protected function getSum($data,$sum,$num,$error,$error_num)
    {
    	$questDb = M('quest');
    	$storyDb = M('question');

    	$result['id'] = $data['name'];		//题目id
    	$result['type'] = $data['type'];		//题目类型
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
    			$result['errorInfo'] = $data['name'].'_' .$data['answer'];
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
    			$result['errorInfo'] .= $data['name'].'_' .$data['answer'];
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
    	$this->assign('examin',$examin);	//分配试卷名

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
    	$arr['exmainid'] = $score['examinid'];
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
    		$inc[$k]['ratio'] = number_format($v['count']/$examin['num'],2)*100;	//获取题目中科目占比
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
    		$show[$k]['name'] = $question[$k];
    		$show[$k]['percent'] = $v['ratio'];
    		$show[$k]['error'] = $result[$k]['error'];
    	}

    	$this->assign('show',$show);
    	
    	$this->display();
    }

    //获取左侧试卷列表
    protected function getLnav()
    {
    	$examinDb = M('examin');
    	$examin = $examinDb->field('nickname,id')->order('rand()')->limit(12)->select();

    	return $examin;
    }

    //拆分错误答案选项
    protected function getError($data)
    {
    	$error = explode('/',$data);

    	foreach($error as $v){
			$res = explode('_',$v);
			$info[$res[0]] = $res[1];
		}

		return $info;
    }

    public function wrong()
    {
    	$questDb = M('quest');
    	$storyDb = M('question');
    	$scoreDb = M('score');

    	$where['id'] = I('post.id');
    	$score = $scoreDb->where($where)->find();

    	//查询试卷名
    	$examinDb = M('examin');
    	$nickname = $examinDb->where('id='.$score['exmainid'])->getField("nickname");
    	$this->assign('nickname',$nickname);

    	//获取试卷类型
    	$type = $examinDb->where('id='.$score['exmainid'])->getField("type");
    	$this->assign('main',getName($type));

    	$serror = explode('/',$score['s_info']);

    	//遍历错题答案，获取每题对应的选项
    	if($score['errorinfo']){
    		$derror = $this->getError($score['errorinfo']);
    	}
    	
    	if($score['s_info']){
    		$serror = $this->getError($score['s_info']);
    	}
  
    	$where['id'] = array('in',$score['error']);
    	$quest = $questDb->field('id,content,aa,bb,cc,dd,answern,aexplain,bexplain,cexplain,dexplain,multiple')->where($where)->select();

        $data = array();

    	foreach($quest as $k=>$v){
    		$data[$k]['name'] = $v['content'];
            $data[$k]['id'] = $v['id'];
    		if($v['multiple'] == 2){
    			$answer = str_replace('+','　',$v['answern']);
    			$data[$k]['sign'] = strtoupper($answer);
    		}else{
    			$data[$k]['sign'] = strtoupper($v['answern']);
    		}
    		
    		$data[$k]['option'][0]['answer'] = $v['aa'];
            $data[$k]['option'][0]['sign'] = $v['aexplain'];
            $data[$k]['option'][0]['active'] = checkIsset('a',$derror[$v['id']]);

            $data[$k]['option'][1]['answer'] = $v['bb'];
            $data[$k]['option'][1]['sign'] = $v['bexplain'];
            $data[$k]['option'][1]['active'] = checkIsset('b',$derror[$v['id']]);

            $data[$k]['option'][2]['answer'] = $v['cc'];
            $data[$k]['option'][2]['sign'] = $v['cexplain'];
            $data[$k]['option'][2]['active'] = checkIsset('c',$derror[$v['id']]);

            $data[$k]['option'][3]['answer'] = $v['dd'];
            $data[$k]['option'][3]['sign'] = $v['dexplain'];
            $data[$k]['option'][3]['active'] = checkIsset('d',$derror[$v['id']]);
    	}

    	$this->assign('data',json_encode($data));

    	//查询故事错题
        $result = array();
    	if($score['story_error']){

	    	$where['id'] = array('in',$score['story_error']);
	    	$story = $storyDb->field('id,story,content,aa,bb,cc,dd,answern,aexplain,bexplain,cexplain,dexplain,multiple')->where($where)->select();
	    	
	    	foreach($story as $v){
	    		$res[$v['story']][] = $v;        
	    	}

	    	$i = 0;
	    	foreach($res as $k=>$v){
	    		if(count($v) != count($v,1)){
                    foreach($v as $z=>$c){
                        $result[$i]['name'] = $k;
                        $result[$i]['option'][$z]['name'] = $c['content'];
                        if($v['multiple'] == 2){
			    			$answer = str_replace('+','　',$c['answern']);
			    			$result[$i]['option'][$z]['sign'] = strtoupper($answer);
			    		}else{
			    			$result[$i]['option'][$z]['sign'] = strtoupper($c['answern']);
			    		}

                        $result[$i]['option'][$z]['option'][0]['answer'] = $c['aa'];
                        $result[$i]['option'][$z]['option'][0]['sign'] = $c['aexplain'] ? $c['aexplain'] : '无解析';
                        $result[$i]['option'][$z]['option'][0]['active'] = checkIsset('a',$serror[$c['id']]);

                        $result[$i]['option'][$z]['option'][1]['answer'] = $c['bb'];
                        $result[$i]['option'][$z]['option'][1]['sign'] = $c['bexplain'] ? $c['bexplain'] : '无解析';
                        $result[$i]['option'][$z]['option'][1]['active'] = checkIsset('b',$serror[$c['id']]);

                        $result[$i]['option'][$z]['option'][2]['answer'] = $c['cc'];
                        $result[$i]['option'][$z]['option'][2]['sign'] = $c['cexplain']? $c['cexplain'] : '无解析';
                        $result[$i]['option'][$z]['option'][2]['active'] = checkIsset('c',$serror[$c['id']]);

                        $result[$i]['option'][$z]['option'][3]['answer'] = $c['dd'];
                        $result[$i]['option'][$z]['option'][3]['sign'] = $c['dexplain']? $c['dexplain'] : '无解析';
                        $result[$i]['option'][$z]['option'][3]['active'] = checkIsset('d',$serror[$c['id']]);
                    } 
                }
                $i ++;
	    	}
    	}
    	$this->assign('story',json_encode($result));

    	//获取左侧试卷列表
    	$l_examin = $this->getLnav();
    	$this->assign('l_examin',$l_examin);

    	$this->display();
    }

    // 已有试卷答题
    public function examin()
    {
    	$examinDb = M('examin');
    	$id = base64_decode(I('get.code'));

    	$examin = $examinDb->where('id='.$id)->find();
    	$this->assign('nickname',$examin['nickname']);
    	$this->assign('main',getName($examin['type']));
    	$this->assign('difys',checkType($examin['difficulty']));
    	$this->assign('examinid',$examin['id']);

    	$questDb = M('quest');
    	$storyDb = M('question');
    	// 单选题
    	$where['id'] = array('in',empty($examin['paper1']) ? '' : $examin['paper1']);

    	$radio = $questDb->where($where)->select();
    	$id = $this->getId($radio);

        $radio = $this->ergodic($radio);
        $this->assign('radio',$radio);

    	//多选题
    	$where['id'] = array('in',empty($examin['paper2']) ? '' : $examin['paper2']);
    	$check = $questDb->where($where)->select();
    	$ids = $this->getId($check,$id['did']);

        $check = $this->ergodic($check);
        $this->assign('check',$check);

    	//不定项选择题
    	$where['id'] = array('in',empty($examin['paper3']) ? '' : $examin['paper3']);
    	$term = $questDb->where($where)->select();
    	$tid = $this->getId($term,$ids['did']);

        $t_count = count($tid['did']);

        $term = $this->ergodic($term);
        $this->assign('term',$term);

    	//故事题
    	$where['id'] = array('in',empty($examin['story']) ? '' : $examin['story']);
    	$story = $storyDb->where($where)->select();

    	$arr = array();
    	foreach($story as $v){
    		$data[$v['story']][] = $v;
    		if(!in_array($v['kid'],$arr)){
        		$arr[] = $v['kid'];
        	}
    	}

    	$historys = $this->ergodic($data,2);
        $this->assign('history',$historys);

        //获取左侧试卷列表
    	$l_examin = $this->getLnav();
    	$this->assign('l_examin',$l_examin);

    	//分配知识点个数
    	$h_count = count($arr);
    	$count = $t_count + $h_count;

    	$this->assign('t_count',$count);

        $token = base64_encode(time());
        session('token',$token);
        $this->assign('token',$token);
        
    	$this->display('index');
    }

    //提交评价
    public function assess()
    {   
        $assessDb = M('assess');
        if(IS_AJAX){
            $data['u_id'] = $this->getUserId();
            $data['e_id'] = I('post.id');

            //先查询
            $res = $assessDb->where($data)->find();
            if($res){
                $this->ajaxReturn('cz');
            }else{
                $data['star'] = I('post.star');
                $data['assess'] = trim(I('post.assess'),'__');
                $data['time'] = time();

                $res = $assessDb->add($data);
                if($res){
                    $this->ajaxReturn('ok');
                }else{
                    $this->ajaxReturn('no');
                }
            }
        }
    }

    // 参与打榜
    public function Band()
    {
        $scoreDb = M('score');

        //查询是否有打榜信息
        $where['type'] = 2;
        $where['id'] = array('neq',I('post.id'));
        $where['exmainid'] = I('post.eid');

        $id = $scoreDb->where($where)->getField('id');

        $data['type'] = 2;
        if(!$id){
          $data['is_fb'] = 2;  
        }

        $res = $scoreDb->where('id='.I('post.id'))->save($data);

        if($res){
            $this->ajaxReturn('yes');
        }else{
            $this->ajaxReturn('no');
        }
    }

    /**
     * 我的错误
     */
    public function mistake(){
        $page = I("p");
        $shows = new mistake();
        $shows->getPageId(1,$page);
    }

    /**
     * 我的错误
     */
    public function lists(){
        $scoreid = I("i");
        $scoreid = base64_decode($scoreid);
        $shows = new lists();
        $shows->shows($scoreid);
    }

    /**
     * 跳转到mark成绩评估页面
     */
    public function tomark(){
        $scoreid = I("i");
        $scoreid = base64_decode($scoreid);
        $url = 'http://'.$_SERVER['HTTP_HOST']."/".U("answer/mark");
        $ch = curl_init();
        $data = array('answer' =>$scoreid);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);
    }

    /**
     * 试卷重做
     */
    public function again(){
      $exmainid = I("i");
      $exmainid = base64_decode($exmainid);
      $tools = new again();
      $tools->index($exmainid);
    }
}