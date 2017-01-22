<?php

/**
 * @Description
 * @Author jiangyuchao
 * @E-mail jiangyc0125@163.com
 * @Date 2014-12-18 下午5:15:53
 * @Version V1.0
 */
class IndexController extends Weiou_Controller_Web {

    public function indexAction() {
//
//        echo "index访问测试xx";
//        exit;

        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "";

        if ($host == "weiou.org" || $host == "www.weiou.org") {
            $this->view->setNoRender();
            echo $this->view->render("index_org.html");
            return;
        }

        $this->view->baseUrl = $this->getRequest()->hostUrl;
        $this->view->headerIndexTag = 1;                       //这个

        $this->setDownloadUrl();

        if ($this->_isMobile == 1) {
            $this->view->setNoRender();
            echo $this->view->render("index_mobile.html");
        }
    }

    /**
     * 我关注的人（用户地图）
     */

    public function xxAction(){
        return "测试访问";
    }

    public function getFollowingForMapAction() {
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 500;
        $pageType = trim($this->getParam('pageType')) ? trim($this->getParam('pageType')) : 'pre';
        $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
        $this->logger->info($this->params);
        $hashtagID = $this->getHashtagIDForIos();

        $json = array();
        $jsonData = array();
        $jsonData['latestList'] = array();
        $jsonData['modifyList'] = array();
        $jsonData['delList'] = array();
        $postService = new Weiou_Service_Post();
        $post = new Weiou_Model_Post();
        $timestamp = array();
        $timestamp['pre'] = null;
        $timestamp['modify'] = null;
        $timestamp['latest'] = null;
        $timestamp['more'] = null;
        $loc = 1;

        if ($postRange) {
            $pidArr = explode(',', $postRange);
            if ($pidArr[0] != NULL) {
                $prePostDetail = $post->getPostDetail($pidArr[0]);
                $timestamp['pre'] = $prePostDetail['post_show_time'];
            }
            if ($pidArr[1] != NULL) {
                $modifyPostDetail = $post->getPostDetail($pidArr[1]);
                $timestamp['modify'] = $modifyPostDetail['post_show_time'];
            }
            if ($timestamp['pre'] == null && $timestamp['modify'] == null) {
                $pageType = 'refresh';
            }
        } else {
            $pageType = 'refresh';
        }
        if ($latestTimestamp) {
            $timestamp['latest'] = new MongoTimestamp($latestTimestamp, 0);
        }
        $data = $postService->getFollowingPost($pageType, $pageSize, $timestamp, $hashtagID, $loc);

        if (isset($data['list'])) {
            $latestData = $postService->_mapViewDataModel($data['list']);
            $jsonData['latestList'] = $latestData['list'];
        }
        if (isset($data['modifyList'])) {
            $modifyData = $postService->_mapViewDataModel($data['modifyList']);
            $jsonData['delList'] = array_merge($jsonData['delList'], $modifyData['delList']);
        }
        if (isset($data['delList'])) {
            $jsonData['delList'] = array_merge($jsonData['delList'], $data['delList']);
        }

        $json['state'] = 0;
        $json['data'] = $jsonData;

        echo json_encode($json);
    }

