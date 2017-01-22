<?php

/**
 * @Description
 * @Author jiangyuchao
 * @E-mail jiangyc0125@163.com
 * @Date 2014-12-9 下午3:43:10
 * @Version V1.0
 */
class UserController extends Weiou_Controller_Web {

    public function indexAction() {
        $userId = $this->getParam("uid");

        $myInfo = $this->getApp()->getData("userinfo");

        if ($userId == null && !$myInfo) {
            $this->_redirect("/login");
        } else {
            $userService = new Weiou_Service_User();
            $userinfo = $userService->getUserInfo($userId);
            $this->view->userId = $userinfo['userId'];
            $this->view->userName = $userinfo['userName'];
//        $this->view->userLogo = $userinfo['userLogo'];
            $this->view->userLogo = $userinfo['userLogoOriginal'];
            $this->view->isoCountryCode = $userinfo['isoCountryCode'];
            $this->view->fanCount = $userinfo['fans'];
            $this->view->followCount = $userinfo['followers'];
            $this->view->address = $userinfo['address'];
            $this->view->userSign = $userinfo['userSign'];
            $this->view->postCount = $userinfo['posts'];
            $this->view->userLikeCount = $userinfo['userLikes'];
            $this->view->isFollowed = $userinfo['isFollowed'];

//            if ($userinfo['userLogoOriginal'] == "") {
//                $this->view->userLogo = "/img/user-avatar.jpg";
//            }
        }
    }

    /**
     * 获取我的相册
     */
    public function getPhotosAction() {
        $userId = $this->getParam("uid");
        if (!$userId) {
            $myinfo = $this->getData('userinfo');
            $userId = $myinfo['user_id'];
        }
        $pageType = $this->getParam('pageType', 'pre');
        $pageSize = $this->getParam('pageSize', 40);
//        $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
        $postID = $this->getParam('postID');
//        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
//        $hashtagID = $this->getParam('hashtagID') ? trim($this->getParam('hashtagID')) : null;
        $json = array();
        $jsonData = array();
        $jsonData['list'] = array();

        if ($userId) {
            $post = new Weiou_Model_Post();
            $postService = new Weiou_Service_Post();
            if ($pageType == 'pre') {
                $pageType = 'refresh';
                $timestamp = NULL;
            } else {     //pageType=more
                if ($postID) {
                    $morePostDetail = $post->getPostDetail($postID);
                    $pageType = 'more';
                    $timestamp = $morePostDetail['post_show_time'];
                } else {
                    $pageType = 'refresh';
                    $timestamp = NULL;
                }
            }
            $cursor = $post->getPostlistByUserid($userId, $timestamp, (int) $pageSize, $pageType);
            $listData = $postService->_gridViewForWebDataModel($cursor, 300);
            $jsonData['list'] = $listData['list'];
        }
//        $json['state'] = 0;
//        $json['data'] = $jsonData;
//        echo json_encode($json);
        $this->logger->info($jsonData['list']);
        $this->view->photos = $jsonData['list'];
    }

    /**
     * 我的粉丝
     */
    public function fansAction() {

        $this->logger->info($this->params);

        $userId = $this->getParam("uid", "");
        $pageType = $this->getParam('pageType', 'pre');
        $pageSize = $this->getParam('pageSize', 50);
        $sec = $this->getParam('sec', 0);
        $inc = $this->getParam('inc', 0);
        if (!$userId) {
            $myinfo = $this->getData('userinfo');
            $userId = $myinfo['user_id'];
        }
        $timestamp = NULL;
        if ($sec) {
            $timestamp = new MongoTimestamp($sec, $inc);
        }
        if ($timestamp == NULL) {
            $pageType = 'refresh';
        }

        $result = new Weiou_JsonModel_ResultForIos();
        $userService = new Weiou_Service_User();
        $list = $userService->getFansByUserID($userId, $timestamp, $pageType, $pageSize);

        $result->state = 0;
        $result->data = array(
            "list" => $list
        );

//        echo $result->toJson();
        $this->view->users = $list;
        $this->view->setNoRender(true);
        if ($pageType == "more") {
            echo $this->view->render('getusers.html');
        } else {
            $this->view->title = "粉丝";
            $this->view->userId = $userId;
            $this->view->dataUrl = "/user/fans";
            echo $this->view->render('userlist.html');
        }
    }

    /**
     * 我的关注
     */
    public function followingAction() {
        $userId = $this->getParam("uid", "");
        $pageType = $this->getParam('pageType', 'pre');
        $pageSize = $this->getParam('pageSize', 50);
        $sec = $this->getParam('sec', 0);
        $inc = $this->getParam('inc', 0);
        if (!$userId) {
            $myinfo = $this->getData('userinfo');
            $userId = $myinfo['user_id'];
        }

        $timestamp = NULL;
        if ($sec) {
            $timestamp = new MongoTimestamp($sec, $inc);
        }
        if ($timestamp == NULL) {
            $pageType = 'refresh';
        }

        $result = new Weiou_JsonModel_ResultForIos();
        $userService = new Weiou_Service_User();
        $list = $userService->getFollowersByUserID($userId, $timestamp, $pageType, $pageSize);

        $result->state = 0;
        $result->data = array(
            "list" => $list
        );

//        echo $result->toJson();
        $this->view->users = $list;
        $this->view->setNoRender(true);
        if ($pageType == "more") {
            echo $this->view->render('getusers.html');
        } else {
            $this->view->title = "关注";
            $this->view->userId = $userId;
            $this->view->dataUrl = "/user/following";
            echo $this->view->render('userlist.html');
        }
    }
    
    public function testAction(){
        $userModel = new Weiou_Model_User();
        
        $users = $userModel->getAllUser();
        
        $favModel = new Weiou_Model_Fav();
        
        foreach($users as $u){
            echo $u["user_id"]."<br>";
            
          
            $userId = $u["user_id"];

            $favModel->add("故里", "", $userId);
            $favModel->add("新奇发现", "", $userId);
            $favModel->add("那些年我们到过的地儿", "", $userId);
            $favModel->add("旅行心愿单", "", $userId);
        }
    }

}
