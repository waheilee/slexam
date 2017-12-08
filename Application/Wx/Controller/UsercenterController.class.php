<?php


namespace Wx\Controller;

class UsercenterController extends ComController{
	protected $userid = 1;

    public function index(){    
    	$scoreDb = M('score');
    	$where['qw_score.people'] = $this->userid;

    	$counts = $scoreDb->where($where)->group('exmainId')->select();
    	$this->assign('sum',count($counts));
    	
        $this->display();
    }

    //试卷信息页
    public function paper($p = 1)
    {	
    	//获取该用户答卷数量
    	$scoreDb = M('score');
    	$where['qw_score.people'] = $this->userid;

    	$counts = $scoreDb->where($where)->group('exmainId')->select();
    	$this->assign('sum',count($counts));

    	if(I('get.type')){
    		$where['qw_examin.type'] = I('get.type');
    		$this->assign('type',I('get.type'));
    	}else{
    		$where['qw_examin.type'] = 1;
    	}

    	if(I('get.data') == 'k'){
    		unset($where['qw_examin.type']);
    		$where['qw_examin.knum'] = 1;
    		$this->assign('data',I('get.data'));
    	}

    	$band = $scoreDb->field('qw_examin.nickname,qw_examin.type,qw_examin.id,qw_examin.difficulty,qw_examin.usenum')->join('qw_examin ON qw_examin.id=qw_score.exmainid','LEFT')->where($where)->page($p.',5')->group('qw_score.exmainid')->select();

    	$bands = A('Bands');
    	foreach($band as &$v){
            $res = $bands->getAssess($v['id']);
            $v['star'] = $res['star'];
            $v['assess'] = $res['assess'];
            $v['score'] = $res['score'];
            $v['usetime'] = $res['usetime'];
        }

    	$this->assign('band',$band);
    	$num = $scoreDb->field('qw_examin.id')->join('qw_examin ON qw_examin.id=qw_score.exmainid','LEFT')->where($where)->group('qw_score.exmainid')->select();
    	$this->assign('num',count($num));

    	//获取各类型试卷数
    	unset($where['qw_examin.knum']);
    	$where['qw_examin.type'] = 1;
    	$count['exam'] = $this->getCount($where);

    	$where['qw_examin.type'] = 2;
    	$count['analog'] = $this->getCount($where);

    	$where['qw_examin.type'] = 3;
    	$count['random'] = $this->getCount($where);

    	//单科试卷数
    	unset($where['qw_examin.type']);
    	$where['qw_examin.knum'] = 1;
    	$count['km'] = $this->getCount($where);

    	$this->assign('count',$count);

    	$this->display();
    }

    // 获取各试卷类型总数
    protected function getCount($where)
    {
    	$scoreDb = M('score');
    	$count = $scoreDb->field('qw_score.id')->join('qw_examin ON qw_examin.id=qw_score.exmainid','LEFT')->where($where)->group('qw_score.exmainid')->select();

    	return count($count);
    }

    //ajax获取数据
    public function getRes()
    {
    	$scoreDb = M('score');
    	$where['qw_score.people'] = $this->userid;
    	if(I('post.type')){
    		$p = I('post.page');
    	}

    	if(I('post.type')){
    		$where['qw_examin.type'] = I('post.type');
    	}else{
    		$where['qw_examin.type'] = 1;
    	}

    	if(I('post.order')){
    		$order['score'] = I('post.order');
    	}

    	$band = $scoreDb->field('qw_examin.nickname,qw_examin.type,max(qw_score.score) as score,qw_examin.id,qw_examin.difficulty,qw_examin.usenum')->join('qw_examin ON qw_examin.id=qw_score.exmainid','LEFT')->where($where)->order($order)->page($p.',5')->group('qw_score.exmainid')->select();
    	// echo I('post.order');

    	$bands = A('Bands');
    	foreach($band as &$v){
            $res = $bands->getAssess($v['id']);
            if($v['type'] == 1){
                $str .= '<li data="z">'; 
            }elseif($v['type'] == 2){
                $str .= '<li data="m">'; 
            }else{
                $str .= '<li data="d">'; 
            }
            $str .= '<div class="oe-topmenu star_'.$p.'"><div class="wrap"><div class="left">综合评星：<span class="startnum" data="'.$res['star'].'"></span></div><div class="right"><a href="javascript:void(0)" onclick="shopNav(\'dzkt\')">订制课堂</a><span>丨</span><a href="javascript:void(0)">分享试卷</a><span>丨</span><a href="javascript:void(0)" class="blue">查看打榜</a></div></div></div><div class="oe-middlename wrap"><div class="left"><img src="'.__ROOT__.'/Public/Wx/images/makelist/zdypaper.png" alt=""/><p class="oem-name">'.$v['nickname'].'</p></div><div class="left oe-num"><span>'.$res['score'].'分</span></div><div class="right"><a href="'.U('Answers/examin',array('code'=>base64_encode($v['id']))).'"><img src="'.__ROOT__.'/Public/Wx/images/yearoldexam/penanswer.png" alt=""/></a></div></div><div class="oe-bottom"><div class="wrap"><span>难易程度：'.checkType($v['difficulty']).'</span><span>答题人数：'.$v['usenum'].'人</span><span>最快用时：'.$res['usetime'].'</span></div></div></li>';
        }

        $this->ajaxReturn($str);
    }

