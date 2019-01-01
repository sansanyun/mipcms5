<?php
//MIPJZ.COM [Don't forget the beginner's mind]
//Copyright (c) 2017~2099 http://MIPJZ.COM All rights reserved.
namespace app\setting\controller;
use think\Request;
use app\common\controller\AdminBase;
    public function index() {

    }
    
    public function urlPost(Request $request) {
        $postAddress = input('post.postAddress');
        if (!$postAddress) {
            return jsonError('请先去设置推送的接口');
        }
        if (!$url) {
            return jsonError('没有检测到你推送的页面地址');
        }
        $urls[] = $url;
     	return jsonSuccess($result);

    }
    
    public function itemEdit()
    {
        $id = input('post.id');
        $settingInfo = json_decode(input('post.setting'));
        foreach ($settingInfo as $key => $val) {
            db('domainSettings')->where('id',$id)->update(array($key => $val));
        }
        return jsonSuccess('成功');
    }
  
    public function itemFind()
    {
        $id = input('post.id');
        $itemInfo = db('domainSettings')->where('id',$id)->find();
        return jsonSuccess('成功',$itemInfo);
    }
      
}