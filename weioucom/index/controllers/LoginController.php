<?php

/**
 * Description of LoginController.php
 *
 * @author Liu Junjie (jjliu@weiou.com)
 * @Date 2016-2-15 10:05:51
 * @copyright 为偶公司
 */
class LoginController extends Weiou_Controller_Web {
    /*
     * 默认登陆页面
     */

    public function indexAction() {
        $this->view->setNoRender();
        $isRender = 0;
        if ($this->_weiouUA->isMobile()) {
            if ($this->_weiouUA->isWechat()) {
                $this->wechatAction();
//                echo $this->view->render("mobile.html");
            } else if ($this->_weiouUA->isQQ()) {
                $this->qqAction();
//                $isRender = 1;
            } else {
                $isRender = 1;
            }
        } else {
            $isRender = 1;
        }

        if ($isRender) {
            if ($this->_weiouUA->isMobile()) {
                echo $this->view->render("mobile_mobile.html");
            } else {
                echo $this->view->render("mobile.html");
            }
        }
    }

    /*
     * 邮箱登陆页面
     */

    public function emailAction() {
        if ($this->_weiouUA->isMobile()) {
            $this->view->setNoRender();
            echo $this->view->render("email_mobile.html");
        }
    }

    /*
     * 手机登陆页面
     */

    public function mobileAction() {
        if ($this->_weiouUA->isMobile()) {
            $this->view->setNoRender();
            echo $this->view->render("mobile_mobile.html");
        }
    }

    /**
     * 登录
     */
    public function loginAction() {
        $this->setNoCacheHeader();
        $this->view->setNoRender();

        $type = $this->getParam("type", 1); //1  手机号登录，2 邮箱登录，3  微信登录， 4 qq登录，5  微博登录

        $this->logger->info($this->params);

        $accountService = new Weiou_Service_Account();
        $result = new Weiou_JsonModel_ResultForIos();
        $result->state = 103; //参数错误

        if ($type == 1) {  //手机号登录
            $phoneNumber = $this->getParam("phone");
            $countryCode = $this->getParam("countryCode");
            $pw = $this->getParam("password");

            $result = $accountService->loginByPhone($phoneNumber, $pw, $countryCode);
        } else if ($type == 2) {//邮箱登陆
            $email = $this->getParam("email");
            $pw = $this->getParam("password");

            $result = $accountService->loginByEmail($email, $pw);
        } else if ($type == 3) {//微信
//            $openId = $this->getParam('openId');
//            $accessToken = $this->getParam('accessToken');
//            $refreshToken = $this->getParam('refreshToken');
//            $nickName = $this->getParam('nickName', "");
//            $logoKey = $this->getParam('logoKey', "");
//
//            $result = $accountService->thirdPartyLogin($type, $openId, $accessToken, $refreshToken, $nickName, $logoKey);
        } else if ($type == 4) {//qq
        } else if ($type == 5) {//微博
        }

        if ($result->state == 0) {
            $userInfo = $result->data;

            $callbackUrl = $this->getRequest()->getLoginCallbackUri();
            $result->data["callbackUrl"] = $callbackUrl;
            $this->getRequest()->delLoginCallbackUri();
            $userId = $userInfo['userId'];
            $sessionUtil = new Weiou_Common_SessionUtil();
            $sessionUtil->set("session_user_id", $userId);
        }

        echo $result->toJson();
    }

    /*
     * 退出
     */

    public function logoutAction() {
        $this->view->setNoRender(true);
        $accountService = new Weiou_Service_Account();
        $accountService->logout();

        $this->_redirect("/");
    }

    /*
     * 微信登录
     */

    public function wechatAction() {
        $this->view->setNoRender();
        $state = md5(uniqid(rand(), TRUE)); //CSRF protection

        $sessionUtil = $this->getApp()->loadUtilClass("SessionUtil");
        $sessionUtil->set("sessionWechatLoginState", $state);

        $wechatType = "web";
        if ($this->_weiouUA->isWechat()) {
            $wechatType = "mp";
        }
        $wechatUtil = new Weiou_Util_Wechat($wechatType);
        $loginUrl = $wechatUtil->getWebLoginUrl($state);

        $this->_redirect($loginUrl);
    }

