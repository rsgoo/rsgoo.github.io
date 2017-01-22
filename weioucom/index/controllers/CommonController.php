<?php

/**
 * @Description
 * @Author jiangyuchao
 * @E-mail jiangyc0125@163.com
 * @Date 2014-12-10 下午4:32:24
 * @Version V1.0
 */
class CommonController extends Weiou_Controller_Web {

    //暂时还没有用

    public function getPostsByUserIDForMapAction() {
        $userInfo = $this->getData('userinfo');
        $userID = $this->getParam('userID');
        $year = $this->getParam('year');
        $json = array();
        $jsonData = array();
        $jsonData['latestList'] = array();
        $jsonData['delList'] = array();
        $jsonData['modifyList'] = array();
        $post = new Weiou_Model_Post();
        $post_service = new Weiou_Service_Post();
        if ($userID) {
            if ($userInfo && $userID == $userInfo['user_id']) {
                $cursor = $post->getLocationPostsByUserID('c_user_post', $userID, $year, true);
            } else {
                $cursor = $post->getLocationPostsByUserID('c_user_post', $userID, $year);
            }
            $data = $post_service->_mapViewDataModel($cursor);
            $jsonData['latestList'] = $data['list'];
        }
        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

    /**
     * 获取个人帖子
     */
    public function getPostsByUserIDForGridViewAction() {
        $userId = trim($this->getParam('userID'));
        $type = $this->getParam('type') ? $this->getParam('type') : 1;
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 90;
        $pageType = trim($this->getParam('pageType')) ? trim($this->getParam('pageType')) : 'pre';
        $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
        $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
        $json = array();
        $jsonData = array();
        if ($userId) {
            $post = new Weiou_Model_Post();
            if ($pageType == 'pre') {
                if ($postRange) {
                    $postIDArr = explode(',', $postRange);
                    if ($postIDArr[0]) {
                        $pageType = 'pre';
                        $prePostDetail = $post->getPostDetail($postIDArr[0]);
                        if ($postIDArr[1]) {
                            $timestamp = $prePostDetail['post_show_time'];
                            $modifyPostDetail = $post->getPostDetail($postIDArr[1]);
                            $modifyTimestamp = $modifyPostDetail['post_show_time'];
                        } else {
                            $timestamp = $prePostDetail['post_show_time'];
                            $modifyTimestamp = NULL;
                        }
                    } else {
                        $pageType = 'refresh';
                        $timestamp = NULL;
                    }
                } else {
                    $pageType = 'refresh';
                    $timestamp = NULL;
                }
            } else {
                if ($postID) {
                    $pageType = 'more';
                    $morePostDetail = $post->getPostDetail($postID);
                    $timestamp = $morePostDetail['post_show_time'];
                } else {
                    $pageType = 'refresh';
                    $timestamp = NULL;
                }
            }
            if ($type == 1) {
                $cursor = $post->getPostlistByUserid($userId, $timestamp, (int) $pageSize, $pageType);
            } else if ($type == 2) {
                $cursor = $post->getBeshowPostListByUserID('c_user_post', $userId, 2, $timestamp, $pageType, (int) $pageSize);
            } else if ($type == 3) {
                $cursor = $post->getBeshowPostListByUserID('c_user_post', $userId, 3, $timestamp, $pageType, (int) $pageSize);
            } else {
                $jsonData = array();
                $json['state'] = 12;
                $json['data'] = $jsonData;
            }
            $postService = new Weiou_Service_Post();
            if ($pageType == 'refresh') {

                $refreshData = $postService->_gridViewDataModel($cursor);
                $jsonData['latestList'] = $refreshData['list'];
                $jsonData['modifyList'] = array();
                $jsonData['delList'] = array();
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else if ($pageType == 'pre') {

                $preData = $postService->_gridViewDataModel($cursor);
                $jsonData['latestList'] = $preData['list'];

                if ($modifyTimestamp && $latestTimestamp) {
                    $latestTimestamp = new MongoTimestamp($latestTimestamp, 0);
                    if ($type == 1) {
                        $modifyCursor = $post->getModifyPostsByUserID($userId, $timestamp, $latestTimestamp, (int) $pageSize);
                    } else {
                        $modifyCursor = $post->getBeshowModifyPostsByUserID($userId, (int) $type, $timestamp, $modifyTimestamp, $latestTimestamp);
                    }

                    $modifyData = $postService->_gridViewDataModel($modifyCursor);
                    $jsonData['modifyList'] = $modifyData['list'];
                    $jsonData['delList'] = $modifyData['delList'];
                } else {
                    $jsonData['modifyList'] = array();
                    $jsonData['delList'] = array();
                }
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else if ($pageType == 'more') {

                $moreData = $postService->_gridViewDataModel($cursor);
                $jsonData['list'] = $moreData['list'];
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else {
                $jsonData['list'] = array();
                $json['state'] = 0;
                $json['data'] = $jsonData;
            }
        } else {
            if ($pageType == "more") {
                $jsonData['list'] = array();
            } else {
                $jsonData['latestList'] = array();
                $jsonData['modifyList'] = array();
                $jsonData['delList'] = array();
            }
            $json['state'] = 19;
            $json['data'] = $jsonData;
        }
        echo json_encode($json);
    }

    public function getPostsByUserIDForListViewAction() {
        $userId = trim($this->getParam('userID'));
        $type = $this->getParam('type') ? $this->getParam('type') : 1;
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 90;
        $pageType = trim($this->getParam('pageType')) ? trim($this->getParam('pageType')) : 'pre';
        $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
        $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
        $commentsSize = trim($this->getParam('commnetsSize')) ? trim($this->getParam('commnetsSize')) : 4;
        $likesSize = trim($this->getParam('likesSize')) ? trim($this->getParam('likesSize')) : 9;
        $imgWidth = trim($this->getParam('width')) ? trim($this->getParam('width')) : 640;
        $imgHeight = trim($this->getParam('height')) ? trim($this->getParam('height')) : 640;

        $postListForm = new Weiou_Form_Ios_PostList();
        $postService = new Weiou_Service_Post();
        $json = array();
        $json['state'] = 0;
        $jsonData = array();
        if ($userId) {
            $post = new Weiou_Model_Post();
            if ($pageType == 'pre') {
                if ($postRange) {
                    $postIDArr = explode(',', $postRange);
                    if ($postIDArr[0]) {
                        $pageType = 'pre';
                        $prePostDetail = $post->getPostDetail($postIDArr[0]);
                        if ($postIDArr[1]) {
                            $timestamp = $prePostDetail['post_show_time'];
                            $modifyPostDetail = $post->getPostDetail($postIDArr[1]);
                            $modifyTimestamp = $modifyPostDetail['post_show_time'];
                        } else {
                            $timestamp = $prePostDetail['post_show_time'];
                            $modifyTimestamp = NULL;
                        }
                    } else {
                        $pageType = 'refresh';
                        $timestamp = NULL;
                    }
                } else {
                    $pageType = 'refresh';
                    $timestamp = NULL;
                }
            } else {
                if ($postID) {
                    $pageType = 'more';
                    $morePostDetail = $post->getPostDetail($postID);
                    $timestamp = $morePostDetail['post_show_time'];
                } else {
                    $pageType = 'refresh';
                    $timestamp = NULL;
                }
            }
            if ($type == 1) {
                $cursor = $post->getPostlistByUserid($userId, $timestamp, (int) $pageSize, $pageType);
            } else if ($type == 2) {
                $cursor = $post->getBeshowPostListByUserID('c_user_post', $userId, 2, $timestamp, $pageType, (int) $pageSize);
            } else if ($type == 3) {
                $cursor = $post->getBeshowPostListByUserID('c_user_post', $userId, 3, $timestamp, $pageType, (int) $pageSize);
            } else {
                $jsonData = array();
                $json['state'] = 12;
                $json['data'] = $jsonData;
            }
            if ($pageType == 'refresh') {

                $refreshData = $postService->_listViewDataModel($cursor, $postListForm);
                $jsonData['latestList'] = $refreshData['list'];
                $jsonData['modifyList'] = array();
                $jsonData['delList'] = array();
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else if ($pageType == 'pre') {

                $preData = $postService->_listViewDataModel($cursor, $postListForm);
                $jsonData['latestList'] = $preData['list'];

                if ($modifyTimestamp && $latestTimestamp) {
                    $latestTimestamp = new MongoTimestamp($latestTimestamp, 0);
                    if ($type == 1) {
                        $modifyCursor = $post->getModifyPostsByUserID($userId, $timestamp, $latestTimestamp, (int) $pageSize);
                    } else {
                        $modifyCursor = $post->getBeshowModifyPostsByUserID($userId, (int) $type, $timestamp, $modifyTimestamp, $latestTimestamp);
                    }

                    $modifyData = $postService->_listViewDataModel($modifyCursor, $postListForm);
                    $jsonData['modifyList'] = $modifyData['list'];
                    $jsonData['delList'] = $modifyData['delList'];
                } else {
                    $jsonData['modifyList'] = array();
                    $jsonData['delList'] = array();
                }
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else if ($pageType == 'more') {

                $moreData = $postService->_listViewDataModel($cursor, $postListForm);
                $jsonData['list'] = $moreData['list'];
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else {
                $jsonData['list'] = array();
                $json['state'] = 0;
                $json['data'] = $jsonData;
            }
        } else {
            $jsonData['list'] = array();
            $json['state'] = 0;
            $json['data'] = $jsonData;
        }
        echo json_encode($json);
    }

    /**
     * 获取个人资料
     */
    public function getUserinfoByUserIDAction() {
        $myinfo = $this->getData('userinfo');
        $userId = trim($this->getParam('uid'));
        if ($userId) {
            $user = new Weiou_Model_User();

            $userInfo = $user->getUserinfoByID($userId);
            if ($userInfo) {
                $post = new Weiou_Model_Post();
                $picUtil = new Weiou_Util_Pic();
                $jsonData = array();
                $jsonData['userId'] = $userInfo['user_id'];
                $jsonData['userName'] = $userInfo['user_nickname'];
                if ($userInfo['user_logo']) {
                    $jsonData['userLogo'] = $picUtil->getUserLogo($userInfo['user_logo'], 200, 200);
                    $jsonData['userLogoOriginal'] = $picUtil->createDownloadUrl($userInfo['user_logo']);
                } else {
                    $jsonData['userLogo'] = "";
                    $jsonData['userLogoOriginal'] = "";
                }
                $jsonData['fans'] = $userInfo['user_fans'];
                $jsonData['followers'] = $userInfo['user_concerned'];
                if ($userInfo['user_permanent_address']) {
                    $addressArr = explode(" ", $userInfo['user_permanent_address']);
                    if (count($addressArr) >= 2 && $addressArr[0] == $addressArr[1]) {
                        $jsonData['address'] = $addressArr[0];
                        if (isset($addressArr[2])) {
                            $jsonData['address'] .= " " . $addressArr[2];
                        }
                    } else {
                        $jsonData['address'] = $userInfo['user_permanent_address'];
                    }
                } else {
                    $jsonData['address'] = "";
                }
                $jsonData['userSign'] = $userInfo['user_signature'] ? $userInfo['user_signature'] : "";
                $jsonData['posts'] = $post->getPostCountByUserID($userInfo['user_id']);
                $jsonData['userLikes'] = $user->getLikesNumByUserID('c_user_post', $userInfo['user_id']);
                $jsonData['isFollowed'] = 0;
                $jsonData['userType'] = $userInfo['type'];

                if ($myinfo) {
                    $followerList = $user->getConcernedByID('c_user_concerned', new MongoId($myinfo['user_concerned_list']));
                    $followerFollowingList = $user->getConcernedByUserid('c_user_concerned', $userId);
                    if (!empty($followerFollowingList ['concerned_list'])) {
                        $followerFollowingArr = array_column($followerFollowingList['concerned_list'], 'concerned_id');
                        if (Weiou_Utils::inArray($myinfo['user_id'], $followerFollowingArr)) {
                            $isFollowedYou = 1;
                        } else {
                            $isFollowedYou = 0;
                        }
                    } else {
                        $isFollowedYou = 0;
                    }
                    if (!empty($followerList['concerned_list'])) {
                        $followerArr = array_column($followerList['concerned_list'], 'concerned_id');
                        $isFollowed = Weiou_Utils::inArray($userId, $followerArr);
                        if ($isFollowed) {
                            if ($isFollowedYou) {
                                $jsonData['isFollowed'] = 2;
                            } else {
                                $jsonData['isFollowed'] = 1;
                            }
                        } else {
                            if ($isFollowedYou) {
                                $jsonData['isFollowed'] = 3;
                            } else {
                                $jsonData['isFollowed'] = 0;
                            }
                        }
                    } else {
                        if ($isFollowedYou) {
                            $jsonData['isFollowed'] = 3;
                        } else {
                            $jsonData['isFollowed'] = 0;
                        }
                    }
                }
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else {
                $json['state'] = 13;
                $json['data'] = (object) NULL;
            }
        } else {
            $json['state'] = 14;
            $json['data'] = (object) NULL;
        }
        $this->logger->info("state:" . $json['state']);
        echo json_encode($json);
    }

    /**
     * 获取个人关注
     */
    public function getFollowersByUserIDAction() {
        $userID = $this->getParam('uid') ? $this->getParam('uid') : '';
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = $this->getParam('pageSize') ? $this->getParam('pageSize') : 50;
        $sec = $this->getParam('sec') ? $this->getParam('sec') : 0;
        $inc = $this->getParam('inc') ? $this->getParam('inc') : 0;

        $timestamp = NULL;
        if ($sec) {
            $timestamp = new MongoTimestamp($sec, $inc);
        }
        if ($timestamp == NULL) {
            $pageType = 'refresh';
        }

        $result = new Weiou_JsonModel_ResultForIos();
        $userService = new Weiou_Service_User();
        $list = $userService->getFollowersByUserID($userID, $timestamp, $pageType, $pageSize);

        $result->state = 0;
        $result->data = array(
            "list" => $list
        );

        echo $result->toJson();
    }

    /**
     * 获取个人粉丝
     */
    public function getFansByUserIDAction() {
        $userID = $this->getParam('uid') ? $this->getParam('uid') : '';
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = $this->getParam('pageSize') ? $this->getParam('pageSize') : 50;
        $sec = $this->getParam('sec') ? $this->getParam('sec') : 0;
        $inc = $this->getParam('inc') ? $this->getParam('inc') : 0;

        $timestamp = NULL;
        if ($sec) {
            $timestamp = new MongoTimestamp($sec, $inc);
        }
        if ($timestamp == NULL) {
            $pageType = 'refresh';
        }

        $result = new Weiou_JsonModel_ResultForIos();
        $userService = new Weiou_Service_User();
        $list = $userService->getFansByUserID($userID, $timestamp, $pageType, $pageSize);

        $result->state = 0;
        $result->data = array(
            "list" => $list
        );

        echo $result->toJson();
    }

    /**
     * 添加关注
     */
    public function addFollowerAction() {
        $followingUserId = trim($this->getParam('uid'));
        $result = new Weiou_JsonModel_ResultForIos();
        $result->state = 1;

        if ($followingUserId) {
            $userService = new Weiou_Service_User();
            $state = $userService->addFollower($followingUserId);

            if ($state == 0) {
                $result->state = 0;
            } else if ($state == 105) {
                $result->state = 105;
            }
        }
        echo $result->toJson();
    }

    /**
     * 取消关注
     */
    public function cancelFollowerAction() {
        $userInfo = $this->getData('userinfo');
        $followerId = $this->getParam('uid');
        $json = array();
        $json['data'] = (object) NULL;
        if ($followerId) {
            $user = new Weiou_Model_User();
            $re = $user->cancelConernedByID('c_user_concerned', new MongoId($userInfo['user_concerned_list']), $followerId, $userInfo['user_id']);
            if ($re) {
                $json['state'] = 0;
            } else {
                $json['state'] = 11;
            }
        } else {
            $json['state'] = 10;
        }
        echo json_encode($json);
    }

    /**
     * 取消关注的地方
     */
    public function cancelFollowedPlaceAction() {
        $userInfo = $this->getData('userinfo');
        $placeID = trim($this->getParam('placeID'));
        $user = new Weiou_Model_User();
        $json = array();
        if ($placeID) {
            $re = $user->cancelConcernedAreaByID('c_concerned_areas', $userInfo['user_concerned_areas'], $placeID, $userInfo["user_id"]);
            if ($re) {
                $json['state'] = 0;
                $json['data'] = (object) NULL;
            } else {
                $json['state'] = 25;
                $json['data'] = (object) NULL;
            }
        } else {
            $json['state'] = 25;
            $json['data'] = (object) NULL;
        }
        echo json_encode($json);
    }

    /**
     * 从通讯录邀请好友
     */
    public function invitedFromAddressbookAction() {
        $myinfo = $this->getData('userinfo');
        $nameStr = $this->getParam('nameList') ? $this->getParam('nameList') : '';
        $phoneStr = $this->getParam('phoneList') ? $this->getParam('phoneList') : '';
        $json = array();
        $jsonData = array();
        if ($nameStr && $phoneStr) {
            $nameArr = explode(',', $nameStr);
            $phoneArr = explode(',', $phoneStr);
            $user = new Weiou_Model_User();
            $i = 0;
            foreach ($phoneArr as $k => $v) {
                $isSelf = FALSE;
                $search_arr = array('-', ' ');
                $replace_arr = array('', '');
                $phoneNumber = str_replace($search_arr, $replace_arr, $v);
                if ($phoneNumber) {
                    if (substr($phoneNumber, 0, 1) == '+') {
                        if ($myinfo['user_full_phone'] != $phoneNumber) {
                            $userInfo = $user->getUserinfoByFullphone($phoneNumber);
                        } else {
                            $userInfo = array();
                            $isSelf = TRUE;
                        }
                    } else {
                        if ($myinfo['user_phone'] != $phoneNumber) {
                            $userInfo = $user->getUserinfoByPhone($phoneNumber);
                        } else {
                            $userInfo = array();
                            $isSelf = TRUE;
                        }
                    }
                    if (!$userInfo && !$isSelf) {
                        $jsonData['list'][$i]['userName'] = $nameArr[$k];
                        $jsonData['list'][$i]['phoneNum'] = $phoneArr[$k];
                        $i += 1;
                    }
                }
            }
            $json['state'] = 0;
            $json['data'] = $jsonData;
        } else {
            $json['state'] = 6;
            $jsonData['list'] = array();
            $json['data'] = $jsonData;
        }
        echo json_encode($json);
    }

    /**
     * 从通讯录添加好友
     */
    public function addFollowerFromAddressbookAction() {
        $myinfo = $this->getData('userinfo');
        $nameStr = $this->getParam('nameList') ? $this->getParam('nameList') : '';
        $phoneStr = $this->getParam('phoneList') ? $this->getParam('phoneList') : '';

        $result = new Weiou_JsonModel_ResultForIos();
        $address = array(
            'userList' => array(),
            'noRegistList' => array()
        );

        if ($nameStr && $phoneStr) {
            $userService = new Weiou_Service_User();
            $address = $userService->checkAddressbook($myinfo, $nameStr, $phoneStr, $phoneStr, "ios");
        }
        $result->state = 0;
        $result->data = array(
            'list' => $address['userList']
        );

        echo $result->toJson();
    }

    /**
     * 从通讯录获取好友
     * isConcerned:0-未关注；1-已经关注；2-相互关注； 3-未在使用
     */
    public function getIsFollowedFromAddressbookAction() {
        $myinfo = $this->getData('userinfo');
        $nameStr = $this->getParam('namelist') ? $this->getParam('namelist') : '';
        $phoneStr = $this->getParam('phonelist') ? $this->getParam('phonelist') : '';

        $result = new Weiou_JsonModel_ResultForIos();
        $address = array(
            'userList' => array(),
            'noRegistList' => array()
        );

        if ($nameStr && $phoneStr) {
            $userService = new Weiou_Service_User();
            $address = $userService->checkAddressbook($myinfo, $nameStr, $phoneStr, $phoneStr, "ios");
        }
        $result->state = 0;
        $result->data = array(
            'list' => $address['noRegistList']
        );

        echo $result->toJson();
    }

    /**
     * 添加关注地方
     */
    public function addFollowPlaceAction() {
        $lat = $this->getParam('lat');
        $lng = $this->getParam('lng');
        $address = $this->getParam('address');
        $radius = $this->getParam('radius') ? $this->getParam('radius') : 2000;

        $result = new Weiou_JsonModel_ResultForIos();
        $result->state = 24;
        $userService = new Weiou_Service_User();
        if ($lat && $lng) {
            $followingPlaceID = $userService->addFollowPlace($lat, $lng, $address, $radius);

            if ($followingPlaceID) {
                $jsonData = array(
                    'placeId' => $followingPlaceID
                );
                $result->state = 0;
                $result->data = $jsonData;
            }
        }

        echo $result->toJson();
    }

    //暂时没有用
    private function replaceWords($str) {
        $silence = new silence();
        $list = $silence->getSilenceList();
        $silenceWords = array();
        $relaceWords = array();
        foreach ($list as $k => $v) {
            $silenceWords[$k] = $v['silence_words'];
            $relaceWords[$k] = $v['replace_words'];
        }
        return str_replace($silenceWords, $relaceWords, $str);
    }

}
