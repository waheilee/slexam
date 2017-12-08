<?php
namespace Common\Model;
use Think\Model;
class ScoretoolsModel extends Model{

    protected $tableName = 'score';

    /**
     * 通过用户id获得所答题的数据
     * @param $type 0为全部,1为周评估，2为月评估，3为年评估
     */
    public function getExaminByPeople($people,$type){
        $field = "qw_score.id,qw_score.exmainId,qw_score.score,qw_score.usetime,qw_score.errorinfo,qw_score.error_num,qw_examin.kid";
        $left = "qw_examin on qw_score.exmainId=qw_examin.id";
        $where["qw_score.people"] = $people;
        date_default_timezone_set('PRC');
        $time = time();
        if($type==0){}elseif($type==1){
            $where["qw_score.time"]=array('GT',$time-3600*24*7);
        }elseif($type==2){
            $where["qw_score.time"]=array('GT',$time-3600*24*30);
        }elseif($type==3){
            $where["qw_score.time"]=array('GT',$time-3600*24*365);
        }
        $result =  $this->field($field)->join($left)->where($where)->order("qw_score.id desc")->select();
        return $result;
    }

    /**
     * 通过page获得答题的id
     */
    public function getExaminIdByPage($people,$page){
        $field = "qw_score.id";
        $left = "qw_examin on qw_score.exmainId=qw_examin.id";
        $where["qw_score.people"] = $people;
        $result =  $this->field($field)->join($left)->where($where)->order("qw_score.id desc")->page($page,1)->select();
        return $result[0]["id"];
    }

    /**
     * 获得所答题的页数
     */
    public function getScoreCount($people){
        $left = "qw_examin on qw_score.exmainId=qw_examin.id";
        $where["qw_score.people"] = $people;
        return $this->join($left)->where($where)->count();
    }

    /**
     * 通过exmainid和people得到最高成绩与最快时间
     * select max(score),min(usetime) from qw_score where people = 2;
     * @return score最高分数 time最短时间
     */
    public function getBestScore($exmainid,$people){
        $field = "max(score) as score,min(usetime) as time";
        $where["exmainId"] = $exmainid;
        $where["people"] = $people;
        $data =  $this->field($field)->where($where)->find();
        return $data;
    }

    /**
     * 通过score id获得想要的数据
     */
    public function getDataById($id,$field){
        $where['id'] = $id;
        $data = $this->field($field)->where($where)->find();
        return $data;
    }

}
?>