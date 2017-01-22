<?php

/**
 * Description of SignupController.php
 * 
 * @author Liu Junjie (jjliu@weiou.com)
 * @Date 2016-2-15 9:47:01
 * @copyright 为偶公司
 */
class SignupController extends Weiou_Controller_Web {
    /*
     * 默认注册页面
     */

    public function indexAction() {
        $this->view->setNoRender();
        if ($this->_weiouUA->isMobile()) {
            echo $this->view->render("mobile_mobile.html");
        } else {
            echo $this->view->render("mobile.html");
        }
    }

    /*
     * 手机注册页面
     */

    public function mobileAction() {
        if ($this->_weiouUA->isMobile()) {
            $this->view->setNoRender();
            echo $this->view->render("mobile_mobile.html");
        }
    }

    /*
     * 邮箱注册页面
     */

    public function emailAction() {
        if ($this->_weiouUA->isMobile()) {
            $this->view->setNoRender();
            echo $this->view->render("email_mobile.html");
        }
    }

    /**
     * 注册提交
     */
    public function registerAction() {
        $this->setNoCacheHeader();
        $this->view->setNoRender();
        $this->logger->info($this->params);

        $type = $this->getParam("type", 1);
        $pw = $this->getParam('password');

        $result = new Weiou_JsonModel_ResultForIos();

        $accountService = new Weiou_Service_Account();
        if ($type == 1) {
            $phoneNumber = $this->getParam('phone');
            $countryCode = $this->getParam('countryCode');
            $verifyCode = $this->getParam('verifyCode');
            $result = $accountService->registerByPhone($phoneNumber, $countryCode, $verifyCode, $pw);
        } else if ($type == 2) {
            $vCode = $this->getParam("vCode", "");

            $sessionUtil = $this->getApp()->loadUtilClass("SessionUtil");
            $vCodeSession = $sessionUtil->get("vCode");

            if ($vCodeSession != "" && strtolower($vCodeSession) == strtolower($vCode)) {
                $email = $this->getParam('email');
                $result = $accountService->registerByEmail($email, $pw);
            } else {
                $result->state = 106;  //邮箱注册的图文验证码错误
            }
        } else {
            $result->state = 103;     //参数错误
        }

        echo $result->toJson();
        $this->logger->info($result->toJson());
    }

    public function  xxAction(){
        echo "hello,world";
        exit;
    }

}
