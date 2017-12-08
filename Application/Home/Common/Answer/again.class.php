<?php
namespace Home\Common\Answer;
use Think\Controller;

class again extends Controller{

     private $point = array();
     private $pointstory = 0;
     private $count = 0;

     public function index($exmainid){

         //获取左侧试卷列表
         $l_examin = $this->getLnav();
         $this->assign('l_examin',$l_examin);

         $token = base64_encode(time());
         session('token',$token);
         $this->assign('token',$token);
         $this->getExamin($exmainid);//试题
         $this->smallData($exmainid);

         $this->display();
     }

    /***
     * 小数据
     * @param $exmainid
     */
    private function smallData($exmainid){
        //获取试卷类型
        $examinDb = M('examin');
        $type = $examinDb->where("id=$exmainid")->getField("type");
        $this->assign('main',getName($type));
        $this->assign('t_count',$this->getPoint());
        $this->assign('examinid',$exmainid);

        $examintool = D("Examintools");
        $exmainmodel = $examintool->getExamin("nickname,difficulty",$exmainid);
        $this->assign('nickname',$exmainmodel['nickname']);
        $this->assign('diffi',$exmainmodel['difficulty']);

        $this->assign('difys',checkType($exmainmodel['difficulty']));
    }

     private function getExamin($exmainid){
         $radio = $this->getData($exmainid,"paper1");
         $check = $this->getData($exmainid,"paper2");
         $term = $this->getData($exmainid,"paper3");
         $history = $this->getData($exmainid,"story");
         $this->assign("radio",$radio);
         $this->assign("check",$check);
         $this->assign("term",$term);
         $this->assign("history",$history);
         $this->assign('count',$this->count);
     }

    /**
     * 数据库操作
     */
    private function getModel($exmainid){
        $model = D("Examintools");
        $field = "paper1,paper2,paper3,story";
        return $model->getExamin($field,$exmainid);
    }

    /**
     * 通过试卷id获得试卷的题目
     * $type "paper1"为单选题 "paper2"为多选题 "paper3"为不定项选择题,"story"为故事题
     * @param $exmainid
     */
    private function getData($exmainid,$type){
        $model = $this->getModel($exmainid);
        $idarray = $model[$type];
        $radio = "[]";
        if($idarray==""||$idarray==null){

        }
        else{
            if($type=="story"){
                $questDb = M('question');
            }else{
                $questDb = M('quest');
            }
            $where['id'] = array('IN',$idarray);
            $order = substr($idarray,0,strlen($idarray)-1);
            if($type=="story"){
                $radio = $this->getHistory($where);
                $this->count = $this->count+count($radio);
                $radio = $this->ergodic($radio["data"],2);
            }else{
                $radio = $questDb->field('id,did,content,aa,bb,cc,dd,answer')->where($where)->order("field(id,$order)")->select();
                $this->point = $this->updatePoint($this->point,$radio);
                $this->count = $this->count+count($radio);
                $radio = $this->ergodic($radio);
            }

        }
        return $radio;
    }

    private function updatePoint($res,$did=''){
        if($did==""||$did==null){

        }else{
            for($i=0;$i<count($did);$i++){
                $res = $this->updatePointTool($res,$did[$i]['did']);
            }
        }
        return $res;
    }

    //获取知识点数组方法
    private function updatePointTool($res,$did = '')
    {
        if($did==""||$did==null){

        }
        else{
            $didarray = explode("，",$did);
            foreach($didarray as $k=>$v){
                if( !$v )
                    unset( $didarray[$k] );
            }
            $res = array_merge($res,$didarray);
            $res = array_unique($res);
        }
        return $res;
    }

    //遍历数组，组合成json类型
    private function ergodic($res,$type=1)
    {
        $data = array();
        if($res==""||$res==null){}else{
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
        }
        $data = json_encode($data);
        return $data;
    }

    //获取随机故事题数
    private function getHistory($where)
    {
        $questtionDb = M('question');
        $history = $questtionDb->where($where)->group('story')->select();

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
        $this->pointstory = $result['count'];
        return $result;
    }

    //获取左侧试卷列表
    private function getLnav()
    {
        $examinDb = M('examin');
        $examin = $examinDb->field('nickname,id')->order('rand()')->limit(12)->select();

        return $examin;
    }

    /**
     * 获得知识点
     */
    private function getPoint(){
        $num = $this->pointstory+count($this->point);
        return $num;
    }

}
?>