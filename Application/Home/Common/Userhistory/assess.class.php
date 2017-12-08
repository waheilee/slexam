<?php
namespace Home\Common\Userhistory;
use Think\Controller;
class assess extends Controller{
    private $num = array();//答题个数
    private $error = array();//错误个数
    /**
     * 显示页面
     */
    public function shows($people){
        $this->smallData($people,0);
        $this->display();
    }

    /**
     * 数据处理
     */
    private function smallData($people,$type){
        $this->assign("coursename",$this->CourseNameHandle($this->getCourseArray()));
        $this->assign("coursecount",$this->getCourseCount());
        $this->assign("nowtime",$this->getNowTime());

        $this->getExaminByPeople($people,$type);  //默认
        $this->assign("coursepercount",$this->getNumHandle());
        $this->assign("errornum",$this->getErrorNum());
    }

    /**
     * 图表处理
     */
    public function Chart($people,$type){
        $this->num = array();
        $this->error = array();
        $this->getExaminByPeople($people,$type);  //默认
        $data["chart"] =  $this->getNumHandle();
        $data["font"] = $this->getErrorNum();
        $json = json_encode($data);

        return $json;
    }

    /**
     * 课程信息处理
     * @arr 课程数组
     */
    private function CourseNameHandle($arr){
        $str = '';
        for($i=0;$i<count($arr);$i++){
            $str = $str.'"'.$arr[$i]["name"].'",';
        }
        return $str;
    }

    /**
     * 获得课程数组
     */
    private function getCourseArray(){
       $model = D("Treetools");
       return $model->getTreeArrayByPidAndType(0,1);
    }

    /**
     * 获得课程总数
     */
    private function getCourseCount(){
        $model = D("Treetools");
        return $model->getTreeCount();
    }


     /**
      * 通过用户id获得所答试卷id
      * 获得id后进行数据处理
      */
     private function getExaminByPeople($people,$type){
         $model = D("Scoretools");
         $arr = $model->getExaminByPeople($people,$type);
         for($i=0;$i<count($arr);$i++){
             $this->getPerCourseNum($arr[$i]["kid"],$arr[$i]["error_num"]);
         }
     }

     /**
      * 通过获得的qw_examin中的kid得到参与的各个课程数目
      * 每执行一次程序，对应的课程数目+1
      * $arr 解析后的kid
      */
     private function getPerCourseNum($kid,$errornum){
         $arr = $this->analysisKid($kid);
         if($arr==""||$arr==null){}
         else{
             for ($i = 0;$i<count($arr);$i++) {
                 $this->num[$arr[$i]-1] = $this->num[$arr[$i]-1]+1;
                 $this->num["count"] = $this->num["count"]+1;
                 $this->error[$arr[$i]-1] = $this->error[$arr[$i]-1]+$errornum;
             }
         }

     }

     /**
      * 解析kid数据
      * kid数据为空或者 ,kid,
      * @return 数组或""
      */
     private function analysisKid($kid){
         $arrs = "";
         if($kid==""){}
         else{
             $arr = explode(",",$kid);
             for($i=1;$i<count($arr)-1;$i++){
                 $arrs[$i-1] = $arr[$i];
             }
         }
         return $arrs;
     }

     /**
      * 将答题个数进行处理
      */
     private function getNumHandle(){
         $str = "";
         for($i=0;$i<$this->getCourseCount();$i++){
           if($this->num[$i]==""||$this->num[$i]==null){
               $this->num[$i] = 0;
           }
           if($i==0){
               //$str = $this->num[$i]/$this->num["count"]*100;
               $str = sprintf("%.2f",$this->num[$i]/$this->num["count"]*100);
           }else{
               //$str = $str.",".$this->num[$i]/$this->num["count"]*100;
               $str = $str.",".sprintf("%.2f",$this->num[$i]/$this->num["count"]*100);
           }
        }
        return $str;
     }

     /**
      * 各科目错误个数进行处理
      */
     private function getErrorNum(){
         $str = "";
         $model = D("Treetools");
         for($i=0;$i<$this->getCourseCount();$i++){
             if($this->error[$i]==""||$this->error[$i]==null){
                 //没有数据
             }else{
                 $say = $model->getNameById($i+1)."错误".$this->error[$i]."道";
                if($i==0){
                    $str = $say;
                }else{
                    $str = $str.",".$say;
                }
             }
         }
         return $str;
     }

     /**
      * 获得当前年月日
      */
     private function getNowTime(){
         date_default_timezone_set('PRC');
         $time = time();
         return date("Y/m/d", $time) ;
     }


}
?>