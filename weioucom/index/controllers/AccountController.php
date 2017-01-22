<?php

/**
 * @Description
 * @Author jiangyuchao
 * @E-mail jiangyc0125@163.com
 * @Date 2014-12-8 上午10:39:25
 * @Version V1.0
 */
class AccountController extends Weiou_Controller_Web {

    public function init() {
        parent::init();
        $this->setNoCacheHeader();
    }

    /**
     * 忘记密码，重置密码
     */
    public function resetPassAction() {
        $code = $this->getParam("code");  // email  md5
        $key = $this->getParam('key');

        $passResetModel = new Weiou_Model_PassReset();

        $checked = false;
        $row = $passResetModel->getPassResetForKey($key);
        if ($row) {
            if ($code == md5($row["email"] . "x--weiou.com--x")) {
                $checked = true;
            }
        }

        if ($checked) {
            $save = $this->getParam('save', 0);
            if ($save == 1) {
                $this->view->setNoRender();
                echo "save";

                $pass = $this->getParam('pass');
                $pass2 = $this->getParam('pass2');

                if ($pass && $pass2 && $pass == $pass2) {

                    $userModel = new Weiou_Model_User();
                    $result = $userModel->updatePassword($row['user_id'], $pass);
                    if ($result) {
                        $this->view->msg = "修改成功。";
                        if ($this->_weiouUA->isMobile()) {
                            echo $this->view->render("success_mobile.html");
                        } else {
                            echo $this->view->render("success.html");
                        }
                    } else {
                        $this->view->msg = "后台繁忙，请稍后再试。";
                        if ($this->_weiouUA->isMobile()) {
                            echo $this->view->render("error_mobile.html");
                        } else {
                            echo $this->view->render("error.html");
                        }
                    }
                } else {
                    $this->view->msg = "您的请求缺少了必要参数，或者参数错误。请检查您的请求。";
                    if ($this->_weiouUA->isMobile()) {
                        echo $this->view->render("error_mobile.html");
                    } else {
                        echo $this->view->render("error.html");
                    }
                }
            } else {
                $this->view->code = $code;
                $this->view->key = $key;

                $this->view->nickname = $row["nickname"];

                if ($row["phone"]) {
                    $this->view->account = substr_replace($row["phone"], "***", 5, 5);
                } else {
                    $this->view->account = substr_replace($row["email"], "***", 5, 5);
                }

                if ($this->_weiouUA->isMobile()) {
                    $this->view->setNoRender();
                    echo $this->view->render("resetpass_mobile.html");
                }
            }
        } else {
            $this->view->setNoRender();
            $this->view->msg = "您的请求缺少了必要参数，或者参数错误。请检查您的请求。";

            if ($this->_weiouUA->isMobile()) {
                echo $this->view->render("error_mobile.html");
            } else {
                echo $this->view->render("error.html");
            }
        }
    }

    /**
     * 忘记密码
     */
    public function forgotPasswordAction() {
        $this->setNoCacheHeader();
        $type = $this->getParam("type", 1);
        $verifyCode = $this->getParam('verifyCode');
        $pw = $this->getParam("password");

        $result = new Weiou_JsonModel_ResultForIos();
        $acc = null;
        if ($type == 1) {
            $phoneNumber = $this->getParam('phone');
            $countryCode = $this->getParam('countryCode');
            if ($countryCode && $phoneNumber) {
                $acc = $countryCode . $phoneNumber;
            }
        } else if ($type == 2) {
            $acc = $this->getParam("email");
        }
        $accountService = new Weiou_Service_Account();
        $result->state = $accountService->forgotPassword($type, $acc, $pw, $verifyCode);

        echo $result->toJson();
    }

    public function changePasswordAction() {
        $this->setNoCacheHeader();
        $myinfo = $this->getData('userinfo');
        $oldPw = trim($this->getParam('oldPassword'));
        $newPw = trim($this->getParam('newPassword'));
        $json = array();
        if ($oldPw && $newPw) {
            $user = new Weiou_Model_User();
            if ($myinfo['country_code'] && $myinfo['user_phone']) {
                $re = $user->authUserForIOS($myinfo['country_code'], $myinfo['user_phone'], $oldPw);
            } else {
                $re = $user->authUserByEmail($myinfo['email'], $oldPw);
            }
            if ($re) {
                $result = $user->updatePassword($myinfo['user_id'], $newPw);
                if ($result) {
                    $json['state'] = 0;
                    $json['data'] = (object) NULL;
                } else {
                    $json['state'] = 105;
                    $json['data'] = (object) NULL;
                }
            } else {
                $json['state'] = 107;
                $json['data'] = (object) NULL;
            }
        } else {
            $json['state'] = 103;
            $json['data'] = (object) NULL;
        }
        echo json_encode($json);
    }

}
