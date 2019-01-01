<?php
use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;

    function getOS() {
        $os_name=PHP_OS;
        if (strpos($os_name,"Linux") !== false) {
            $os_str="Linux";
        }else if (strpos($os_name,"WIN") !== false) {
            $os_str="Win";
        } else {
            $os_str="Mac";
        }
        return $os_str; 
    }
    function getRenderFile () {
        if (getOS() == 'Mac') {
            $file = ROOT_PATH . 'system/vendor/bin/macphantomjs';
        }
            $file = ROOT_PATH . 'system/vendor/bin/phantomjs.exe';
        }
        if (getOS() == 'Linux') {
            $file = ROOT_PATH . 'system/vendor/bin/phantomjs';
        }
        return $file;
    }
    
    function getRenderHtml($url) {
        if (!$url) {
            return false;
        }
        $client = Client::getInstance();
        $client->getEngine()->setPath(getRenderFile());
        $client->isLazy();
        $request = $client->getMessageFactory()->createRequest();
        $response = $client->getMessageFactory()->createResponse();
//      $request->setHeaders(array('Referer' => 'https://www.baidu.com'));
        $request->setUrl($url);
        $request->setMethod('GET');
        $request->setTimeout(5000);
        $request->setDelay(5);
        $client->send($request, $response);
        $res = $response->getContent();
        if ($res) {
            $htmlHeader = '<!DOCTYPE html><html>';
            $htmlFooter = '</html>';
            return $htmlHeader.$res.$htmlFooter;
        } else {
            return '';
        }
    }
    function viewRender() {
        //  $location = ROOT_PATH . 'addons/test';
    //  $serviceContainer = ServiceContainer::getInstance();
    //  $procedureLoader = $serviceContainer->get('procedure_loader_factory')->createProcedureLoader($location);
    //  $client = Client::getInstance();
    //  $client->getEngine()->setPath(ROOT_PATH . 'system/vendor/bin/macphantomjs');
    //  $client->setProcedure('test11');
    //  $client->getProcedureLoader()->addLoader($procedureLoader);
    //  $request  = $client->getMessageFactory()->createRequest();
    //  $response = $client->getMessageFactory()->createResponse();
    //  $client->send($request, $response);
    }
			if (strpos($ip,$value) !== false) {
		}