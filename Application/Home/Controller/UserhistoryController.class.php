<?php


namespace Home\Controller;
use Home\Common\Userhistory\assess;
use Home\Common\Userhistory\exam;

class UserhistoryController extends ComController {
    //历史答题
    public function historicalAnswer()
    {   
        //获取试卷
        $scoreDb = M('score');
        $examinDb = M('examin');
        $people = 1;
        $where['people']=  $people;     //session获取用户id  下面也需要修改
        $order = array('qw_score.score'=>'desc','qw_score.sntime'=>'asc');
  
        $count = $scoreDb->field('exmainid')->where($where)->group('exmainid')->select();
        //var_dump($count);die;
        $Page = new \Think\Page(count($count),10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);

        $where = "qw_score.people=$people and qw_score.score in (select max(qw_score.score) from qw_score group by qw_score.exmainid)";
        //$band = $scoreDb->field('qw_examin.*,qw_score.id as scoreid,qw_score.score,qw_score.usetime')->join('qw_examin ON qw_examin.id=qw_score.exmainid')->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->group('qw_score.exmainid')->select();
        $band = $scoreDb->field('qw_examin.*,qw_score.id as scoreid,qw_score.score,min(qw_score.usetime) as usetime')->join('qw_examin ON qw_examin.id=qw_score.exmainid')->where("$where")->order($order)->limit($Page->firstRow.','.$Page->listRows)->group('qw_score.exmainid')->select();

        foreach($band as &$v){
            $res = $this->getAssess($v['id']);
            $v['star'] = $res['star'];
            $v['assess'] = $res['assess'];
        }
        //var_dump($band);die;
        $this->assign('band',$band);
        $this->display('historical_answer');
    }

    public function getAssess($id)
    {
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

        return $result;
    }

    //试卷内容
    public function examContent()
    {
        //$this->display('exam_content');
        $id = base64_decode(I("i"));
        $scoreid = I("s");
        $scoreid = base64_decode($scoreid);
        $this->assign("scoreid",$scoreid);
        $shows = new exam();
        $shows->shows($id,1);
    }

    //成绩分析评估
    public function scoreReporting()
    {
        $this->display('score_reporting');
    }

    //历史打榜
    public function historyList()
    {   
        //获取试卷
        $scoreDb = M('score');
        $examinDb = M('examin');
        $type="mine";

        $people = 1;
        $where['qw_score.people']=  $people;     //session获取用户id
        $where['qw_score.type']=  2;   
        $order = array('qw_score.score'=>'desc','qw_score.sntime'=>'asc');

        if(I("type")==""||I("type")==null){
            $type = "mine";
        }else{
            $type = I("type");
        }

        if($type=="all"){

        }else{
          $ass = M('assess')->field('e_id')->where("u_id=$people")->select();
          $exidlist = "";
          for($i=0;$i<count($ass);$i++){
              if($i==0){$exidlist = $ass[$i]['e_id'];}
              else{$exidlist = $exidlist.','.$ass[$i]['e_id'];}
          }
          $where['qw_score.exmainId'] = array('IN',$exidlist);
        }
  
        $count = $scoreDb->field('exmainid')->where($where)->group('exmainid')->select();
        $Page = new \Think\Page(count($count),10);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $Page->setConfig('first','首页');
        $Page->setConfig('last','尾页');
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);

        $band = $scoreDb->field('qw_examin.*,max(qw_score.score) as score,min(qw_score.usetime) as usetime,qw_score.id as scoreid')->join('qw_examin ON qw_examin.id=qw_score.exmainid')->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->group('qw_score.exmainid')->select();

        /**
         * 获得试卷的排名
         */
        $bestscore = 1;
        for($i=0;$i<count($band);$i++){
            $rank = $scoreDb->where("score>".$band[$i]['score']." and type=2 and exmainId=".$band[$i]['id']."")->getField('count(1) as rank');
            $band[$i]['rank'] = $rank+1;
            if($i==0){
                $bestscore = $rank + 1;
            }
            if($rank+1<$bestscore){
                $bestscore = $rank + 1;
            }

            $assessmodel = D("Assesstools");
            $assessdata = $assessmodel->getAverageStar($band[$i]['id']);
            $band[$i]['star'] = ceil($assessdata["star"]/$assessdata["num"]);
        }

        //历史最好成绩
        $this->assign('bestscore',$bestscore);

        /**
         * 打榜试卷
         */
        $myorder = $scoreDb->field('count(distinct exmainId) as num')->where("people=$people and type=2")->find();
        $this->assign('myrank',$myorder['num']);
        $allorder = M('assess')->field('count(id) as num')->where("u_id=$people")->find();
        $this->assign('allrank',$allorder['num']);

        $this->assign('band',$band);
        $this->assign('type',$type);
        $this->display('history_list');
    }

    //历史打榜内容
    public function historyListContent()
    {
        $this->display('history_list_content');
    }

    //总和评估
    public function assess()
    {
        $tools = new assess();
        $tools->shows(1);
    }

    /**
     * 总和评估周评估，月评估，年评估
     * $type 1为周评估，2为月评估，3为年评估
     */
    public function assessment(){
        $type = I("type");
        $tools = new assess();
        echo $tools->Chart(1,$type);
    }
}
