<?php
namespace Home\Common\Userhistory;
use Think\Controller;
class exam extends Controller{

    public function shows($id,$people){
        $this->getExamData($id);
        $this->getPeopleData($id,$people);
        $this->display("exam_content");
    }

    /**
     * 获得个人数据
     */
    private function getPeopleData($e_id,$people){
       $this->getScore($e_id,$people);//最高分数与最短时间
    }

    /**
     * 获得试卷数据
     */
    private function getExamData($id){
       $model = D("Examintools");
       $data = $model->getExamin("nickname",$id);
       $nickname = $data["nickname"]; //试卷名称
       //$this->assign("examid",$id);
       $this->assign("nickname",$nickname);
       $this->assign("star",$this->getStar($id));
    }

    /**
     * 试卷的综合星数
     */
    private function getStar($e_id){
       $model = D("Assesstools");
       $data = $model->getAverageStar($e_id);
       return ceil($data["star"]/$data["num"]);
    }

    /**
     * 获得个人得分
     */
    private function getScore($e_id,$people){
        $model = D("Scoretools");
        $data = $model->getBestScore($e_id,$people);
        $this->assign("score",$data["score"]);
        $this->assign("time",$data["time"]);
    }
}
?>