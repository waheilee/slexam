<?php
namespace Common\Model;
use Think\Model;
class TreetoolsModel extends Model{

    protected $tableName = 'tree';

   /**
    * 通过type与pid获得课程数组
    */
   public function getTreeArrayByPidAndType($pid,$type){
       $where["pid"] = $pid;
       $where["type"] = $type;
       return $this->field("id,name")->where($where)->select();
   }

    /**
     * 通过id得到课程名称
     */
    public function getNameById($id){
        $where["id"] = $id;
        $data = $this->field("name")->where($where)->find();
        return $data["name"];
    }

   /**
    * 获得课程总数
    * type为1 pid为0
    */
   public function getTreeCount(){
       $where["pid"] = 0;
       $where["type"] = 1;
       return $this->where($where)->count();
   }

}
?>