    // 试卷错题页
    public function wrong($p = 1)
    {	
    	$scoreDb = M('score');
    	$treeDb = M('tree');

    	//获取科目
    	$tree = $treeDb->field('id,name')->where('pid=0')->select();
    	foreach($tree as $v){
    		$data[$v['id']] = $v['name'];
    	}
    	
    	if(I('post.name')){
    		$where['qw_examin.nickname'] = array('like','%'.I('post.name').'%');
    		$this->assign('name',I('post.name'));
    	}

    	$order['qw_examin.createtime'] = 'desc';
    	if(IS_AJAX){
    		if(I('post.order')){
    			unset($order['qw_examin.createtime']);
	    		$order['((qw_examin.num - qw_score.error_num)/qw_examin.num)'] = I('post.order');
	    	}

    		$p = I('post.page');
    	}

    	//查询错误试卷信息
    	$where['people'] = $this->userid;
    	$where['error_num'] = array('neq',0);

    	$count = $this->getCount($where);
    	$this->assign('count',$count);

    	$score = $scoreDb->field('qw_examin.nickname,qw_examin.num,qw_examin.knum,qw_score.id,qw_score.error_num')->join('qw_examin ON qw_examin.id=qw_score.exmainid','LEFT')->where($where)->page($p.',5')->group('qw_score.exmainid')->order($order)->select();

    	if(IS_AJAX){
    		$str = '';
    		foreach($score as $v){
    			if($v['knum'] == 1){
    				$km = $data[trim($v['kid'],',')];
    			}else{
    				$km = '综合科目';
    			}
    			$str .= '<li><div class="wrap"><div class="left"><p class="ebl-name"><img src="'.__ROOT__.'/Public/Wx/images/usecenter/djct/error.png" alt=""/><span>'.$v['nickname'].'</span></p><p class="ebl-subject">所属科目：'.$km.'</p><p class="ebl-content"><span>正确率：'.getAccuracy($v['error_num'],$v['num']).'%</span><span>错误：'.$v['error_num'].'道题</span><a href="javascript:void(0)" onClick="goMark('.$v['id'].')">查看试卷评估</a></p></div><div class="right"><a href="javascript:void(0)" onClick="goError('.$v['id'].')"><figure class="pie" data="'.getAccuracy($v['error_num'],$v['num'],'error').'" data-behavior="pie-chart"></figure></a></div><div class="clearfix"></div></div></li>' ;
    		}
    		$this->ajaxReturn($str);
    	}else{
    		$this->assign("data",$data);
	    	$this->assign('score',$score);
	    	$this->display();
    	}
    }