    /*
     * 微信登录，callback
     */

    public function wechatCallbackAction() {
        $this->view->setNoRender();
        $this->logger->info($this->params);

        $code = $this->getParam("code");
        $state = $this->getParam("state");

        $sessionUtil = new Weiou_Common_SessionUtil();
        $sessionState = $sessionUtil->get("sessionWechatLoginState");

        $success = 0;
        if ($code && $state && $state == $sessionState) {
            $wechatType = "web";
            if ($this->_weiouUA->isWechat()) {
                $wechatType = "mp";
            }
            $wechatUtil = new Weiou_Util_Wechat($wechatType);
            $accessTokenInfo = $wechatUtil->accessToken($code);
            $this->logger->info($accessTokenInfo);
            if ($accessTokenInfo && isset($accessTokenInfo['access_token']) && isset($accessTokenInfo['openid'])) {
                $accessToken = $accessTokenInfo['access_token'];
                $openId = $accessTokenInfo['openid'];
                $unionId = isset($accessTokenInfo['unionid']) ? $accessTokenInfo['unionid'] : null;

                $userinfo = $wechatUtil->userinfo($openId, $accessToken);
                if ($userinfo) {
                    $this->logger->info($userinfo);
                    $nickName = $userinfo["nickname"];
                    $logoUrl = $userinfo["headimgurl"];

                    if (!$unionId) {
                        $unionId = $userinfo["unionid"];
                    }
                } else {
                    $nickName = "";
                    $logoUrl = "";
                }

                if ($unionId) {
                    $type = 3; //wechat
                    $snsId = $unionId;
                    $accountService = new Weiou_Service_Account();
                    $weiouUser = $accountService->getUserinfoByThirdPaty($type, $snsId, $nickName, $logoUrl);

                    if ($weiouUser) {
                        $userId = $weiouUser['userId'];

                        $sessionUtil->set("session_user_id", $userId);

                        $success = 1;
                    }
                }
            }
        }
        if ($success == 1) {
            $callbackUrl = $this->getRequest()->getLoginCallbackUri();
            if ($callbackUrl) {
                $this->getRequest()->delLoginCallbackUri();
                $this->_redirect($callbackUrl);
            } else {
                $this->_redirect("/user");
            }
        } else {
            $this->_redirect("/login");
        }
    }

    /*
     * 测试QQ登录
     */

    public function testAction() {

    }

    public function qqAction() {
//        $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
        $this->view->setNoRender();
        $qqUtil = new Weiou_Util_Qq("web");
        $state = md5(uniqid(rand(), TRUE)); //CSRF protection

        $sessionUtil = $this->getApp()->loadUtilClass("SessionUtil");
        $sessionUtil->set("sessionQqLoginState", $state);

        $loginUrl = $qqUtil->getWebLoginUrl($state);
        $this->logger->info($loginUrl);
        $this->_redirect($loginUrl);
    }

