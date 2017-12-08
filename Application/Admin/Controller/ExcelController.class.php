<?php


namespace Admin\Controller;

class ExcelController extends ComController{
    //put your code here
    public function index() {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $field = isset($_GET['field']) ? $_GET['field'] : '';
        $keyword = isset($_GET['keyword']) ? htmlentities($_GET['keyword']) : '';
        $order = isset($_GET['orders']) ? $_GET['orders'] : 'DESC';
        $where = '';

        $prefix = C('DB_PREFIX');
        if ($order == 'asc') {
            $order = "{$prefix}file.time asc";
        } elseif (($order == 'desc')) {
            $order = "{$prefix}file.time desc";
        } else {
            $order = "{$prefix}file.id asc";
        }
        if ($keyword <> '') {
            if ($field == 'filename') {
                $where = "{$prefix}file.filename LIKE '%$keyword%'";
            }
            if ($field == 'admin') {
                $where = "{$prefix}file.admin LIKE '%$keyword%'";
            }
            if ($field == 'order') {
                if($keyword=='非故事型模拟题'){
                    $keyword=1;
                }elseif ($keyword=='非故事型真题') {
                    $keyword=2;
                }elseif ($keyword=='故事型模拟题') {
                    $keyword=3;
                }  else {
                    $keyword=4;
                }
                $where = "{$prefix}file.model = {$keyword}";
            }
        }


        $user = M('file');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $count = $user->order($order)
                ->where($where)
                ->count();

        $list = $user->order($order)
                ->where($where)
                ->limit($offset . ',' . $pagesize)
                ->select();

        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display('index');
    }
    
