<?php

/**
 * @desc
 * @author jiangyuchao
 * @email jiangyc0125@163.com
 * @date 4:28:08 PM May 19, 2015
 * @version V1.0
 */
class GameController extends Weiou_Controller_Web {

    //暂时还没有用

    public function myInvitedPreciousAction() {
        $myinfo = $this->getData('userinfo');
        $ic = new Weiou_Model_InvitedCode();
        $user = new Weiou_Model_User();
        $post = new Weiou_Model_Post();
        $json = array();
        $jsonData = array();
        $code = $ic->getInvitedCodeByUserID($myinfo['user_id']);
        $childList = $ic->getCodeByParentID($code['code_id']);
        if (empty($childList)) {
            $jsonData['list'] = array();
            $json['state'] = 0;
            $json['data'] = $jsonData;
        } else {
            foreach ($childList as $k => $v) {
                $childInfo = $user->getUserinfoByID($v['user_id']);
                $jsonData['list'][$k]['userId'] = $v['user_id'];
                $jsonData['list'][$k]['userName'] = $childInfo['user_nickname'];
                if ($childInfo['user_signature']) {
                    $jsonData['list'][$k]['userSign'] = $childInfo['user_signature'];
                } else {
                    $jsonData['list'][$k]['userSign'] = '';
                }
                if ($childInfo['user_logo']) {
                    $picUtil = new Weiou_Util_Pic();
                    $jsonData['list'][$k]['userLogo'] = $picUtil->getUserLogo($childInfo['user_logo']);
                } else {
                    $jsonData['list'][$k]['userLogo'] = "";
                }
                $row = $ic->getChildnodesByPid($v['code_id']);
                $childReferralCodeStr = $row["getChildList('" . $v['code_id'] . "')"];

                $childReferralCodeArr = explode(',', $childReferralCodeStr);
                $childCount = count($childReferralCodeArr);
                $timeDiff = 0;
                $jsonData['list'][$k]['num'] = 0;
                $jsonData['list'][$k]['bonus'] = 0;
                if ($childCount >= 2) {
                    $userIdArr = array();
                    for ($i = 1; $i < $childCount; $i++) {
                        $childReferralCodeInfo = $ic->getInvitedCodeByID($childReferralCodeArr[$i]);
                        $userIdArr[] = $childReferralCodeInfo['user_id'];
                    }
                    $cursor = $post->getHidePostsByUseridArr('c_user_post', $userIdArr);
                    $preciousNum = 0;
                    foreach ($cursor as $doc) {
                        $timeDiff += Weiou_Util_time::timeDiffForHour(time(), $doc['post_create_time']->sec);
                        $preciousNum ++;
                    }
                    $jsonData['list'][$k]['bonus'] = $timeDiff;
                    $jsonData['list'][$k]['num'] = $preciousNum;
                }

                $this->logger->info($childInfo['user_nickname']);
                $this->logger->info($v['code_id']);
                $this->logger->info($childReferralCodeStr);

                $this->logger->info($jsonData['list'][$k]['num']);
                $this->logger->info($jsonData['list'][$k]['bonus']);
            }
            $json['state'] = 0;
            $json['data'] = $jsonData;
        }
        echo json_encode($json);
    }

    public function myPreciousAction() {
        $myinfo = $this->getData('userinfo');
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = $this->getParam('pageSize') ? $this->getParam('pageSize') : 30;

        $this->logger->info($this->params);

        $post = new Weiou_Model_Post();
        $json = array();
        $jsonData = array();
        if ($pageType == 'more') {
            $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
            if ($postID) {
                $postDetail = $post->getPostDetail($postID);
                if ($postDetail) {
                    $moreCursor = $post->getHidePostByUserid('c_user_post', $myinfo['user_id'], $postDetail['post_create_time'], $pageSize, $pageType);

                    $moreList = $this->_preciousDataModel($moreCursor, $myinfo, $post);
                    $jsonData['list'] = $moreList['list'];
                    $json['state'] = 0;
                    $json['data'] = $jsonData;
                } else {
                    $jsonData['list'] = array();
                    $json['state'] = 18;
                    $json['data'] = $jsonData;
                }
            } else {
                $jsonData['list'] = array();
                $json['state'] = 18;
                $json['data'] = $jsonData;
            }
        } else {
            $postRange = $this->getParam('postRange') ? $this->getParam('postRange') : '';
            //$latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
            if ($postRange) {
                $pidArr = explode(',', $postRange);
                if ($pidArr[0] != NULL) {
                    $prePostDetail = $post->getPostDetail($pidArr[0]);
                    $preTimestamp = $prePostDetail['post_create_time'];
                } else {
                    $preTimestamp = NULL;
                }
                if ($pidArr[1] != NULL) {
                    $modifyPostDetail = $post->getPostDetail($pidArr[1]);
                    $modifyTimestamp = $modifyPostDetail['post_create_time'];
                } else {
                    $modifyTimestamp = NULL;
                }
                if ($preTimestamp == NULL && $modifyTimestamp == NULL) {
                    $pageType = 'refresh';
                }
            } else {
                $pageType = 'refresh';
                $preTimestamp = NULL;
                $modifyTimestamp = NULL;
            }

            $preCursor = $post->getHidePostByUserid('c_user_post', $myinfo['user_id'], $preTimestamp, $pageSize, $pageType);
            $preList = $this->_preciousDataModel($preCursor, $myinfo, $post);
            $jsonData['latestList'] = $preList['list'];
            $jsonData['modifyList'] = array();
            $jsonData['delList'] = array();

            $json['state'] = 0;
            $json['data'] = $jsonData;
        }
        echo json_encode($json);
    }