    /**
     * 获取我的关注（九宫格），暂时没用
     */
    public function getFollowingForGridViewAction() {
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = $this->getParam('pageSize') ? $this->getParam('pageSize') : 30;
        $postRange = $this->getParam('postRange') ? $this->getParam('postRange') : '';
        $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
        $user = new Weiou_Model_User();
        $post = new Weiou_Model_Post();
        $json = array();
        $jsonData = array();

        $jsonData['latestList'] = array();
        $jsonData['modifyList'] = array();
        $jsonData['delList'] = array();

        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

    public function getFollowingForListViewAction() {
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = $this->getParam('pageSize') ? $this->getParam('pageSize') : 30;
        $postRange = $this->getParam('postRange') ? $this->getParam('postRange') : '';
        $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
        $imgWidth = trim($this->getParam('width')) ? trim($this->getParam('width')) : 640;
        $imgHeight = trim($this->getParam('height')) ? trim($this->getParam('height')) : 640;
        $commentsSize = trim($this->getParam('commentsSize')) ? trim($this->getParam('commentsSize')) : 4;
        $likesSize = trim($this->getParam('likesSize')) ? trim($this->getParam('likesSize')) : 9;
        $this->logger->info($this->params);
        $postListForm = new Weiou_Form_Ios_PostList();
        $hashtagID = $this->getHashtagIDForIos();

        $json = array();
        $jsonData = array();
        $jsonData['latestList'] = array();
        $jsonData['list'] = array();
        $jsonData['modifyList'] = array();
        $jsonData['delList'] = array();
        $postService = new Weiou_Service_Post();
        $post = new Weiou_Model_Post();
        $timestamp = array();
        $timestamp['pre'] = null;
        $timestamp['modify'] = null;
        $timestamp['latest'] = null;
        $timestamp['more'] = null;
        $loc = 0;


        if ($pageType == 'more') {
            $postDetail = $post->getPostDetail($postID);
            $timestamp['more'] = $postDetail['post_show_time'];
        } else {
            if ($postRange) {
                $pidArr = explode(',', $postRange);
                if ($pidArr[0] != NULL) {
                    $prePostDetail = $post->getPostDetail($pidArr[0]);
                    $timestamp['pre'] = $prePostDetail['post_show_time'];
                }
                if ($pidArr[1] != NULL) {
                    $modifyPostDetail = $post->getPostDetail($pidArr[1]);
                    $timestamp['modify'] = $modifyPostDetail['post_show_time'];
                }
                if ($timestamp['pre'] == null && $timestamp['modify'] == null) {
                    $pageType = 'refresh';
                }
            } else {
                $pageType = 'refresh';
            }
            if ($latestTimestamp) {
                $timestamp['latest'] = new MongoTimestamp($latestTimestamp, 0);
            }
        }
        $data = $postService->getFollowingPost($pageType, $pageSize, $timestamp, $hashtagID, $loc);

        if (isset($data['list'])) {
            $latestData = $postService->_listViewDataModel($data['list'], $postListForm);
            if ($pageType == "more") {
                $jsonData['list'] = $latestData['list'];
            } else {
                $jsonData['latestList'] = $latestData['list'];
            }
        }
        if (isset($data['modifyList'])) {
            $modifyData = $postService->_listViewDataModel($data['modifyList'], $postListForm);
            $jsonData['modifyList'] = $modifyData['list'];
            $jsonData['delList'] = array_merge($jsonData['delList'], $modifyData['delList']);
        }
        if (isset($data['delList'])) {
            $jsonData['delList'] = array_merge($jsonData['delList'], $data['delList']);
        }

        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

    /**
     * 获取最新（地图）
     */
    public function getLatestForMapAction() {
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 500;
        $pageType = trim($this->getParam('pageType')) ? trim($this->getParam('pageType')) : 'pre';
        $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';

        $hashtagID = $this->getHashtagIDForIos();

        $postService = new Weiou_Service_Post();
        $post = new Weiou_Model_Post();
        $preTimestamp = NULL;
        $modifyTimestamp = NULL;
        if ($postRange) {
            $pidArr = explode(',', $postRange);
            if ($pidArr[0] != NULL) {
                $prePostDetail = $post->getPostDetail($pidArr[0]);
                $preTimestamp = $prePostDetail['post_show_time'];
            }
            if ($pidArr[1] != NULL) {
                $modifyPostDetail = $post->getPostDetail($pidArr[1]);
                $modifyTimestamp = $modifyPostDetail['post_show_time'];
            }
        }
        $preCursor = $post->getLatestPost($preTimestamp, (int) $pageSize, $pageType, $hashtagID, 1);

        $json = array();
        $jsonData = array();

        $preList = $postService->_mapViewDataModel($preCursor);
        $jsonData['latestList'] = $preList['list'];

        if ($modifyTimestamp && $latestTimestamp) {
            $latestTimestamp = new MongoTimestamp($latestTimestamp, 0);
            $modifyCursor = $post->getLatestModifyPosts($preTimestamp, $latestTimestamp, (int) $pageSize, $hashtagID, 1);

            $modifyList = $postService->_mapViewDataModel($modifyCursor);
            $jsonData['delList'] = $modifyList['delList'];
        } else {
            $jsonData['delList'] = array();
        }
        $jsonData['modifyList'] = array();
        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

    /**
     * 获取最新（九宫格）
     */
    public function getLatestForGridViewAction() {
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 10;
        $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';

        $hashtagID = $this->getHashtagIDForIos();

        $this->logger->info($this->params);
        $post = new Weiou_Model_Post();
        $json = array();
        $jsonData = array();
        $postService = new Weiou_Service_Post();
        if ($pageType == 'more') {
            $postDetail = $post->getPostDetail($postID);
            $moreTimestamp = $postDetail['post_show_time'];
            $moreCursor = $post->getLatestPost($moreTimestamp, (int) $pageSize, $pageType, $hashtagID);
            $moreData = $postService->_gridViewDataModel($moreCursor);
            $jsonData['list'] = $moreData['list'];
        } else {
            $preTimestamp = NULL;
            $modifyTimestamp = NULL;
            if ($postRange) {
                $pidArr = explode(',', $postRange);
                if ($pidArr[0] != NULL) {
                    $prePostDetail = $post->getPostDetail($pidArr[0]);
                    $preTimestamp = $prePostDetail['post_show_time'];
                }
                if ($pidArr[1] != NULL) {
                    $modifyPostDetail = $post->getPostDetail($pidArr[1]);
                    $modifyTimestamp = $modifyPostDetail['post_show_time'];
                }
            }
            $preCursor = $post->getLatestPost($preTimestamp, (int) $pageSize, $pageType, $hashtagID);
            $preData = $postService->_gridViewDataModel($preCursor);
            $jsonData['latestList'] = $preData['list'];
            $jsonData['modifyList'] = array();
            $jsonData['delList'] = array();
            if ($modifyTimestamp && $latestTimestamp) {
                $latestTimestamp = new MongoTimestamp($latestTimestamp, 0);
                $modifyCursor = $post->getLatestModifyPosts($preTimestamp, $latestTimestamp, (int) $pageSize, $hashtagID);
                $modifyData = $postService->_gridViewDataModel($modifyCursor);
                $jsonData['modifyList'] = $modifyData['list'];
                $jsonData['delList'] = $modifyData['delList'];
            }
        }
        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

    public function getPostsByPostIDsForListViewAction() {
        $postIdStr = $this->getParam('postIDs');

        $result = new Weiou_JsonModel_ResultForIos();
        $jsonData = array();
        $jsonData['list'] = array();

        $postListForm = new Weiou_Form_Ios_PostList();

        if ($postIdStr) {
            $post = new Weiou_Model_Post();
            $postIdArr = explode(',', $postIdStr);
            $postService = new Weiou_Service_Post();
            foreach ($postIdArr as $v) {
                $postID = trim($v);
                if ($postID) {
                    $postDetail = $post->getPostDetailByID($postID);
                    if ($postDetail) {
                        $jsonData['list'][] = $postService->_postDetailForIos($postDetail, $postListForm);
                    }
                }
            }
        }
        $result->data = $jsonData;
        echo $result->toJson();
    }

    public function getLatestForListViewAction() {
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
        $hashtagID = $this->getHashtagIDForIos();

        $post = new Weiou_Model_Post();
        $postService = new Weiou_Service_Post();
        $json = array();
        $jsonData = array();
        $this->logger->info($this->params);

        if ($pageType == 'more') {
            $postDetail = $post->getPostDetail($postID);
            $moreTimestamp = $postDetail['post_show_time'];
            $moreCursor = $post->getLatestPost($moreTimestamp, (int) $pageSize, $pageType, $hashtagID);
            $moreData = $postService->_listViewDataModel($moreCursor, $postListForm);
            $jsonData['list'] = $moreData['list'];
        } else {
            $preTimestamp = NULL;
            $modifyTimestamp = NULL;
            if ($postRange) {
                $pidArr = explode(',', $postRange);
                if ($pidArr[0] != NULL) {
                    $prePostDetail = $post->getPostDetail($pidArr[0]);
                    $preTimestamp = $prePostDetail['post_show_time'];
                }
                if ($pidArr[1] != NULL) {
                    $modifyPostDetail = $post->getPostDetail($pidArr[1]);
                    $modifyTimestamp = $modifyPostDetail['post_show_time'];
                }
            }
            $preCursor = $post->getLatestPost($preTimestamp, (int) $pageSize, $pageType, $hashtagID);
            $preData = $postService->_listViewDataModel($preCursor, $postListForm);
            $jsonData['latestList'] = $preData['list'];
            $jsonData['modifyList'] = array();
            $jsonData['delList'] = array();

            if ($modifyTimestamp && $latestTimestamp) {
                $latestTimestamp = new MongoTimestamp($latestTimestamp, 0);
                $modifyCursor = $post->getLatestModifyPosts($preTimestamp, $latestTimestamp, (int) $pageSize, $hashtagID);
                $modifyData = $postService->_listViewDataModel($modifyCursor, $postListForm);
                $jsonData['modifyList'] = $modifyData['list'];
                $jsonData['delList'] = $modifyData['delList'];
            }
        }
        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

    /**
     * 获取最热帖子
     * @param string $collection_name
     * @param string $timestamp
     * @param number $pageSize
     * @return MongoCursor
     */
    public function getHottestPostsForListViewAction() {
        /*
          $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
          //	    $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
          $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 10;
          $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
          //	    $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
          $commentsSize = trim($this->getParam('commentsSize')) ? trim($this->getParam('commentsSize')) : 4;
          $likesSize = trim($this->getParam('likesSize')) ? trim($this->getParam('likesSize')) : 9;
          $imgWidth = trim($this->getParam('width')) ? trim($this->getParam('width')) : 640;
          $imgHeight = trim($this->getParam('height')) ? trim($this->getParam('height')) : 640;
         */

        $postListForm = new Weiou_Form_Ios_PostList();

        $pageType = $postListForm->pageType;
        $pageSize = $postListForm->pageSize;
        $postID = $postListForm->postID;
        $commentsSize = $postListForm->commentsSize;
        $likesSize = $postListForm->likesSize;
        $imgWidth = $postListForm->imgWidth;
        $imgHeight = $postListForm->imgHeight;

        $hashtagID = $this->getHashtagIDForIos();



        $post = new Weiou_Model_Post();
        $postService = new Weiou_Service_Post();
        $json = array();
        $jsonData = array();
        $loc = 0;
        if ($pageType == 'more') {
            $postDetail = $post->getPostDetail($postID);
            $moreTimestamp = $postDetail['post_show_time'];
            $moreGoodNum = $postDetail['post_good_num'];

            $moreCursor = $postService->getHottestPosts($pageSize, $pageType, $moreGoodNum, $moreTimestamp, $hashtagID, $loc);

            $moreData = $postService->_listViewDataModel($moreCursor, $postListForm);
            $jsonData['list'] = $moreData['list'];
        } else {
            $pageType = 'refresh';
            $preCursor = $postService->getHottestPosts($pageSize, $pageType, null, null, $hashtagID, $loc);

            $preData = $postService->_listViewDataModel($preCursor, $postListForm);
            $jsonData['latestList'] = $preData['list'];

            $jsonData['modifyList'] = array();
            $jsonData['delList'] = array();
        }
        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

    public function getRecommendForMapAction() {
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 500;
        $pageType = trim($this->getParam('pageType')) ? trim($this->getParam('pageType')) : 'pre';
        $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
        $hashtagID = trim($this->getParam('hashtagID')) ? trim($this->getParam('hashtagID')) : null;

        $this->logger->info($this->params);
        $postService = new Weiou_Service_Post();
        $post = new Weiou_Model_Post();

        if ($latestTimestamp) {
            $preTimestamp = new MongoTimestamp($latestTimestamp, 0);
        } else {
            $pageType = 'refresh';
            $preTimestamp = NULL;
        }
        $preCursor = array();

        if (!$latestTimestamp) {
            //不能根据hashtagID来获取数据
//            $pageSize = 500;
            $preCursor = $postService->getRecommendForMapFirst($pageSize);
        } else {
            $preCursor = $post->getLatestPostByRefreshTime($preTimestamp, (int) $pageSize, $pageType, $hashtagID, 1);
        }
        $json = array();
        $jsonData = array();

        $preList = $postService->_mapViewDataModel($preCursor);
//        if ($pageType == "pre") {
//            $preList = array_reverse($preList);
//        }
        $jsonData['latestList'] = $preList['list'];
        $jsonData['modifyList'] = array();
        $jsonData['delList'] = array();

        if ($latestTimestamp) {
            $latestTimestamp = new MongoTimestamp($latestTimestamp, 0);
            $modifyCursor = $post->getLatestModifyPostsByRefreshTime($preTimestamp, $latestTimestamp, $pageSize, $hashtagID, 1);
            $modifyList = $postService->_mapViewDataModel($modifyCursor);
            $jsonData['delList'] = $modifyList['delList'];
        }
        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

    public function getRecommendForListViewAction() {
//        $this->pageType = $request->get('pageType') ? $request->get('pageType') : 'pre';
//          $this->postRange = $request->get('postRange') ? $request->get('postRange') : '';
//          $this->pageSize = $request->get('pageSize') ? $request->get('pageSize') : 10;
//          $this->postID = $request->get('postID') ? $request->get('postID') : '';
//          $this->latestTimestamp = $request->get('timestamp') ? $request->get('timestamp') : '';
//          $this->commentsSize = $request->get('commentsSize') ? $request->get('commentsSize') : 4;
//          $this->likesSize = $request->get('likesSize') ? $request->get('likesSize') : 9;
//          $this->imgWidth = $request->get('width') ? $request->get('width') : 640;
//          $this->imgHeight = $request->get('height') ? $request->get('height') : 640;
//          $this->hashtagID = $request->get('hashtagID') ? $request->get('hashtagID') : null;
//          $this->sec = $request->get('sec') ? $request->get('sec') : 0;
//          $this->inc = $request->get('inc') ? $request->get('inc') : 0;
//          $this->networkMode = $request->get('networkMode') ;

        $postListForm = new Weiou_Form_Ios_PostList();

        $pageType = $postListForm->pageType;
        $postRange = $postListForm->postRange;
        $pageSize = $postListForm->pageSize;
        $postID = $postListForm->postID;
        $latestTimestamp = $postListForm->latestTimestamp;
        $commentsSize = $postListForm->commentsSize;
        $likesSize = $postListForm->likesSize;
        $imgWidth = $postListForm->imgWidth;
        $imgHeight = $postListForm->imgHeight;
        $hashtagID = $postListForm->hashtagID;
        $sec = $postListForm->sec;
        $inc = $postListForm->inc;
        $networkMode = $postListForm->networkMode;

        $post = new Weiou_Model_Post();
        $postService = new Weiou_Service_Post();
        $json = array();
        $jsonData = array();

        if ($pageType == 'more') {
            if ($sec == 0) {
                $postDetail = $post->getPostDetail($postID);
                $moreTimestamp = $postDetail['refreshTime'];
            } else {
                $moreTimestamp = new MongoTimestamp($sec, $inc);
            }
            $moreCursor = $post->getLatestPostByRefreshTime($moreTimestamp, (int) $pageSize, $pageType, $hashtagID);
            $moreData = $postService->_listViewDataModel($moreCursor, $postListForm);
            $jsonData['list'] = $moreData['list'];
        } else {
            if ($latestTimestamp) {
                $preTimestamp = new MongoTimestamp($latestTimestamp, 0);
            } else {
                $pageType = 'refresh';
                $preTimestamp = NULL;
            }
            $preCursor = $post->getLatestPostByRefreshTime($preTimestamp, (int) $pageSize, $pageType, $hashtagID);
            $preData = $postService->_listViewDataModel($preCursor, $postListForm);

//            if ($pageType == "pre") {
//                $preData['list'] = array_reverse($preData['list']);
//            }

            $jsonData['latestList'] = $preData['list'];
//            array_push($jsonData, $preData);
            $jsonData['modifyList'] = array();
            $jsonData['delList'] = array();

            if ($latestTimestamp) {
                $latestTimestamp = new MongoTimestamp($latestTimestamp, 0);
                $modifyCursor = $post->getLatestModifyPostsByRefreshTime($preTimestamp, $latestTimestamp, $pageSize, $hashtagID);
                $modifyData = $postService->_listViewDataModel($modifyCursor, $postListForm);
                $jsonData['modifyList'] = $modifyData['list'];
                $jsonData['delList'] = $modifyData['delList'];
            }
        }
        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

    public function getRecommendForGridViewAction() {
        $this->getLatestForGridViewAction();
    }

    public function getBestForMapAction() {
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 10;

        $hashtagID = $this->getHashtagIDForIos();

        $postService = new Weiou_Service_Post();
        $result = new Weiou_JsonModel_ResultForIos();
        $jsonData = array();
        $this->logger->info($this->params);

        $queryTimestamp = $this->getQueryTimestampForIos();
        $loc = 0;

        $postCursor = $postService->getBestPost($pageSize, $pageType, $queryTimestamp, $hashtagID, $loc);
        $postData = $postService->_mapViewDataModel($postCursor['list']);

        if ($pageType == 'more') {
            $jsonData['list'] = $postData['list'];
        } else {
            $jsonData['latestList'] = $postData['list'];
            $jsonData['modifyList'] = array();
            $jsonData['delList'] = array();

            $modifyPostData = $postService->_mapViewDataModel($postCursor['modifyList']);
            $jsonData['modifyList'] = $modifyPostData['list'];
            $jsonData['delList'] = $modifyPostData['delList'];
        }

        $result->state = 0;
        $result->data = $jsonData;
        echo $result->toJson();
    }

    public function getBestForListViewAction() {
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 10;
        $commentsSize = trim($this->getParam('commentsSize')) ? trim($this->getParam('commentsSize')) : 4;
        $likesSize = trim($this->getParam('likesSize')) ? trim($this->getParam('likesSize')) : 9;
        $imgWidth = trim($this->getParam('width')) ? trim($this->getParam('width')) : 640;
        $imgHeight = trim($this->getParam('height')) ? trim($this->getParam('height')) : 640;

        $postListForm = new Weiou_Form_Ios_PostList();
        $hashtagID = $this->getHashtagIDForIos();

        $postService = new Weiou_Service_Post();
        $result = new Weiou_JsonModel_ResultForIos();
        $jsonData = array();
        $this->logger->info($this->params);

        $queryTimestamp = $this->getQueryTimestampForIos();
        $loc = 0;

        $postCursor = $postService->getBestPost($pageSize, $pageType, $queryTimestamp, $hashtagID, $loc);
        $postData = $postService->_listViewDataModel($postCursor['list'], $postListForm);

        if ($pageType == 'more') {
            $jsonData['list'] = $postData['list'];
        } else {
            $jsonData['latestList'] = $postData['list'];
            $jsonData['modifyList'] = array();
            $jsonData['delList'] = array();

            $modifyPostData = $postService->_listViewDataModel($postCursor['modifyList'], $postListForm);
            $jsonData['modifyList'] = $modifyPostData['list'];
            $jsonData['delList'] = $modifyPostData['delList'];
        }

        $result->state = 0;
        $result->data = $jsonData;
        echo $result->toJson();
    }

    public function getBestForGridViewAction() {
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 10;

        $hashtagID = $this->getHashtagIDForIos();

        $postService = new Weiou_Service_Post();
        $result = new Weiou_JsonModel_ResultForIos();
        $jsonData = array();
        $this->logger->info($this->params);

        $queryTimestamp = $this->getQueryTimestampForIos();
        $loc = 0;

        $postCursor = $postService->getBestPost($pageSize, $pageType, $queryTimestamp, $hashtagID, $loc);
        $postData = $postService->_gridViewDataModel($postCursor['list']);

        if ($pageType == 'more') {
            $jsonData['list'] = $postData['list'];
        } else {
            $jsonData['latestList'] = $postData['list'];
            $jsonData['modifyList'] = array();
            $jsonData['delList'] = array();

            $modifyPostData = $postService->_gridViewDataModel($postCursor['modifyList']);
            $jsonData['modifyList'] = $modifyPostData['list'];
            $jsonData['delList'] = $modifyPostData['delList'];
        }

        $result->state = 0;
        $result->data = $jsonData;
        echo $result->toJson();
    }

    public function test2Action() {
        $this->view->setNoRender(true);

        $frameWidth = $this->getParam('fw', 900);

        $postListForm = new Weiou_Form_Ios_PostList();

        $pageType = $postListForm->pageType;
        $pageSize = $postListForm->pageSize;

        $hashtagID = $this->getHashtagIDForIos();
        $this->logger->info($hashtagID);

        $postService = new Weiou_Service_Post();
        $result = new Weiou_JsonModel_ResultForIos();
        $jsonData = array();
        $this->logger->info($this->params);

        $queryTimestamp = $this->getQueryTimestampForIos();
        $loc = 0;
        $best = 0;
        $category = null;

        $postCursor = $postService->getLatestPost($pageSize, $pageType, $queryTimestamp, $hashtagID, $loc, $best, $category);
        $this->logger->info($postCursor);

        $rowHeight = $frameWidth * 0.25;
//        $frameWidth = 960;
        $gap = 5;
        $this->logger->info("yyyyyyyyyyy");
        $postData = $postService->_scrollViewResponsiveDataModel($postCursor['list'], $rowHeight, $frameWidth, $gap);
        echo "adsfadsf<br><hr>";

        $picData = $postData["picData"];
        $rowHeightArr = $postData["rowHeight"];
        $rowCount = count($picData);
        $zz = 0;
        for ($i = 0; $i < $rowCount; $i++) {
            $rowHeightReal = $rowHeightArr[$i];
            $this->logger->info("height:" . $rowHeightReal);
            $this->logger->info(count($picData[$i]));
            echo "<div>";
            foreach ($picData[$i] as $pic) {
                $zz++;
//                $picWidth = round($pic["aspectRatio"] * $rowHeightReal);
                $picWidth = $pic["width"];
                $picUrl = $pic["thumbnail"];
                echo '<img height="' . $rowHeightReal . 'px" width="' . $picWidth . 'px" src="' . $picUrl . '" style="text-align: right; margin-right: ' . $gap . 'px;">';
            }
            echo "</div>";
//            echo "<div height=".$gap."px></div>";
            echo "<hr>";
        }

        echo "<br><br>";
        echo $zz;
    }

    public function testAction() {
        echo "test-index";
        $this->view->setNoRender();
        var_dump($this->params);
        $this->logger->info($this->params);
        
        $this->logger->info($_GET);
        $this->logger->info($_POST);
        
        
        $content = $this->getParam("content");
        $picData = $this->getParam("picData");
        
        $this->logger->info($content);
        
        $this->logger->info($picData);
        
        
        $pd = json_decode($picData,true);
        $this->logger->info($pd);
        
        $pd0 = $pd[0];
        $this->logger->info($pd0);
        $this->logger->info($pd0["content"]);
        
    }

    public function getLatestPostsForScrollViewAction() {

        $this->view->setNoRender(true);

        $this->logger->info($this->params);
//        echo "<pre>";
//        print_r($this->params);
//        exit;
        $frameWidth = $this->getParam('fw', 1000);                  //px

        $gap = $this->getParam('gap', 8);                           //px

        $rowHeight = $this->getParam('height', $frameWidth * 0.2);  //px

        $postListForm = new Weiou_Form_Ios_PostList();

        $pageType = $postListForm->pageType;                        //pre
        $pageSize = $postListForm->pageSize;                        //30

        $hashtagID = $this->getHashtagIDForIos();                   //null

        $this->logger->info($hashtagID);                            //这也是啥也没有啊

        $postService = new Weiou_Service_Post();                    //数据库mysql与mongo服务

        $result = new Weiou_JsonModel_ResultForIos();               //null

        $queryTimestamp = $this->getQueryTimestampForIos();

        $loc  = 0;
        $best = 0;
//        $category = null;

        $category = $this->getParam('category', "all");             //获取到分类
        if ($category == "all") {
            $category = null;
        }

        //                                        30         pre         null            null        0     0
        $postCursor = $postService->getLatestPost($pageSize, $pageType, $queryTimestamp, $hashtagID, $loc, $best, $category);
//        echo "<pre>";
//        print_r($postCursor['list']);
//        exit;
        $this->logger->info($postCursor);

//        $frameWidth = 960;
//        $gap = 5;//   px
        $postData = $postService->_scrollViewResponsiveDataModel($postCursor['list'], $rowHeight, $frameWidth, $gap);

//        $picData = $postData["picData"];
//        $rowHeightArr = $postData["rowHeight"];
//
//        echo $category;
//        echo "<hr/>";
//        exit;
        $result->data = $postData;

        echo $result->toJson();
    }

    public function getHottestPostsForScrollViewAction() {
        $this->view->setNoRender(true);
        $this->logger->info($this->params);
        /*
          $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
          //	    $postRange = trim($this->getParam('postRange')) ? trim($this->getParam('postRange')) : '';
          $pageSize = trim($this->getParam('pageSize')) ? trim($this->getParam('pageSize')) : 10;
          $postID = trim($this->getParam('postID')) ? trim($this->getParam('postID')) : '';
          //	    $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
          $commentsSize = trim($this->getParam('commentsSize')) ? trim($this->getParam('commentsSize')) : 4;
          $likesSize = trim($this->getParam('likesSize')) ? trim($this->getParam('likesSize')) : 9;
          $imgWidth = trim($this->getParam('width')) ? trim($this->getParam('width')) : 640;
          $imgHeight = trim($this->getParam('height')) ? trim($this->getParam('height')) : 640;
         */

        $postListForm = new Weiou_Form_Ios_PostList();

        $pageType = $postListForm->pageType;
        $pageSize = $postListForm->pageSize;
        $postID = $postListForm->postID;

        $hashtagID = $this->getHashtagIDForIos();
        $loc = 0;

        $frameWidth = $this->getParam('fw', 1000);  //px
        $gap = $this->getParam('gap', 8);  //px
        $rowHeight = $this->getParam('height', $frameWidth * 0.2);  //px

        $category = $this->getParam('category', "all");
        if ($category == "all") {
            $category = null;
        }

        $result = new Weiou_JsonModel_ResultForIos();

        $post = new Weiou_Model_Post();
        $postService = new Weiou_Service_Post();

        if ($pageType == 'more') {
            $postDetail = $post->getPostDetail($postID);
            $moreTimestamp = $postDetail['post_show_time'];
            $moreGoodNum = $postDetail['post_good_num'];
        } else {
            $pageType = 'refresh';

            $moreTimestamp = null;
            $moreGoodNum = null;
        }

        $postCursor = $postService->getHottestPosts($pageSize, $pageType, $moreGoodNum, $moreTimestamp, $hashtagID, $loc, $category);


        $postData = $postService->_scrollViewResponsiveDataModel($postCursor, $rowHeight, $frameWidth, $gap);

        $result->data = $postData;
        header('Content-type: application/json');
        echo $result->toJson();
    }

}
