<?php
namespace Common\Model;
use Think\Model;
class ExamintoolsModel extends Model{

    protected $tableName = 'examin';

    /**
     * 通过试卷id获得试卷信息
     */
    public function getExamin($field,$id){
        $where["id"] = $id;
        return $this->field($field)->where($where)->find();
    }

    /**
     * 通过试卷id获得试卷的题目个数
     */
    public function getTopicsNum($id){
        $field = "paper1,paper2,paper3,story";
        $data = $this->getExamin("",$id);
        $paper1 = $data["paper1"];
        $paper2 = $data["paper2"];
        $paper3 = $data["paper3"];
        $story = $data["story"];
        $num = $this->getTopicsNumtools($paper1)+$this->getTopicsNumtools($paper2)+$this->getTopicsNumtools($paper3)+$this->getTopicsNumtools($story);
        return $num;
    }

    /**
     * @param $param  ''或者1,2,
     * @return int  个数
     */
    private function getTopicsNumtools($param){
        $num = 0;
        if($param==""||$param==null){}
        else{
            $arr = explode(',',$param);
            foreach($arr as $k=>$v){
                if( !$v )
                    unset( $arr[$k] );
            }
            $num = count($arr);
        }
        return $num;
    }

}
?>