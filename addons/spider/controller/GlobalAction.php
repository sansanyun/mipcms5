<?php
//MIPJZ.COM [Don't forget the beginner's mind]
//Copyright (c) 2017~2099 http://MIPJZ.COM All rights reserved.
namespace addons\spider\controller;
use think\Request;
use think\Controller;
class GlobalAction extends Controller
    public function spider()
        $addonsInfo = config('addonsInfo');
            if (strpos($userAgent,"Mobile") !== false) {
                if (strpos($userAgent,"render") !== false) {
                    db('spiders')->insert(array('uuid' => uuid(),'add_time' => time(),'type' => 'mobileRender','pageUrl' => $this->view->siteUrl, 'ua' => Request::instance()->ip(), 'vendor' => 'baidu'));
                } else {
                    db('spiders')->insert(array('uuid' => uuid(),'add_time' => time(),'type' => 'mobile','pageUrl' => $this->view->siteUrl, 'ua' => Request::instance()->ip(), 'vendor' => 'baidu'));
                }
            } else {
                if (strpos($userAgent,"render") !== false) {
                    db('spiders')->insert(array('uuid' => uuid(),'add_time' => time(),'type' => 'pcRender','pageUrl' => $this->view->siteUrl, 'ua' => Request::instance()->ip(), 'vendor' => 'baidu'));
                } else {
                    db('spiders')->insert(array('uuid' => uuid(),'add_time' => time(),'type' => 'pc','pageUrl' => $this->view->siteUrl, 'ua' => Request::instance()->ip(), 'vendor' => 'baidu'));
                }
            }
        }

    }
}