    public function add() {
       
        $this->display('form');
    }
    public function del() {

       $aids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        if (!$aids) {
            $this->error('参数错误！未勾选');
        }

        $map['id'] = array('in', $aids);
        if (M('file')->where($map)->delete()) {
            addlog('删除试卷记录ID：' . $aids);
            $this->success('恭喜，试卷记录删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }
    public function update() {
        require '/PHPExcel/Classes/PHPExcel/IOFactory.php';   //引入phpexcel类
        $model=M();
        header("Content-type:text/html;charset=utf-8");
        $id = isset($_POST['id']) ? intval($_POST['id']) :'';
        $data['remark'] = isset($_POST['remark']) ? $_POST['remark'] : 0;
        $data['model'] = isset($_POST['model']) ? $_POST['model'] : 0;
        $data['admin'] = 1;         //session
        $data['filename'] = $_FILES['file']['name'];
        $data['filepath'] = './Upload/file/' . date('Y-m-d', time());
        $data['time'] = time();

        $upload = new \Think\Upload(); // 实例化上传类

        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->exts = ''; // 设置附件上传类型
        $upload->rootPath = './Upload/'; // 设置附件上传根目录
        $upload->savePath = '/file/'; // 设置附件上传（子）目录
        $upload->saveName = '';
        // 上传文件 ，如果文件名包含汉字，则3.2.3版本中需要修改ThinkPHP\Library\Think\Upload\Driver\local.class.php
        //第82行处改为if (!move_uploaded_file($file['tmp_name'], iconv('utf-8','gb2312',$filename))) 
        //这点很重要
        $info = $upload->upload();
         
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {// 上传成功
            $model = M();
            $file = $model->table('qw_file')->add($data);
            
            if ($file) {
                $models=$data['model'];
                if (!$this->addsql($models)) {
                     echo "<script>alert('文件导入数据库成功。');</script>"; 
                    $this->success('上传成功！');
                } else {
                     echo "<script>alert('文件导入数据库失败，请检查文件是否对应文件类型。');</script>"; 
                    $this->error('上传失败。');
                }
            }
        }
    }

    public function addsql($models) {
        header("Content-type:text/html;charset=utf-8");  //统一输出编码为utf-8 
//PHPExcel  

        $file = $_FILES['file']['name'];

        $type = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $model=M();
        $files = $model->table('qw_file')->where('filename=' . '"' . $file . '"')->select();
        $filepath = substr($files[0]['filepath'], 1);

        $path = $_SERVER['DOCUMENT_ROOT'] . "slcms" . $filepath . '/' . $files[0]['filename']; //记得改文件名
        
        $path=iconv('UTF-8','GB2312',$path);  //注意如果文件名包含中文字符，要进行字符转换，否则file_exists找不到文件。
        //var_dump($path);die;
        if (!file_exists($path)) {
            $this->error('文件不存在!');
        }

//根据不同类型分别操作
        if ($type == 'xlsx' || $type == 'xls') {
            $objPHPExcel = \PHPExcel_IOFactory::load($path);
        } else if ($type == 'csv') {
            $objReader = \PHPExcel_IOFactory::createReader('CSV')
                    ->setDelimiter(',')
                    ->setInputEncoding('UTF-8') //不设置将导致中文列内容返回boolean(false)或乱码
                    ->setEnclosure('"')
                    ->setLineEnding("\r\n")
                    ->setSheetIndex(0);
            $objPHPExcel = $objReader->load($path);
        } else {
            die('Not supported file types!');
        }

        $sheet = $objPHPExcel->getSheet(0);

//获取行数与列数,注意列数需要转换
        $highestRowNum = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnNum = \PHPExcel_Cell::columnIndexFromString($highestColumn);

//取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
        $filed = array();
        for ($i = 0; $i < $highestColumnNum; $i++) {
            $cellName = \PHPExcel_Cell::stringFromColumnIndex($i) . '1';
            $cellVal = $sheet->getCell($cellName)->getValue(); //取得列内容
            $filed [] = $cellVal;
        }

//开始取出数据并存入数组
        $data = array();
        for ($i = 2; $i <= $highestRowNum; $i++) {//ignore row 1
            $row = array();
            for ($j = 0; $j < $highestColumnNum; $j++) {
                $cellName = \PHPExcel_Cell::stringFromColumnIndex($j) . $i;
                
                if ($sheet->getCell($cellName)->hasHyperlink())
                { 
                  $cellVal = $sheet->getCell($cellName)->getHyperlink()->getUrl();
                }else{
                    $cellVal = $sheet->getCell($cellName)->getValue();
                }
                $row[$filed[$j]] = $cellVal;
            }
            $data [] = $row;
        }

//var_dump($data);die;

        $length = count($data);

        for ($i = 0; $i < $length; $i++) {
            
        $know = explode('，',$data[$i]["知识点"]);      //中文状态下的逗号
       
        //var_dump($knowledge);die;
        $tree=M('tree')->where('type=4')->select();
         $kid='';
         $zid='';
         $jid='';
        foreach ($tree as $key => $value) {
             $name=$value['name'];
             $namearray = explode('，',$name);     //tree里面的知识点字符串拆分成数组
             //var_dump($know);die;
              foreach ($namearray as $key => $val) {
                  foreach ($know as $key => $vall) {
                     if(trim($vall)==trim($val)){
                        $kid.=','.$value['cid'] ;   //课程id
                        $zid.=','.$value['zid'] ;   //章id
                        $jid.=','.$value['pid'] ;   //节id
                     }
                 }
              }
        }
        $datas['kid'] = $kid.',';  //英文的
        $datas['zid'] = $zid.',';  //英文的
        $datas['jid'] = $jid.',';  //英文的
        $datas['did'] = '，'.$data[$i]["知识点"].'，';   //中文的
        $datas["story"] =$data[$i]["故事介绍"];
        $datas["contentcount"] =$data[$i]["题数"];
        $datas["content"] =$data[$i]["题目"];
        $datas["questtime"] =strtotime($data[$i]["题目年份"]);
        $datas["questiontime"] =strtotime($data[$i]["题目年份"]);
        $datas["sort"] =$data[$i]["题目序号"];
        $datas["aa"] =$data[$i]["选项A"];
        $datas["bb"] =$data[$i]["选项B"];
        $datas["cc"] =$data[$i]["选项C"];
        $datas["dd"] =$data[$i]["选项D"];
        $datas["multiple"] =$data[$i]["题目类型"];
        $datas["answern"]=str_replace(",","+",$data[$i]["答案"]);
        $datas["difficulty"] =$data[$i]["题目难度"];
        $datas["aexplain"] =$data[$i]["A选项解析"];
        $datas["bexplain"] =$data[$i]["B选项解析"];
        $datas["cexplain"] =$data[$i]["C选项解析"];
        $datas["dexplain"] =$data[$i]["D选项解析"];
        $datas["explain"] =$data[$i]["难点解析"];
        $datas["model"] =$models;
        $datas["addtime"] =time();
        $datas["people"] =$_SESSION['user'];              //session
        //var_dump($models);
            switch ($models){
                case '1':   //非故事型模拟题 
                    //var_dump($datas);die;
                    $result=M('quest')->data($datas)->add();
                 break;
                 case '2':  //非故事型真题
                     $result=M('quest')->data($datas)->add();
                 break;
                 case '3':  //故事型模拟题
                     $datas["model"] =1;
                     $result=M('question')->data($datas)->add();
                break;
                case '4':   //故事型真题
                    $datas["model"] =2;
                    $result=M('question')->data($datas)->add();
                  break;
            }
            //die;
            if ($result === false) {
                echo '执行到' . $i . '出错';
                echo '<br/>';
                $i++;
            } else {
                
                echo $i . '执行完毕';
            }
        }
    }
    public function uploads() {//文件下载 

        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $model = M();
        $files = $model->table('qw_file')->where('id=' . $id)->select();

        /*
          $file------文件名
          $_SERVER['DOCUMENT_ROOT']-----服务器跟目标
          获取文件在文件夹里面的位置
          必须是绝对路径
          Content-Type: application/force-download  强制浏览器下载
         */
        $filepath = substr($files[0]['filepath'], 1);
        $file = $_SERVER['DOCUMENT_ROOT'] . "slcms" .$filepath.'/'.$files[0]['filename'];
        $file=iconv('UTF-8','GB2312',$file);
       // var_dump($file);die;
        if (is_file($file)) {
            header("Content-Type: application/force-download");
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=" . $files[0]['filename']);
            readfile($file);
            exit;
        } else {
            $this->error('文件不存在');
            exit;
        }
    }
}
