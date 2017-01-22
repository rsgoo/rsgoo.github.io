<?php

/**
 * Description of BikerController.php
 * 
 * @author Liu Junjie (jjliu@weiou.com)
 * @Date 2016-7-5 16:22:39
 * @copyright 为偶公司
 */
class Mazu2Controller extends Weiou_Controller_Web {

//  列表页
    public function indexAction() {
        if ($this->_weiouUA->isWechat() || $this->_weiouUA->isQQ()) {
            if (!$this->getData("userinfo")) {
                $this->_redirect("/login");
                return;
            }
        }
        $pageSize = $this->getParam("pageSize", 10);
        $pageNum = $this->getParam("page", 1);

        $bikerService = new Weiou_Service_Biker();
        $rows = $bikerService->getBikers($pageSize, $pageNum);

        $picUtil = new Weiou_Util_Pic();

        $bikers = array();
        foreach ($rows as $r) {
            $keys = explode(",", $r['pickeys']);
            $r['picUrl'] = $picUtil->getImageViewBySizeForCut($keys[0], 300, 400);
            $bikers[] = $r;
        }
        $this->logger->info($bikers);
        $this->view->bikers = $bikers;

        if ($this->_weiouUA->isMobile()) {
            $this->view->setNoRender();
            echo $this->view->render("index_mobile.html");
        }
    }

//  列表页
    public function signupAction() {
        if ($this->_weiouUA->isWechat() || $this->_weiouUA->isQQ()) {
            if (!$this->getData("userinfo")) {
                $this->_redirect("/login");
                return;
            }
        }

        if ($this->_weiouUA->isMobile()) {
            $this->view->setNoRender();
            echo $this->view->render("signup_mobile.html");
        }
    }

//  列表页
    public function saveAction() {

        $this->view->setNoRender();

        $result = new Weiou_JsonModel_ResultForIos();
        $bikerForm = new Weiou_Form_Biker();


        $bikerService = new Weiou_Service_Biker();
        $re = $bikerService->addBiker($bikerForm);
        $this->logger->info($result->toJson());
        echo $result->toJson();
    }

//  列表页
    public function userAction() {

        if ($this->_weiouUA->isWechat() || $this->_weiouUA->isQQ()) {
            if (!$this->getData("userinfo")) {
                $this->_redirect("/login");
                return;
            }
        }

        $uid = $this->getParam("uid");

        if ($uid) {
            $bikerService = new Weiou_Service_Biker();
            $biker = $bikerService->getBikerByUid($uid);

            if ($biker) {

                $picUtil = new Weiou_Util_Pic();
                $keys = explode(",", $biker['pickeys']);
                $picUrls = array();
                foreach ($keys as $k) {
                    $picUrl = $picUtil->getImageViewBySize($k, 800);
                    $picUrls[] = $picUrl;
                }
                
                if (strpos($biker["videoUrl"],"http") === 0) {
                    
                } else {
                    $biker["videoUrl"] = "";
                }

                $this->logger->info($picUrls);
                $biker["picUrls"] = $picUrls;

                $this->logger->info($biker);
                $this->view->biker = $biker;

                if ($this->_weiouUA->isMobile()) {
                    $this->view->setNoRender();
                    echo $this->view->render("user_mobile.html");
                }
            } else {
                $this->view->setNoRender();
                $this->_redirect("/mazu2");
                return;
            }
        } else {
            $myInfo = $this->getData("userinfo");
            if ($myInfo) {
                $this->view->setNoRender();
                $myUserId = $myInfo["user_id"];
                $this->_redirect("/mazu2/user?uid=" . $myUserId);
                return;
            } else {
                $this->view->setNoRender();
                $this->_redirect("/mazu2/");
                return;
            }
        }
    }

    public function likeAction() {
        $this->view->setNoRender();

        $id = $this->getParam("id");
        $userinfo = $this->getData("userinfo");
        $userId = $userinfo['user_id'];

        $result = new Weiou_JsonModel_ResultForIos();
        $result->state = 103;

        if ($id && $userId) {
            $bikerService = new Weiou_Service_Biker();
            $re = $bikerService->addLike($id, $userId);

            if ($re == 1) {
                $result->state = 0;
            } else {
                $result->state = 2099;
            }
        }
        echo $result->toJson();
    }

}
