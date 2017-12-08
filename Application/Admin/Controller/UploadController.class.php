<?php
/**
 *
 * 功能说明：文件上传控制器。
 *
 **/

namespace Admin\Controller;

use Think\Upload;

class UploadController extends ComController
{
    public function index($type = null)
    {

    }

    public function uploadpic()
    {
        $Img = I('Img');
        $Path = null;
        if ($_FILES['img']) {
            $Img = $this->saveimg($_FILES);
        }
        $BackCall = I('BackCall');
        $Width = I('Width');
        $Height = I('Height');
        if (!$BackCall) {
            $Width = $_POST['BackCall'];
        }
        if (!$Width) {
            $Width = $_POST['Width'];
        }
        if (!$Height) {
            $Width = $_POST['Height'];
        }
        $this->assign('Width', $Width);
        $this->assign('BackCall', $BackCall);
        $this->assign('Img', $Img);
        $this->assign('Height', $Height);
        $this->display('Uploadpic');
    }

    private function saveimg($files)
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
            'rootPath' => './Public/',
            'savePath' => 'attached/'.date('Y')."/".date('m')."/",
            'subName'  =>  array('date', 'd'),
        ));
        $info = $upload->upload($files);
        if(!$info) {// 上传错误提示错误信息
            $error = $upload->getError();
            echo "<script>alert('{$error}')</script>";
        }else{// 上传成功
            foreach ($info as $item) {
                $filePath[] = __ROOT__."/Public/".$item['savepath'].$item['savename'];
            }
            $ImgStr = implode("|", $filePath);
            return $ImgStr;
        }
    }

    public function batchpic()
    {
        $ImgStr = I('Img');
        $ImgStr = trim($ImgStr, '|');
        $Img = array();
        if (strlen($ImgStr) > 1) {
            $Img = explode('|', $ImgStr);
        }
        $Path = null;
        $newImg = array();
        $newImgStr = null;
        if ($_FILES) {
            $newImgStr = $this->saveimg($_FILES);
            if ($newImgStr) {
                $newImg = explode('|', $newImgStr);
            }

        }
        $Img = array_merge($Img,$newImg);
        $ImgStr = implode("|", $Img);
        $BackCall = I('BackCall');
        $Width = I('u');
        $Height = I('Height');
        if (!$BackCall) {
            $Width = $_POST['BackCall'];
        }
        if (!$Width) {
            $Width = $_POST['Width'];
        }
        if (!$Height) {
            $Width = $_POST['Height'];
        }
        $this->assign('Width', $Width);
        $this->assign('BackCall', $BackCall);
        $this->assign('ImgStr', $ImgStr);
        $this->assign('Img', $Img);
        $this->assign('Height', $Height);
        $this->display('Batchpic');
    }

    //上传图片
    public function upload($width,$height,$where)
    {
        //实例化上传类
        $upload = new Upload();

        //$this->ajaxReturn(array('status'=>22,'msg'=>'success'));          
        //设置允许上传的文件类型
        $upload->exts = array("jpg","jpeg","png");
        //设置允许上传的最大文件
        $upload->maxSize = 10000000;
        //是否生成子目录
        $upload->autoSub = false;
        //保存文件的根目录
        $upload->rootPath = './';
        //保存路径
        $upload->savePath = 'Upload/'.$where.'/'.date('Y')."/".date('m')."/";
        //单文件上传
        //$myFile = $_FILES["myFile"];
        $result = $upload->upload();

        $houzhui = pathinfo($result["file"]['savename'], PATHINFO_EXTENSION);
        $name = pathinfo($result["file"]['savename'], PATHINFO_FILENAME);

        if($result)
        {
            $arr["view"]= $result["file"]['savepath'].$result["file"]['savename'];

            $image = new \Think\Image();
            $path = $arr["view"];
            $image->open($path);

            $image->thumb($width,$height,3)->save($arr["view"]);
            return $arr;
        }
        else
        {   
            
            $msg = $upload->getError();
            return $msg;
        }
    }

    // 上传视频
    public function video()
    {
        $upload = new Upload();

        //设置允许上传的文件类型
        $upload->exts = array("mp4","wmv");
        //设置允许上传的最大文件
        $upload->maxSize = 1000000000;
        //是否生成子目录
        $upload->autoSub = false;
        //保存文件的根目录
        $upload->rootPath = '.';
        $upload->savePath = '/Video/';
        $result = $upload->upload();
        if($result)
        {
            $arr= $result["filevedio"]['savepath'].$result["filevedio"]['savename'];
            return $arr;
        }else{
            return false;
        }
    }
}