    //我的撞宝
    public function myHitPreciousAction() {
        $myinfo = $this->getData('userinfo');
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = $this->getParam('pageSize') ? $this->getParam('pageSize') : 45;
        $post = new Weiou_Model_Post();
        $json = array();
        $jsonData = array();
        if ($pageType == 'more') {
            $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
            if ($postID) {
                $postDetail = $post->getPostDetail($postID);
                $moreTimestamp = $postDetail['post_show_time'];
                $moreCursor = $post->getBeshowPostListByUserID('c_user_post', $myinfo['user_id'], 3, $moreTimestamp, 'more', $pageSize);
                $moreList = $this->_preciousDataModel($moreCursor, $myinfo, $post, 2);
                $jsonData['list'] = $moreList['list'];
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else {
                $json['state'] = 17;
                $jsonData['list'] = array();
                $json['data'] = $jsonData;
            }
        } else {
            $postRange = $this->getParam('postRange') ? $this->getParam('postRange') : '';
            $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
            if ($postRange) {
                $pidArr = explode(',', $postRange);
                if ($pidArr[0] != NULL) {
                    $prePostDetail = $post->getPostDetail($pidArr[0]);
                    $preTimestamp = $prePostDetail['post_show_time'];
                } else {
                    $preTimestamp = NULL;
                }
                if ($pidArr[1] != NULL) {
                    $modifyPostDetail = $post->getPostDetail($pidArr[1]);
                    $modifyTimestamp = $modifyPostDetail['post_show_time'];
                } else {
                    $modifyTimestamp = NULL;
                }
                if ($preTimestamp == NULL && $modifyTimestamp == NULL) {
                    $pageType = 'refresh';
                }
            } else {
                $pageType = 'refresh';
                $preTimestamp = NULL;
                $modifyTimestamp = NULL;
            }
            $preCursor = $post->getBeshowPostListByUserID('c_user_post', $myinfo['user_id'], 3, $preTimestamp, $pageType, $pageSize);
            $preList = $this->_preciousDataModel($preCursor, $myinfo, $post, 2);
            $jsonData['latestList'] = $preList['list'];
            if ($modifyTimestamp && $latestTimestamp) {
                $latestTimestamp = new MongoTimestamp($latestTimestamp, 0);
                $modifyCursor = $post->getBeshowModifyPostsByUserID($myinfo['user_id'], 3, $preTimestamp, $modifyTimestamp, $latestTimestamp);
                $modifyList = $this->_preciousDataModel($modifyCursor, $myinfo, $post, 2);
                $jsonData['modifyList'] = $modifyList['list'];
                $jsonData['delList'] = $modifyList['delList'];
            } else {
                $jsonData['modifyList'] = array();
                $jsonData['delList'] = array();
            }
            $json['state'] = 0;
            $json['data'] = $jsonData;
        }
        echo json_encode($json);
    }

    //我的炫宝
    public function myShowPreciousAction() {
        $myinfo = $this->getData('userinfo');
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = $this->getParam('pageSize') ? $this->getParam('pageSize') : 45;
        $post = new Weiou_Model_Post();
        $history = new history();
        $showtime = $history->getShowtimeOrderByDesc();
        $beginTimestamp = strtotime($showtime['show_begin_time']);
        $endTimestamp = strtotime($showtime['show_end_time']);
        $json = array();
        $jsonData = array();
        if ($pageType == 'more') {
            $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
            if ($postID) {
                $postDetail = $post->getPostDetail($postID);
                $moreTimestamp = $postDetail['post_show_time'];
                $moreCursor = $post->getBeshowPostListByUserID('c_user_post', $myinfo['user_id'], 2, $moreTimestamp, 'more', $pageSize);
                $moreList = $this->_showViewDataModel($moreCursor, $post, $beginTimestamp, $endTimestamp);
                $jsonData['list'] = $moreList['list'];
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else {
                $json['state'] = 17;
                $jsonData['list'] = array();
                $json['data'] = $jsonData;
            }
        } else {
            $postRange = $this->getParam('postRange') ? $this->getParam('postRange') : '';
            $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
            if ($postRange) {
                $pidArr = explode(',', $postRange);
                if ($pidArr[0] != NULL) {
                    $prePostDetail = $post->getPostDetail($pidArr[0]);
                    $preTimestamp = $prePostDetail['post_show_time'];
                } else {
                    $preTimestamp = NULL;
                }
                if ($pidArr[1] != NULL) {
                    $modifyPostDetail = $post->getPostDetail($pidArr[1]);
                    $modifyTimestamp = $modifyPostDetail['post_show_time'];
                } else {
                    $modifyTimestamp = NULL;
                }
                if ($preTimestamp == NULL && $modifyTimestamp == NULL) {
                    $pageType = 'refresh';
                }
            } else {
                $pageType = 'refresh';
                $preTimestamp = NULL;
                $modifyTimestamp = NULL;
            }
            $preCursor = $post->getBeshowPostListByUserID('c_user_post', $myinfo['user_id'], 2, $preTimestamp, $pageType, $pageSize);

            $preList = $this->_showViewDataModel($preCursor, $post, $beginTimestamp, $endTimestamp);
            $jsonData['latestList'] = $preList['list'];
            if ($modifyTimestamp && $latestTimestamp) {
                $modifyCursor = $post->getBeshowModifyPostsByUserID($myinfo['user_id'], 2, $preTimestamp, $modifyTimestamp, $latestTimestamp);

                $modifyList = $this->_showViewDataModel($modifyCursor, $post, $beginTimestamp, $endTimestamp);
                $jsonData['modifyList'] = $modifyList['list'];
                $jsonData['delList'] = $modifyList['delList'];
            } else {
                $jsonData['modifyList'] = array();
                $jsonData['delList'] = array();
            }
            $json['state'] = 0;
            $json['data'] = $jsonData;
        }
        echo json_encode($json);
    }

    public function myBombAction() {
        $myinfo = $this->getData('userinfo');
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = $this->getParam('pageSize') ? $this->getParam('pageSize') : 30;
        $json = array();
        $jsonData = array();
        $post = new Weiou_Model_Post();
        if ($pageType == 'more') {
            $postID = $this->getParam('postID') ? $this->getParam('postID') : '';
            if ($postID) {
                $postDetail = $post->getPostDetail($postID);
                $moreCursor = $post->getBonb('c_user_post', $myinfo['user_id'], $postDetail['post_show_time'], 'more', (int) $pageSize);

                $moreList = $this->_bonbDataModel($moreCursor);
                $jsonData['list'] = $moreList['list'];
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else {
                $json['state'] = 18;
                $jsonData['list'] = array();
                $json['data'] = $jsonData;
            }
        } else {
            $postRange = $this->getParam('postRange') ? $this->getParam('postRange') : '';
            $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
            if ($postRange) {
                $pidArr = explode(',', $postRange);
                if ($pidArr[0] != NULL) {
                    $prePostDetail = $post->getPostDetail($pidArr[0]);
                    $preTimestamp = $prePostDetail['post_show_time'];
                } else {
                    $preTimestamp = NULL;
                }
                if ($pidArr[1] != NULL) {
                    $modifyPostDetail = $post->getPostDetail($pidArr[1]);
                    $modifyTimestamp = $modifyPostDetail['post_show_time'];
                } else {
                    $modifyTimestamp = NULL;
                }
                if ($preTimestamp == NULL && $modifyTimestamp == NULL) {
                    $pageType = 'refresh';
                }
            } else {
                $pageType = 'refresh';
                $preTimestamp = NULL;
                $modifyTimestamp = NULL;
            }
            $preCursor = $post->getBonb('c_user_post', $myinfo['user_id'], $preTimestamp, $pageType, (int) $pageSize);
            $preList = $this->_bonbDataModel($preCursor);
            $jsonData['latestList'] = $preList['list'];
            if ($modifyTimestamp && $latestTimestamp) {
                $latestTimestamp = new MongoTimestamp($latestTimestamp, 0);
                $modifyCursor = $post->getModifyBonb('c_user_post', $myinfo['user_id'], $preTimestamp, $modifyTimestamp, $latestTimestamp);
                $modifyList = $this->_bonbDataModel($modifyCursor);
                $jsonData['modifyList'] = $modifyList['list'];
                $jsonData['delList'] = $modifyList['delList'];
            } else {
                $jsonData['modifyList'] = array();
                $jsonData['delList'] = array();
            }
            $json['state'] = 0;
            $json['data'] = $jsonData;
        }
        echo json_encode($json);
    }

    public function getAwardsForGirdViewAction() {
        $pageType = trim($this->getParam('pageType')) ? trim($this->getParam('pageType')) : 'pre';
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 10;
        $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
        $timestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
        $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
        //$commentsSize = trim($this->getParam('commentsSize')) ? trim($this->getParam('commentsSize')) : 30;
        $history = new history();
        $json = array();
        $jsonData = array();
        $awardsList = array();
        $modifyAwardsList = array();
        $post = new Weiou_Model_Post();
        $postService = new Weiou_Service_Post();
        if ($pageType == 'pre') {
            if ($postRange) {
                $postIDArr = explode(',', $postRange);
                if ($postIDArr[0] != NULL) {
                    $pageType = 'pre';
                    $preAwardsInfo = $history->getHistoryAwardsByPostID($postIDArr[0]);
                    $awardsList = $history->getHistoryAwardsForPage($preAwardsInfo['awards_id'], $pageType, $pageSize);
                } else {
                    $pageType = 'refresh';
                    $awardsList = $history->getHistoryAwardsForPage(0, $pageType, $pageSize);
                }
            } else {
                $pageType = 'refresh';
                $awardsList = $history->getHistoryAwardsForPage(0, $pageType, $pageSize);
            }
        } else {
            //echo "111";
            if ($postID) {
                //echo '222';
                $pageType = 'more';
                $moreAwardsInfo = $history->getHistoryAwardsByPostID($postID);
                $awardsList = $history->getHistoryAwardsForPage($moreAwardsInfo['awards_id'], $pageType, $pageSize);
            } else {
                $pageType = 'refresh';
                $awardsList = $history->getHistoryAwardsForPage(0, $pageType, $pageSize);
            }
        }
        //var_dump($awardsList);
        if ($pageType == 'refresh' || $pageType == 'pre') {
            //echo aaa;
            if ($awardsList) {
                $idArr = array();
                foreach ($awardsList as $k => $v) {
                    $idArr[$k] = new MongoId($v['awards_post_id']);
                }
                $cursor = $post->getPostsByIDArr($idArr);
                $preData = $postService->_gridViewDataModel($cursor);
                $jsonData['latestList'] = $preData['list'];
                $jsonData['modifyList'] = array();
                $jsonData['delList'] = array();
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else {
                $jsonData['latestList'] = array();
                $jsonData['modifyList'] = array();
                $jsonData['delList'] = array();
                $json['state'] = 0;
                $json['data'] = $jsonData;
            }
        } else if ($pageType == 'more') {
            //echo '333';
            if ($awardsList) {
                $idArr = array();
                foreach ($awardsList as $k => $v) {
                    $idArr[$k] = new MongoId($v['awards_post_id']);
                }
                $cursor = $post->getPostsByIDArr($idArr);
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
            $json['state'] = 19;
            $json['data'] = array();
        }
        echo json_encode($json);
    }

    /*
     * 历届获奖
     */

    public function getAwardsForListViewAction() {
        $myinfo = $this->getData('userinfo');
        $pageType = trim($this->getParam('pageType')) ? trim($this->getParam('pageType')) : 'pre';
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 10;
        $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
        $timestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
        $commentsSize = trim($this->getParam('commentsSize')) ? trim($this->getParam('commentsSize')) : 4;
        $likesSize = trim($this->getParam('likesSize')) ? trim($this->getParam('likesSize')) : 9;
        $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
        $imgWidth = trim($this->getParam('width')) ? trim($this->getParam('width')) : 640;
        $imgHeight = trim($this->getParam('height')) ? trim($this->getParam('height')) : 640;

        $postListForm = new Weiou_Form_Ios_PostList();
        $history = new history();
        $json = array();
        $jsonData = array();
        $awardsList = array();
        $modifyAwardsList = array();
        $post = new Weiou_Model_Post();
        $postService = new Weiou_Service_Post();
        if ($pageType == 'pre') {
            if ($postRange) {
                $postIDArr = explode(',', $postRange);
                if ($postIDArr[0] != NULL) {
                    $pageType = 'pre';
                    $preAwardsInfo = $history->getHistoryAwardsByPostID($postIDArr[0]);
                    if ($postIDArr[1]) {
                        $modifyAwardsInfo = $history->getHistoryAwardsByPostID($postIDArr[1]);
                        $awardsList = $history->getHistoryAwardsForPage($preAwardsInfo['awards_id'], $pageType, $pageSize);
                        $modifyAwardsList = $history->getBtwHistoryAwards($modifyAwardsInfo['awards_id'], $preAwardsInfo['awards_id']);
                    } else {
                        $awardsList = $history->getHistoryAwardsForPage($preAwardsInfo['awards_id'], $pageType, $pageSize);
                    }
                } else {
                    $pageType = 'refresh';
                    $awardsList = $history->getHistoryAwardsForPage(0, $pageType, $pageSize);
                }
            } else {
                $pageType = 'refresh';
                $awardsList = $history->getHistoryAwardsForPage(0, $pageType, $pageSize);
            }
        } else {
            if ($postID) {
                $pageType = 'more';
                $moreAwardsInfo = $history->getHistoryAwardsByPostID($postID);
                $awardsList = $history->getHistoryAwardsForPage($moreAwardsInfo['awards_id'], $pageType, $pageSize);
            } else {
                $pageType = 'refresh';
                $awardsList = $history->getHistoryAwardsForPage(0, $pageType, $pageSize);
            }
        }
        //var_dump($awardsList);
        if ($pageType == 'refresh') {
            //echo aaa;
            if ($awardsList) {
                $idArr = array();
                foreach ($awardsList as $k => $v) {
                    $idArr[$k] = new MongoId($v['awards_post_id']);
                }
                $cursor = $post->getPostsByIDArr($idArr);
                $preData = $postService->_listViewDataModel($cursor, $postListForm);
                $jsonData['latestList'] = $preData['list'];
                $jsonData['modifyList'] = array();
                $jsonData['delList'] = array();
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else {
                $jsonData['latestList'] = array();
                $jsonData['modifyList'] = array();
                $jsonData['delList'] = array();
                $json['state'] = 0;
                $json['data'] = $jsonData;
            }
        } else if ($pageType == 'pre') {
            if ($awardsList) {
                $idArr = array();
                foreach ($awardsList as $k => $v) {
                    $idArr[$k] = new MongoId($v['awards_post_id']);
                }
                $preCursor = $post->getPostsByIDArr($idArr);
                $preData = $postService->_listViewDataModel($preCursor, $postListForm);
                $jsonData['latestList'] = $preData['list'];
            } else {
                $jsonData['latestList'] = array();
            }
            if ($modifyAwardsList && $timestamp) {
                $idArr = array();
                $modifyTimestamp = new MongoTimestamp($timestamp, 0);
                foreach ($modifyAwardsList as $k => $v) {
                    $idArr[$k] = new MongoId($v['awards_post_id']);
                }
                $modifyCursor = $post->getPostsByIDArr($idArr, $modifyTimestamp);
                $modifyData = $postService->_listViewDataModel($modifyCursor, $postListForm);
                $jsonData['modifyList'] = $modifyData['list'];
            } else {
                $jsonData['modifyList'] = array();
            }
            $jsonData['delList'] = array();
            $json['state'] = 0;
            $json['data'] = $jsonData;
        } else if ($pageType == 'more') {
            if ($awardsList) {
                $idArr = array();
                foreach ($awardsList as $k => $v) {
                    $idArr[$k] = new MongoId($v['awards_post_id']);
                }
                $cursor = $post->getPostsByIDArr($idArr);
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
            $json['state'] = 19;
            $json['data'] = array();
        }
        echo json_encode($json);
    }

    /**
     * 炫宝
     */
    public function showHideAction() {
        $userInfo = $this->getData('userinfo');
        $postID = trim($this->getParam('postID'));
        $postService = new Weiou_Service_Post();
        $json = $postService->showHide($postID, $userInfo["user_id"]);
        echo json_encode($json);
    }

    /**
     * 获取投票列表
     */
    public function getPostVoteForListViewAction() {
        $myinfo = $this->getData("userinfo");
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 10;
        $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
        $commentsSize = trim($this->getParam('commentsSize')) ? trim($this->getParam('commentsSize')) : 4;
        $likesSize = trim($this->getParam('likesSize')) ? trim($this->getParam('likesSize')) : 9;
        $imgWidth = trim($this->getParam('width')) ? trim($this->getParam('width')) : 640;
        $imgHeight = trim($this->getParam('height')) ? trim($this->getParam('height')) : 640;

        $postListForm = new Weiou_Form_Ios_PostList();
        $post = new Weiou_Model_Post();
        $user = new Weiou_Model_User();
        $history = new history();
        $showtime = $history->getShowtimeOrderByDesc();
        $beginTimestamp = strtotime($showtime['show_begin_time']);
        $endTimestamp = strtotime($showtime['show_end_time']);
        $beginMongotimestamp = new MongoTimestamp($beginTimestamp);
        $endMongotimestamp = new MongoTimestamp($endTimestamp);
        $postService = new Weiou_Service_Post();
        $json = array();
        $jsonData = array();
        if ($pageType == 'more') {
            $postDetail = $post->getPostDetail($postID);
            $moreTimestamp = $postDetail['post_show_time'];
            $moreCursor = $post->getShowPostByTimeForIOS('c_user_post', $beginMongotimestamp, $endMongotimestamp, $moreTimestamp, (int) $pageSize, $pageType);
            $moreData = $postService->_listViewDataModel($moreCursor, $postListForm);
            $jsonData['list'] = $moreData['list'];
        } else {
            $pageType = 'refresh';
            $preTimestamp = NULL;
            $preCursor = $post->getShowPostByTimeForIOS('c_user_post', $beginMongotimestamp, $endMongotimestamp, $preTimestamp, (int) $pageSize, $pageType);

            $preData = $postService->_listViewDataModel($preCursor, $postListForm);
            $jsonData['latestList'] = $preData['list'];

            $jsonData['modifyList'] = array();
            $jsonData['delList'] = array();
        }
        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

    public function getHashTagsAction() {
        $this->getGames();
    }

    /**
     * 炸弹数据模型
     * @param MongoCursor $cursor
     * @return multitype:multitype:
     */
    private function _bonbDataModel($cursor) {
        $i = 0;
        $j = 0;
        $post = new Weiou_Model_Post();
        $picUtil = new Weiou_Util_Pic();
        $data = array();
        $data['list'] = array();
        $data['delList'] = array();
        foreach ($cursor as $doc) {
            if ($doc['post_isDel'] == 0) {
                $data['list'][$i]['id'] = $doc['_id']->{'$id'};
                foreach ($doc['pic_list'] as $k => $v) {
                    $data['list'][$i]['thumbnail'] = $picUtil->getImgView($v['pic_key']);
                    break;
                }
                $hitPreciousCursor = $post->getExplodeHideByExplodepostid('c_user_post', $doc['_id']->{'$id'});
                $k = 0;
                $data['list'][$i]['hitPreciousList'] = array();
                foreach ($hitPreciousCursor as $doc1) {
                    $data['list'][$i]['hitPreciousList'][$k]['id'] = $doc1['_id']->{'$id'};
                    foreach ($doc1['pic_list'] as $k1 => $v1) {
                        $data['list'][$i]['hitPreciousList'][$k]['picKey'] = $v1['pic_key'];
                        $data['list'][$i]['hitPreciousList'][$k]['thumbnail'] = $picUtil->getImgView($v1['pic_key']);
                        break;
                    }
                    $k += 1;
                }

                $i += 1;
            } else {
                $data['delList'][$j]['id'] = $doc['_id']->{'$id'};
                $j += 1;
            }
        }
        return $data;
    }

    /**
     * 藏宝数据模型
     * @param MongoCursor $cursor
     * @param array $userInfo
     * @param post $post
     * @param int $type 1-藏宝 2-撞宝
     * @return Ambigous <multitype:multitype: , number>
     */
    private function _preciousDataModel($cursor, $userInfo, $post, $type = 1) {
        $data = array();
        $data['list'] = array();
        $data['delList'] = array();
        $picUtil = new Weiou_Util_Pic();
        $user = new Weiou_Model_User();
        foreach ($cursor as $doc) {
            if ($doc['post_isDel'] == 0) {
                $d = array();

                $d['id'] = $doc['_id']->{'$id'};
                $d['userId'] = $doc['user_id'];
                $userinfo = $user->getUserinfoByID($doc['user_id']);
                $d['userName'] = $userinfo['user_nickname'];
                if (isset($doc['Amap'])) {
                    $lat = Weiou_Utils::getLat($doc['Amap']);
                    $lng = Weiou_Utils::getLng($doc['Amap']);
                    if (isset($doc['formatted_address'])) {
                        $d['address'] = $doc['formatted_address'];
                    } else {
                        $d['address'] = $lat . ',' . $lng;
                    }
                    $d['lat'] = $lat;
                    $d['lng'] = $lng;
                } else {
                    $d['address'] = "";
                    $d['lat'] = 91.00;
                    $d['lng'] = 181.00;
                }
                foreach ($doc['pic_list'] as $k => $v) {
                    $d['thumbnail'] = $picUtil->getImgView($v['pic_key']);
                    break;
                }

                if ($doc['post_isHide'] == 1) {
                    $d['sec'] = $doc['post_create_time']->sec;
                    $d['inc'] = $doc['post_create_time']->inc;
                } else {
                    $d['sec'] = $doc['post_show_time']->sec;
                    $d['inc'] = $doc['post_show_time']->inc;
                }
                if (isset($doc['refreshTime']) && $doc['refreshTime'] != "") {
                    $d['sec'] = $doc['refreshTime']->sec;
                    $d['inc'] = $doc['refreshTime']->inc;
                }

                if ($type == 1) {
//                    $locLat = isset($doc['loc']['latitude'])?$doc['loc']['latitude']:$doc['loc']['coordinates'][1];
//                    $locLng = isset($doc['loc']['longitude'])?$doc['loc']['longitude']:$doc['loc']['coordinates'][0];
                    $locLat = Weiou_Utils::getLat($doc['Amap']);
                    $locLng = Weiou_Utils::getLng($doc['Amap']);
                    $nearCursor = $post->getAllPostsExceptMeOrderByDistance('c_user_post', $userInfo['user_id'], $locLat, $locLng);
                    foreach ($nearCursor as $doc1) {
                        $d['distance'] = $this->getDistance($locLng, $locLat, Weiou_Utils::getLng($doc1['Amap']), Weiou_Utils::getLat($doc1['Amap']));
                    }
                    $d['time'] = $doc['post_create_time']->sec;
                    $d['bonus'] = Weiou_Util_Time::timeDiffForHour(time(), $doc['post_create_time']->sec);
                    $data['list'][] = $d;
                } else {
                    if ($doc['exposure_post_id'] != $doc['_id']->{'$id'}) {
                        $hitPreciousCursor = $post->getExplodeHideByExplodepostid('c_user_post', $doc['exposure_post_id'], 0);
                        if ($hitPreciousCursor->count() > 0) {
                            $bombPostDetail = $post->getPostDetailByID($doc['exposure_post_id']);
                            $k = 0;
                            if ($bombPostDetail) {
                                $d['hitPreciousList'][$k]['id'] = $doc['exposure_post_id'];
                                foreach ($bombPostDetail['pic_list'] as $k1 => $v1) {
                                    $d['hitPreciousList'][$k]['thumbnail'] = $picUtil->getImgView($v1['pic_key']);
                                    break;
                                }
                                $k += 1;
                            }
                            foreach ($hitPreciousCursor as $doc1) {
                                if ($doc1['exposure_post_id'] != $doc1['_id']->{'$id'}) {
                                    $d['hitPreciousList'][$k]['id'] = $doc1['_id']->{'$id'};
                                    foreach ($doc1['pic_list'] as $k2 => $v2) {
                                        $d['hitPreciousList'][$k]['thumbnail'] = $picUtil->getImgView($v2['pic_key']);
                                        break;
                                    }
                                    $k += 1;
                                }
                            }
                        } else {
                            $d['hitPreciousList'] = array();
                        }
                        $d['time'] = $doc['post_create_time']->sec;
                        $d['publishTime'] = $doc['post_show_time']->sec;
                        $d['bonus'] = Weiou_Util_time::timeDiffForHour($doc['post_show_time']->sec, $doc['post_create_time']->sec);

                        $data['list'][] = $d;
                    }
                }
            } else {
                if ($type == 2) {
                    if ($doc['exposure_post_id'] != $doc['_id']->{'$id'}) {
                        $data['delList'][]['id'] = $doc['_id']->{'$id'};
                    }
                } else {
                    $data['delList'][]['id'] = $doc['_id']->{'$id'};
                }
            }
        }
        return $data;
    }

    private function _showViewDataModel($cursor, $post, $beginTimestamp, $endTimestamp) {
        $data = array();
        $data['list'] = array();
        $data['delList'] = array();
        $beginMongotimestamp = new MongoTimestamp($beginTimestamp);
        $endMongotimestamp = new MongoTimestamp($endTimestamp);
        $awards = new Weiou_Model_Awards();
        $picUtil = new Weiou_Util_Pic();
        $awardsList = $awards->getAllAwards();
        $awardsArr = array();
        foreach ($awardsList as $v) {
            $t = array();
            $p = $post->getPostDetailByID($v["awards_post_id"]);
            foreach ($p['pic_list'] as $k => $v2) {
                $v['awards_post_thumbnail'] = $picUtil->getImgView($v2['pic_key']);
                break;
            }
            $t["awards_post_id"] = $v["awards_post_id"];
//            $v["awards_post_url"] = "";
            $awardsArr[$v["show_id"]] = $v;
        }

        $showCursor = $post->getShowHidePost('c_user_post', new MongoTimestamp($beginTimestamp), new MongoTimestamp($endTimestamp), 0, 0, 10, 'refresh');
        $myinfo = $this->getData('userinfo');
        foreach ($showCursor as $show) {
            $firstPostLikes = $show['post_good_num'];
            break;
        }
        foreach ($cursor as $doc) {
            if ($doc['post_isDel'] == 0) {
                $d = array();
                $d['id'] = $doc['_id']->{'$id'};
                $d['userId'] = $myinfo['user_id'];
                $d['userName'] = $myinfo['user_nickname'];
                $d['postLikes'] = $doc['post_good_num'];
                $d['firstPostLikes'] = $firstPostLikes;

                if (isset($doc["post_show_time_id"]) && isset($awardsArr[$doc["post_show_time_id"]])) {
                    $d['firstPostID'] = $awardsArr[$doc["post_show_time_id"]]["awards_post_id"];
                    $d['firstPostThumbnail'] = $awardsArr[$doc["post_show_time_id"]]["awards_post_thumbnail"];
                } else {
                    $d['firstPostID'] = "";
                    $d['firstPostThumbnail'] = "";
                }

                if ($beginTimestamp < $doc['post_show_time']->sec && $doc['post_show_time']->sec <= $endTimestamp) {
                    $allCurrShowCursor = $post->getAllCurrShowHidePosts('c_user_post', $beginMongotimestamp, $endMongotimestamp);
                    $curRange = 0;
                    foreach ($allCurrShowCursor as $showDoc) {
                        if ($showDoc['_id'] == $doc['_id']) {
                            $curRange += 1;
                            break;
                        } else {
                            $curRange += $curRange;
                        }
                    }
                    $d['range'] = $curRange;
                    $d['timeDiff'] = Weiou_Util_Time::timeDiffForSec($endTimestamp, time());
                    $d['bonus'] = Weiou_Util_time::timeDiffForHour($doc['post_show_time']->sec, $doc['post_create_time']->sec);
                } else {
                    $d['range'] = -1;
                    $d['timeDiff'] = 0;
                    $d['bonus'] = Weiou_Util_time::timeDiffForHour($doc['post_show_time']->sec, $doc['post_create_time']->sec);
                }
                foreach ($doc['pic_list'] as $k => $v) {
                    $d['thumbnail'] = $picUtil->getImgView($v['pic_key']);
                    break;
                }
                if (isset($doc['Amap'])) {
                    $lat = Weiou_Utils::getLat($doc['Amap']);
                    $lng = Weiou_Utils::getLng($doc['Amap']);
                    if (isset($doc['formatted_address'])) {
                        $d['address'] = $doc['formatted_address'];
                    } else {
                        $d['address'] = $lat . ',' . $lng;
                    }
                    $d['lat'] = $lat;
                    $d['lng'] = $lng;
                } else {
                    $d['address'] = "";
                    $d['lat'] = 91.00;
                    $d['lng'] = 181.00;
                }
                $d['time'] = $doc['post_show_time']->sec;
                if ($doc['post_isHide'] == 1) {
                    $d['sec'] = $doc['post_create_time']->sec;
                    $d['inc'] = $doc['post_create_time']->inc;
                } else {
                    $d['sec'] = $doc['post_show_time']->sec;
                    $d['inc'] = $doc['post_show_time']->inc;
                }
                if (isset($doc['refreshTime']) && $doc['refreshTime'] != "") {
                    $d['sec'] = $doc['refreshTime']->sec;
                    $d['inc'] = $doc['refreshTime']->inc;
                }

                $data['list'][] = $d;
            } else {
                $data['delList'][]['id'] = $doc['_id']->{'$id'};
            }
        }
        //var_dump($data);
        return $data;
    }

    public function getGamesAction() {
        $json = array();
        $gameService = new Weiou_Service_Game();

        $data = $gameService->getGames();
        $json["state"] = 0;
        $json["data"] = $data;
        if (count($data) == 0) {
            $json["state"] = 10;
        }

        echo json_encode($json);
    }

    public function getGameInfoAction() {
        $gameID = $this->getParam('gameID') ? $this->getParam('gameID') : 0;

        $json = array();
        $gameService = new Weiou_Service_Game();

        $data = $gameService->getGameInfo($gameID);
        $json["state"] = 0;
        $json["data"] = $data;
        if (count($data) == 0) {
            $json["state"] = 10;
        }

        echo json_encode($json);
    }

    public function getGameListAction() {
        $pageType = $this->getParam('pageType') ? trim($this->getParam('pageType')) : 'refresh';
        $pageSize = $this->getParam('pageSize') ? trim($this->getParam('pageSize')) : 30;
        $hashtagID = $this->getParam('gameID') ? trim($this->getParam('gameID')) : '';

        $json = array();
        $gameService = new Weiou_Service_Game();

        $data = $gameService->getGameList($pageType, $pageSize, $hashtagID);
        $json["state"] = 0;
        $json["data"] = $data;
        if (count($data) == 0) {
            $json["state"] = 10;
        }

        echo json_encode($json);
    }

    public function getUserRankingAction() {
        $pageSize = $this->getParam('pageSize') ? trim($this->getParam('pageSize')) : 30;
        $pageNum = $this->getParam('pageNum') ? trim($this->getParam('pageNum')) : 1;
        $hashtagID = $this->getParam('gameID') ? trim($this->getParam('gameID')) : '';
        $locale = $this->getParam('locale') ? trim($this->getParam('locale')) : 'en';
        $this->logger->info($_POST);
        $json = array();
        $jsonData = array();
        $gameService = new Weiou_Service_Game();
        $latestList = $gameService->getUserRanking($hashtagID, $pageNum, $pageSize, $locale);

        $json['state'] = 0;
        $jsonData['list'] = $latestList;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

}
