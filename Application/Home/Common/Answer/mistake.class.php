<?php
namespace Home\Common\Answer;
use Think\Controller;
use Think\Page;

class mistake extends Controller{

    private function shows($id){
        if($id==""){}else {
            $questDb = M('quest');
            $storyDb = M('question');
            $scoreDb = M('score');

            $where['id'] = $id;
            $score = $scoreDb->where($where)->find();

            //查询试卷名
            $examinDb = M('examin');
            $nickname = $examinDb->where('id=' . $score['exmainid'])->getField("nickname");
            $this->assign('nickname', $nickname);

            //获取试卷类型
            $type = $examinDb->where('id=' . $score['exmainid'])->getField("type");
            $this->assign('main', getName($type));

            $serror = explode('/', $score['s_info']);

            //遍历错题答案，获取每题对应的选项
            if ($score['errorinfo']) {
                $derror = $this->getError($score['errorinfo']);
            }

            if ($score['s_info']) {
                $serror = $this->getError($score['s_info']);
            }

            if($score['error']==""){

            }else {

                $where['id'] = array('in', $score['error']);

                $quest = $questDb->field('id,content,aa,bb,cc,dd,answern,aexplain,bexplain,cexplain,dexplain,multiple')->where($where)->select();

                $data = array();

                foreach ($quest as $k => $v) {
                    $data[$k]['name'] = $v['content'];
                    if ($v['multiple'] == 2) {
                        $answer = str_replace('+', '　', $v['answern']);
                        $data[$k]['sign'] = strtoupper($answer);
                    } else {
                        $data[$k]['sign'] = strtoupper($v['answern']);
                    }

                    $data[$k]['option'][0]['answer'] = $v['aa'];
                    $data[$k]['option'][0]['sign'] = $v['aexplain'];
                    $data[$k]['option'][0]['active'] = checkIsset('a', $derror[$v['id']]);

                    $data[$k]['option'][1]['answer'] = $v['bb'];
                    $data[$k]['option'][1]['sign'] = $v['bexplain'];
                    $data[$k]['option'][1]['active'] = checkIsset('b', $derror[$v['id']]);

                    $data[$k]['option'][2]['answer'] = $v['cc'];
                    $data[$k]['option'][2]['sign'] = $v['cexplain'];
                    $data[$k]['option'][2]['active'] = checkIsset('c', $derror[$v['id']]);

                    $data[$k]['option'][3]['answer'] = $v['dd'];
                    $data[$k]['option'][3]['sign'] = $v['dexplain'];
                    $data[$k]['option'][3]['active'] = checkIsset('d', $derror[$v['id']]);
                }

                $this->assign('data', json_encode($data));
            }
            //查询故事错题
            $result = array();
            if ($score['story_error']) {

                $where['id'] = array('in', $score['story_error']);
                $story = $storyDb->field('id,story,content,aa,bb,cc,dd,answern,aexplain,bexplain,cexplain,dexplain,multiple')->where($where)->select();

                foreach ($story as $v) {
                    $res[$v['story']][] = $v;
                }

                $i = 0;
                foreach ($res as $k => $v) {
                    if (count($v) != count($v, 1)) {
                        foreach ($v as $z => $c) {
                            $result[$i]['name'] = $k;
                            $result[$i]['option'][$z]['name'] = $c['content'];
                            if ($v['multiple'] == 2) {
                                $answer = str_replace('+', '　', $c['answern']);
                                $result[$i]['option'][$z]['sign'] = strtoupper($answer);
                            } else {
                                $result[$i]['option'][$z]['sign'] = strtoupper($c['answern']);
                            }

                            $result[$i]['option'][$z]['option'][0]['answer'] = $c['aa'];
                            $result[$i]['option'][$z]['option'][0]['sign'] = $c['aexplain'] ? $c['aexplain'] : '无解析';
                            $result[$i]['option'][$z]['option'][0]['active'] = checkIsset('a', $serror[$c['id']]);

                            $result[$i]['option'][$z]['option'][1]['answer'] = $c['bb'];
                            $result[$i]['option'][$z]['option'][1]['sign'] = $c['bexplain'] ? $c['bexplain'] : '无解析';
                            $result[$i]['option'][$z]['option'][1]['active'] = checkIsset('b', $serror[$c['id']]);

                            $result[$i]['option'][$z]['option'][2]['answer'] = $c['cc'];
                            $result[$i]['option'][$z]['option'][2]['sign'] = $c['cexplain'] ? $c['cexplain'] : '无解析';
                            $result[$i]['option'][$z]['option'][2]['active'] = checkIsset('c', $serror[$c['id']]);

                            $result[$i]['option'][$z]['option'][3]['answer'] = $c['dd'];
                            $result[$i]['option'][$z]['option'][3]['sign'] = $c['dexplain'] ? $c['dexplain'] : '无解析';
                            $result[$i]['option'][$z]['option'][3]['active'] = checkIsset('d', $serror[$c['id']]);
                        }
                    }
                    $i++;
                }
            }
            $this->assign('story', json_encode($result));
        }
        //获取左侧试卷列表
        $l_examin = $this->getLnav();
        $this->assign('l_examin',$l_examin);

        $this->display();
    }

    //拆分错误答案选项
    private function getError($data)
    {
        $error = explode('/',$data);

        foreach($error as $v){
            $res = explode('_',$v);
            $info[$res[0]] = $res[1];
        }

        return $info;
    }

    //获取左侧试卷列表
    private function getLnav()
    {
        $examinDb = M('examin');
        $examin = $examinDb->field('nickname,id')->order('rand()')->limit(12)->select();

        return $examin;
    }

    /**
     * @param $id通过page获得答卷id
     */
    public function getPageId($people,$page){
      $model = D("Scoretools");
      $count = $model->getScoreCount($people);
      if($page>=$count){
          $page = $count;
      }
      if($page<1){
          $page = 1;
      }
      $this->page($count);
      $id = $model->getExaminIdByPage($people,$page);
      $this->getNumByScoreId($id);
      $this->shows($id);
    }

    /**
     * 分页
     */
    private function page($count){
        $page = new Page($count,1);
        $page->lastSuffix = false;
        $page->rollPage = 10;
        //$page->setConfig('prev','上一页');
        //$page->setConfig('next','下一页');
        $page->setConfig('first','首页');
        $page->setConfig('last','尾页');
        //$page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $show = $page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
    }

    /**
     * 通过score id获得题目数量
     */
    private function getNumByScoreId($scoreid){
        $score = D("Scoretools");
        $data = $score->getDataById($scoreid,'exmainid');
        $exmainid = $data['exmainid'];
        $exmain = D("Examintools");
        $num = $exmain->getTopicsNum($exmainid);
        $this->assign('count',$num);
    }
}
?>