<?php

/**
 * @Description
 * @Author jiangyuchao
 * @E-mail jiangyc0125@163.com
 * @Date 2014-12-9 下午2:31:07
 * @Version V1.0
 */
include_once 'Filter.class.php';

class indexFilter extends Filter {
    /*
     * 该controller中的所有action都是默认可以访问的
     *
     */

    protected $_openController = array(
        'about', 'discover', 'follow', 'share', 'login', 'signup', 'user', 'system', 'biker'
    );
    protected $_openRS = array(
        //游客权限
        'post' => array("detail", "uploadcallback", "index", "index2", "getuploadtoken"),
        'index' => array("index", "getlatestpostsforscrollview", "gethottestpostsforscrollview", "test"),
        'mazu' => array("index", "user", "signup", "save"),
        'mazu2' => array("index", "user", "signup", "save"),
        'account' => array('resetpass', 'resetpasssave')
    );

    public function doFilter() {
//        $session = $this->getApp()->loadUtilClass("SessionUtil");
        $session = new Weiou_Common_SessionUtil();
        $user_id = $session->get("session_user_id");

        if ($user_id) {
            $user = new Weiou_Model_User();
            if ($user_id) {
                $userinfo = $user->getUserinfoByID($user_id);
            }
            if ($userinfo) {
                $this->getApp()->putData("userinfo", $userinfo);
                $year = date("Y");
                $month = date("m");
                $day = date("d");
                $dayBegin = mktime(0, 0, 0, $month, $day, $year);
                $dayEnd = mktime(23, 59, 59, $month, $day, $year);
                $loginInfo = $user->getOnedayLoginByUserid($user_id, $dayBegin, $dayEnd);
                if (!$loginInfo) {
                    $user->addLogin($userinfo['user_id'], time());
                }
            } else {
                $session->clear();
//				$json = array();
//				$json['state'] = 1;
//				$json['data'] = (object)NULL;
//				echo json_encode($json);
                exit();
            }
        } else {
            if (!$this->_isCanVisit($this->getCName(), $this->getAName())) {
                $respType = $this->getRequest()->get("respType");
                if ($respType == "json") {
                    $json = array();
                    $json['state'] = 1;
                    $json['data'] = (object) NULL;
                    echo json_encode($json);
                    exit();
                } else {
                    // 记录当前的url
                    $this->getRequest()->setLoginCallbackUri();
                    $this->getApp()->gotoUrl("login", "index");
                    exit();
                }
            }
        }
        if (strtolower($this->getCName()) != "login") {
            $this->getRequest()->setLoginCallbackUri();
        }
    }

    protected function _isOpenRs($cName, $aName) {
        if (in_array($cName, $this->_openController)) {
            return true;
        } else {
            return array_key_exists($cName, $this->_openRS) ? in_array($aName, $this->_openRS[$cName]) : false;
        }
    }

    protected function _isCanVisit($cName, $aName) {
        if ($this->_isOpenRs(strtolower($cName), strtolower($aName)) ||
                $this->_isOpenRs(strtolower($cName), $aName)) {
            return true;
        } else {
            return false;
        }
    }

}
