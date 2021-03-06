<?php

//MIPJZ.Com [Don't forget the beginner's mind]

//Copyright (c) 2017~2099 http://MIPJZ.Com All rights reserved.

namespace app\setting\controller;

use think\Request;
use think\Response;

use app\common\controller\AdminBase;

class ApiAdminUpload extends AdminBase
{


    public function index()
    {
    }
 

     public function defaultImgUpload(Request $request)
     {

        if (Request::instance()->isPost()) {
            
            $file = $request->file('fileDataFileName');
            if (empty($file)) {
                return jsonError('图片不存在');
            }
            
            $tempFile = $file->getInfo();
            $ext = getImagetype($tempFile['tmp_name']);
            if ($ext !='png' && $ext!='jpeg' &&  $ext != 'gif' && $ext !='jpg') {
                return jsonError('图片类型错误');
            }
            
            $type = input('post.type');
            $ymd = input('post.ymd');
            $siteId = input('post.siteId');
            $ymd = 1;
            if (empty($type)) {
                $type = 'temp';
            }
            if ($siteId) {
                $type = $siteId;
            }
            try {
                if ($ext == 'jpg') {
                    $srcImage = imagecreatefromjpeg($tempFile['tmp_name']);
                }
                if ($ext == 'png') {
                    $srcImage = imagecreatefrompng($tempFile['tmp_name']);
                }
                if ($ext == 'gif') {
                    $srcImage = imagecreatefromgif($tempFile['tmp_name']);
                }
                $srcW = imagesx($srcImage);
                $srcH = imagesx($srcImage);
            } catch (\Exception $e) {
                return jsonError('图片上传失败');
            }
            $tempContent = file_get_contents($tempFile['tmp_name']);
            if (strpos($tempContent, '<?php') !== false) {
                return jsonError('图片上传失败');
            }
            if (strpos($tempContent, 'eval') !== false) {
                return jsonError('图片上传失败');
            }
            
            $aliyunossInfo = db('Addons')->where('name','aliyunoss')->find();
            $qiniuossInfo = db('Addons')->where('name','qiniuoss')->find();
            if ($aliyunossInfo || $qiniuossInfo) {
                if ($qiniuossInfo) {
                    $title = uuid() . '.' . $ext;
                    $res = model('addons\qiniuoss\model\QiniuossUploads')->defaultUpload($title,$tempContent,$type);
                }
                if ($aliyunossInfo) {
                    $title = uuid() . '.' . $ext;
                    $res = model('addons\aliyunoss\model\AliyunossUploads')->defaultUpload($title,$tempContent,$type);
                }
                if ($res['code'] == 1) {
                    return jsonSuccess('',$res['data']);
                } else {
                    return jsonError($res['msg']);
                }
            } else {
                if (SITE_HOST) {
                    $public = 'public/';
                } else {
                    $public = '';
                }
                if (!is_writable(PUBLIC_PATH . $this->siteInfo['uploadUrl'])) {
                    return jsonError('/uploads 文件夹权限无法写入');
                }
                $uploadsPath  = PUBLIC_PATH . DS . $this->siteInfo['uploadUrl'] . DS . $type . DS . date('Y',time()) . DS . date('m',time()) . DS . date('d',time());
                $info = $file->rule('uniqid')->validate(['ext'=>'jpg,png,gif'])->move($uploadsPath);
                if ($info) {
                    $domain = $this->siteInfo['articleDomain'] ? config('domainStatic') : '';
                    $data = $domain . '/' . $public . $this->siteInfo['uploadUrl'] .'/'.$type.'/' . date('Y',time()) . '/' . date('m',time()) . '/' . date('d',time()) . '/' . $info->getFilename();
                    return jsonSuccess('',$data);
                } else {
                    return jsonError($file->getError());
                }
            }
            

        }

    }
 
    public function defaultVideoUpload(Request $request)
    {
            
        $file = $request->file('fileDataVideo');
        if (empty($file)) {
            return jsonError('视频不存在');
        }
         
        $type = input('post.type');
        $ymd = input('post.ymd');
        $siteId = input('post.siteId');
        $ymd = 1;
        if (empty($type)) {
            $type = 'video';
        }
        if ($siteId) {
            $type = $siteId;
        }
        
        if (SITE_HOST) {
            if ($ymd) {
                if(!is_writable(ROOT_PATH . $this->siteInfo['uploadUrl'])) {
                    return jsonError('/uploads 文件夹权限无法写入');
                }
                $uploadsPath  = ROOT_PATH . DS . $this->siteInfo['uploadUrl'] . DS . $type . DS . date('Y',time()) . DS . date('m',time()) . DS . date('d',time());
                
                $info = $file->rule('uniqid')->validate(['ext'=>'mp4'])->move($uploadsPath);

            } else {

                $info = $file->rule('uniqid')->validate(['ext'=>'mp4'])->move(ROOT_PATH . DS . $this->siteInfo['uploadUrl'] . DS . $type);

            }

        } else {
            if ($ymd) {
                if(!is_writable(PUBLIC_PATH . $this->siteInfo['uploadUrl'])) {
                    return jsonError('/uploads 文件夹权限无法写入');
                }
                $uploadsPath  = PUBLIC_PATH . DS . $this->siteInfo['uploadUrl'] . DS . $type . DS . date('Y',time()) . DS . date('m',time()) . DS . date('d',time());
                $info = $file->rule('uniqid')->validate(['ext'=>'mp4'])->move($uploadsPath);

            } else {

                $info = $file->rule('uniqid')->validate(['ext'=>'mp4'])->move(PUBLIC_PATH . DS . $this->siteInfo['uploadUrl'] . DS . $type);

            }

        }

        if ($info) {

            if ($ymd) {

                return jsonSuccess('','/' . $this->siteInfo['uploadUrl'] .'/'.$type.'/' . date('Y',time()) . '/' . date('m',time()) . '/' . date('d',time()) . '/' . $info->getFilename());

            } else {

                return jsonSuccess('','/' . $this->siteInfo['uploadUrl'] .'/'.$type.'/'.$info->getFilename());

            }

        } else {

            return jsonError($file->getError());

        }

    }


}