    public function qqCallbackAction() {
        $this->view->setNoRender();
        $this->logger->info($this->params);

        $code = $this->getParam("code");
        $state = $this->getParam("state");

        $sessionUtil = new Weiou_Common_SessionUtil();
        $sessionState = $sessionUtil->get("sessionQqLoginState");

        $success = 0;
        if ($code && $state && $state == $sessionState) {
            $qqUtil = new Weiou_Util_Qq("web");
            $accessTokenInfo = $qqUtil->accessToken($code);
            $this->logger->info($accessTokenInfo);
            if ($accessTokenInfo) {
                $accessToken = $accessTokenInfo['access_token'];
                $openIdInfo = $qqUtil->getOpenId($accessToken);
                if ($openIdInfo && isset($openIdInfo['openid'])) {
                    $openId = $openIdInfo['openid'];
                    $userinfo = $qqUtil->userinfo($openId, $accessToken);
                    if ($userinfo) {
                        $this->logger->info($userinfo);
                        $nickName = $userinfo["nickname"];

                        if (isset($userinfo["figureurl_qq_2"]) && $userinfo["figureurl_qq_2"] != "") {
                            $logoUrl = $userinfo["figureurl_qq_2"];
                        } else if (isset($userinfo["figureurl_2"]) && $userinfo["figureurl_2"] != "") {
                            $logoUrl = $userinfo["figureurl_2"];
                        } else if (isset($userinfo["figureurl_qq_1"]) && $userinfo["figureurl_qq_1"] != "") {
                            $logoUrl = $userinfo["figureurl_qq_1"];
                        } else if (isset($userinfo["figureurl_1"]) && $userinfo["figureurl_1"] != "") {
                            $logoUrl = $userinfo["figureurl_1"];
                        } else if (isset($userinfo["figureurl"]) && $userinfo["figureurl"] != "") {
                            $logoUrl = $userinfo["figureurl"];
                        } else {
                            $logoUrl = "";
                        }
                    } else {
                        $nickName = "";
                        $logoUrl = "";
                    }
                    $type = 4; //qq
                    $snsId = $openId;
                    $accountService = new Weiou_Service_Account();
                    $weiouUser = $accountService->getUserinfoByThirdPaty($type, $snsId, $nickName, $logoUrl);

                    if ($weiouUser) {
                        $userId = $weiouUser['userId'];

                        $sessionUtil->set("session_user_id", $userId);

                        $success = 1;
                    }
                }
            }
        }
        if ($success == 1) {
            $callbackUrl = $this->_request->getLoginCallbackUri();
            if ($callbackUrl) {
                $this->getRequest()->delLoginCallbackUri();
                $this->_redirect($callbackUrl);
            } else {
                $this->_redirect("/user");
            }
        } else {
            $this->_redirect("/login");
        }
    }

    public function weiboAction() {
        $this->view->setNoRender();
        $weiboUtil = new Weiou_Util_Weibo("web");
        $state = md5(uniqid(rand(), TRUE)); //CSRF protection
        $sessionUtil = $this->getApp()->loadUtilClass("SessionUtil");
        $sessionUtil->set("sessionWeiboLoginState", $state);
        $loginUrl = $weiboUtil->getWebLoginUrl($state);
        $this->logger->info($loginUrl);
        $this->_redirect($loginUrl);
    }

    public function weiboCallbackAction() {
        $this->view->setNoRender();
        $this->logger->info($this->params);

        $code = $this->getParam("code");
        $state = $this->getParam("state");

        $sessionUtil = new Weiou_Common_SessionUtil();
        $sessionState = $sessionUtil->get("sessionWeiboLoginState");

        $success = 0;
        if ($code && $state && $state == $sessionState) {
            $weiboUtil = new Weiou_Util_Weibo("web");
            $accessTokenInfo = $weiboUtil->accessToken($code);
            if ($accessTokenInfo && $accessTokenInfo['access_token'] && $accessTokenInfo['uid']) {
                $accessToken = $accessTokenInfo['access_token'];
                $uid = $accessTokenInfo['uid'];

//                $tokenInfo = $weiboUtil->getTokenInfo($accessToken);   //用于校验授权情况
                $userinfo = $weiboUtil->userinfo($uid, $accessToken);
                if ($userinfo) {
                    $this->logger->info($userinfo);
                    $nickName = $userinfo["screen_name"];
                    $logoUrl = $userinfo["profile_image_url"];
                } else {
                    $nickName = "";
                    $logoUrl = "";
                }

                $type = 5; //weibo
                $snsId = $uid;
                $accountService = new Weiou_Service_Account();
                $weiouUser = $accountService->getUserinfoByThirdPaty($type, $snsId, $nickName, $logoUrl);

                if ($weiouUser) {
                    $userId = $weiouUser['userId'];

                    $sessionUtil->set("session_user_id", $userId);

                    $success = 1;
                }
            }
        }
        if ($success == 1) {
            $callbackUrl = $this->_request->getLoginCallbackUri();
            if ($callbackUrl) {
                $this->getRequest()->delLoginCallbackUri();
                $this->_redirect($callbackUrl);
            } else {
                $this->_redirect("/user");
            }
        } else {
            $this->_redirect("/login");
        }
    }

    public function cancelWeiboCallbackAction() {
        $this->view->setNoRender();
        $this->logger->info($this->params);
    }

}