    //个人打榜
    public function band($p = 1)
    {	
    	$scoreDb = M('score');
    	//获取发起和参与的榜单总数
    	$where['qw_score.people'] = $this->userid;
    	$where['qw_score.type'] = 2;

    	$where['qw_score.is_fb'] = 2;
    	$res = $scoreDb->field('id,exmainid')->where($where)->group('exmainid')->select();
    	$f_num = count($res);
    	
    	foreach($res as $v){
    		$id[] = $v['id'];
    	}

    	$where['qw_score.exmainid'] = array('not in',$id);
    	$where['qw_score.is_fb'] = 1;
    	$res = $scoreDb->field('id')->where($where)->group('exmainid')->select();
    	$c_num = count($res);
    	

    	if(I('get.type') == 'cy'){
    		$where['qw_score.is_fb'] = 1;
    		$sum = $c_num;
    	}else{
    		unset($where['qw_score.exmainid']);
    		$where['qw_score.is_fb'] = 2;
    		$sum = $f_num;
    	}

    	$order['qw_examin.createtime'] = 'desc';
    	if(IS_AJAX){
    		if(I('post.order')){
    			unset($order['qw_examin.createtime']);
	    		$order['((qw_examin.num - qw_score.error_num)/qw_examin.num)'] = I('post.order');
	    	}

    		$p = I('post.page');
    	}

    	// 获取参与榜单信息
        $order['qw_score.time'] = 'asc';
    	$band = $scoreDb->field('qw_examin.nickname,qw_examin.type,qw_examin.id,qw_examin.difficulty,qw_examin.usenum')->join('qw_examin ON qw_examin.id=qw_score.exmainid','LEFT')->where($where)->order($order)->page($p.',5')->group('qw_score.exmainid')->select();

    	if(IS_AJAX){
        	foreach($band as $v){
        		$res = $this->getAssess($v['id']);

        		if($v['type'] == 1){
        			$str .= '<li data="z">';
        		}elseif($v['type'] == 2){
        			$str .= '<li data="m">';
        		}else{
        			$str .= '<li data="d">';
        		}
        		$str .= '<div class="oe-topmenu"><div class="wrap"><div class="left">综合评星：<span class="startnum" data="'.$res['star'].'"></span></div><div class="right"><a href="javascript:void(0)" onclick="shopNav(\'dzkt\')">订制课堂</a><span>丨</span><a href="javascript:void(0)">分享试卷</a><span>丨</span><a href="'.U('Bands/info',array('code'=>base64_encode($v['id']))).'" class="blue">查看打榜</a></div></div></div><div class="oe-middlename wrap"><div class="left"><img src="'.__ROOT__.'/Public/Wx/images/makelist/zdypaper.png" alt=""/><p class="oem-name">'.$v['nickname'].'</p></div><div class="left oe-num"><span>'.$res['score'].'分</span></div><div class="right oe-rank">排名<span>'.$res['count'].'</span></div></div><div class="oe-bottom"><div class="wrap"><span>难易程度：'.checkType($v['difficulty']).'</span><span>答题人数：'.$v['usenum'].'人</span><span>最快用时：'.$res['usetime'].'</span></div></div></li>';
        	}

        	$this->ajaxReturn($str);
        }else{

	        foreach($band as &$v){
	            $res = $this->getAssess($v['id']);
	            $v['star'] = $res['star'];
	            $v['count'] = $res['count'];
	            $v['score'] = $res['score'];
	            $v['usetime'] = $res['usetime'];
	        }


        	$this->assign('f_num',$f_num);
        	$this->assign('c_num',$c_num);
        	$this->assign('sum',$sum);
        	$this->assign('type',I('get.type'));
        	$this->assign('band',$band);
    		$this->display();
        }
        
    }

    protected function getAssess($id)
    {
        $order['score'] = 'desc';
        $order['sntime'] = 'asc';

        //查询试卷最高得分和用时
        $scoreDb = M('score');
        $score = $scoreDb->field('usetime,score,sntime')->where('exmainid='.$id)->order($order)->find();
        
        $where['exmainid'] = $id;
        $where['score'] = array('gt',$score['score']);
        $where['sntime'] = array('lt',$score['sntime']);
        $count = $scoreDb->where($where)->count();

        //查询试卷评价和得星
        $assessDb = M('assess');

        $star = $assessDb->where('e_id='.$id)->avg('star');
        
        $result['star'] = ceil($star);
        $result['score'] = $score['score'];
        $result['usetime'] = $score['usetime'];
        $result['count'] = $count + 1;

        return $result;
    }
}
