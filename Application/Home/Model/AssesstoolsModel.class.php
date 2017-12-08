<?php
namespace Common\Model;
use Think\Model;
class AssesstoolsModel extends Model{

    protected $tableName = 'assess';

    /**
     * select sum(star),e_id,count(e_id) from qw_assess group by e_id;
     * select sum(star),count(*) as num from qw_assess where e_id=3;
     * 通过试卷id得到星数
     * @return star 总星数 num 评分人数
     */
    public function getAverageStar($e_id){
        $field = "sum(star) as star,count(id) as num";
        $where["e_id"] = $e_id;
        return $this->field($field)->where($where)->find();
    }

}
?>