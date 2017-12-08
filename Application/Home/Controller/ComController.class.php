<?php
/**
 *
 * 功能说明：前台公用控制器。
 *
 **/

namespace Home\Controller;

use Think\Controller;
use Think\Upload;
class ComController extends Controller
{

    public function _initialize()
    {
        C(setting());
        //查询非故事题目数
        $quesCount = M('quest')->count();
        //查询故事题目数
        $questionCount = M('question')->group('story')->sum('contentcount');
        $count = $quesCount + $questionCount;
        $this->assign('count',$count);
        /*
        $links = M('links')->limit(10)->order('o ASC')->select();
        $this->assign('links',$links);
        */
    }
    /**
     *
     * 上传头像
     *
     **/
    public function SaveImg($files)
    {
        $mimes = array(
            'image/jpeg',
            'image/jpg',
            'image/jpeg',
            'image/png',
            'image/pjpeg',
            'image/gif',
            'image/bmp',
            'image/x-png'
        );
        $exts = array(
            'jpeg',
            'jpg',
            'jpeg',
            'png',
            'pjpeg',
            'gif',
            'bmp',
            'x-png'
        );
        $upload = new Upload(array(
            'mimes' => $mimes,
            'exts' => $exts,
            'rootPath' => './Upload/',
            'savePath' => 'head/'.date('Y')."/".date('m')."/",
            'subName'  =>  array('date', 'd'),
        ));
        $info = $upload->upload($files);
        if(!$info) {// 上传错误提示错误信息
            $error = $upload->getError();
            echo "<script>alert('{$error}')</script>";
        }else{// 上传成功
           foreach ($info as $item) {
                $path = "/Upload/".$item['savepath'].$item['savename'];
                $image = new \Think\Image();
                $image->open('.'.$path);
                $image->thumb(220,220,3)->save($path);
                $filePath[] = $path;
            }

            $ImgStr = implode("|", $filePath);
            return $ImgStr;
        }
    }
}