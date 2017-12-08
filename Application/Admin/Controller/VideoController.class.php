<?php
/**
 *
 * 功能说明：文章控制器。
 *
 **/
namespace Admin\Controller;

use Vendor\Tree;
use Think\Upload;
use Admin\Controller\UploadController;

class VideoController extends ComController
{
    public function add()
    {

      //var_dump(I('post.'));die;
        if(I('post.')){
            $videoDb = M('video');
            $upload = A('Upload');
            $video = $upload->video();
            if($video){
                $data['title'] = I('post.title');
                $data['thumb'] = I('post.pic');
                $data['url'] = $video;
                $data['kid'] = I('post.course');
                $data['keywords'] = I('post.keywords');
                $data['type'] = I('post.type');
                $data['addtime'] = time();
                $data['uid'] = session('uid');
                $data['groom'] = I('post.groom');
                $data['rec'] = I('post.rec');

        $data['teachername'] = I('post.teachername');
        $data['videonum'] = I('post.videonum');
        $data['did'] = I('post.did');

        $knowledge=$data['did'];
        $know = explode('，',$knowledge);      //中文状态下的逗号

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
                     if($vall==$val){
                        $kid.=$value['cid'] ;   //课程id
                        $zid.=$value['zid'] ;   //章id
                        $jid.=$value['pid'] ;   //节id
                     }
                 }
              }
        }
        $data['kid'] = $kid;  //英文的
        $data['zid'] = $zid;  //英文的
        $data['jid'] = $jid;  //英文的


                $res = $videoDb->add($data);
                if($res){
                    $sta['status']=2;
                    $orderlist=M('orderlist')->where('ordernum='.$data['videonum'])->save($sta);
                    if($orderlist){
                    $this->success('添加成功!');
                    }
                }else{
                    $this->error('添加失败!');
                }
            }else{
                $this->error('视频上传有误！');
            }
        }else{
            $videonum = I('ordernum');
            $did = I('did');

            $category = M('category')->field('id,pid,name')->order('o asc')->select();
            $tree = new Tree($category);
            $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
            $category = $tree->get_tree(0, $str, 0);
            $this->assign('category', $category);//导航

            $province = M('tree')->where(array('pid' => 0))->select();
            $this->assign('province', $province);
            $this->assign('videonum', $videonum);
            $this->assign('did', $did);
            $this->display();
        }
    }

 
    public function index($sid = 0, $p = 1)
    {
        $province = M('tree')->where(array('pid' => 0))->select();
        $this->assign('province', $province);

        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $keywords = isset($_GET['keywords']) ? htmlentities($_GET['keywords']) : '';
        $courseid= isset($_GET['courseid']) ? $_GET['courseid'] : '';

        $pagesize = 20; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
       
        if($courseid){
            $where['kid']=$courseid;
        }
        if($keywords){
            $where['content']=$keywords;
        }

        $video = M('video');
        $count = $video->where($where)->count();
        $list = $video->where($where)->limit($offset . ',' . $pagesize)->select();
        //$user->getLastSql();
        //var_dump($list);die;
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();

        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    public function del()
    {

        $uids = isset($_REQUEST['uids']) ? $_REQUEST['uids'] : false;
        if ($uids) {
            if (is_array($uids)) {
                $uids = implode(',', $uids);
                $map['id'] = array('in', $uids);
            } else {
                $map = 'id=' . $uids;
            }
            if (M('video')->where($map)->delete()) {
                addlog('删除视频，id：' . $uids);
                $this->success('恭喜，视频删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }

    }

    public function edit($id)
    {

        $id = intval($id);
        $video = M('video')->where('id=' . $id)->find();
        if ($video) {
            $province = M('tree')->where(array('pid' => 0))->select();
            $this->assign('province', $province);
        } else {
            $this->error('参数错误！');
        }
        $this->assign('video', $video);
        $this->display('form');
    }

    public function update($id = 0)
    {
        $id = intval($id);
        $data['title'] = I('post.title');
        $data['thumb'] = I('post.pic'); 
//        $data['kid'] = I('post.course'); 
        $data['keywords'] = I('post.keywords'); 
        $data['type'] = I('post.type'); 
        $data['groom'] = I('post.groom');
        $data['rec'] = I('post.rec');
        $data['teachername'] = I('post.teachername');
        $data['videonum'] = I('post.videonum');
        $data['did'] = I('post.did');
        $knowledge=$data['did'];
        $know = explode('，',$knowledge);      //中文状态下的逗号
       
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
                     if($vall==$val){
                        $kid.=','.$value['cid'] ;   //课程id
                        $zid.=','.$value['zid'] ;   //章id
                        $jid.=','.$value['pid'] ;   //节id
                     }
                 }
              }
        }
        $data['kid'] = $kid.',';  //英文的
        $data['zid'] = $zid.',';  //英文的
        $data['jid'] = $jid.',';  //英文的
        $data['did'] = '，'.$knowledge.'，';//中文的
        
        if($_FILES['file']['name']){
            $videoDb = M('video');
            $upload = A('Upload');
            $video = $upload->video();
            if($video){
                $data['url'] = $video;
            }else{
                $this->error('视频上传错误！');
            }
        }

        if ($id) {
            M('video')->data($data)->where('id=' . $id)->save();
            addlog('编辑视频，id：' . $id);
            $this->success('恭喜！视频编辑成功！');
        } else {
           $this->success('视频编辑失败！');
        }
    }


    //上传图片
    public function upload()
    {
        //实例化上传类
        $upload = A("Upload");
        $arr = $upload->upload(395,400,'video');
        $this->ajaxReturn($arr);
       
    }
    //获得视频文件的总长度时间和创建时间 
     public function getTime($file){ 
        $vtime = exec("ffmpeg -i ".$file." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");//总长度 
        $ctime = date("Y-m-d H:i:s",filectime($file));//创建时间 
        //$duration = explode(":",$time); 
        // $duration_in_seconds = $duration[0]*3600 + $duration[1]*60+ round($duration[2]);//转化为秒 
        return array('vtime'=>$vtime, 
        'ctime'=>$ctime 
        ); 
        } 
    //获得视频文件的缩略图 
     public  function getVideoCover($file,$time) { 
        if(empty($time))$time = '1';//默认截取第一秒第一帧 
        $strlen = strlen($file); 
        $videoCover = substr($file,0,$strlen-4); 
        $videoCoverName = $videoCover.'.jpg';//缩略图命名 
        exec("ffmpeg -i ".$file." -y -f mjpeg -ss ".$time." -t 0.001 -s 320x240 ".$videoCoverName."",$out,$status); 
        if($status == 0)return $videoCoverName; 
        elseif ($status == 1)return FALSE; 
        } 
        
        public function time(){
           $id = 2;
           $video = M('video')->where('id=' . $id)->find();
           $filepath=$video["url"];
           $path = $_SERVER['DOCUMENT_ROOT'] . "slcms" .$filepath; //记得改文件名
       
           $path=iconv('UTF-8','GB2312',$path); 
           $path=is_file($path);
           //var_dump($a);die;
            //调用方法 
            $duration = $this->getTime($path); 
            var_dump($duration);die;
            echo $duration['vtime'].'<br/>';//总长度 
            echo $duration['ctime'].'<br/>';//创建时间 
            $videoCoverName = $this->getVideoCover('$video["url"]', 6); 
            echo $videoCoverName;//获得缩略图名称 
           
        }
            
}