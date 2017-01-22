<?php

/**
 * Description of FollowController.php
 * 
 * @author Liu Junjie (jjliu@weiou.com)
 * @Date 2016-2-13 23:22:39
 * @copyright 为偶公司
 */
class FollowController extends Weiou_Controller_Web {

    public function indexAction() {
        $this->view->baseUrl = $this->getRequest()->hostUrl;
        $this->view->isFollowPage = 1;
    }

    /**
     * 获取我的相册
     */
    public function getPhotosAction() {
        $pageType = $this->getParam('pageType', 'pre');
        $pageSize = $this->getParam('pageSize', 10);
//        $postRange = $this->getParam('postRange') ? $this->getParam('postRange') : '';
        $postID = $this->getParam('postID', "");
//        $latestTimestamp = trim($this->getParam('timestamp')) ? trim($this->getParam('timestamp')) : '';
//        $imgWidth = trim($this->getParam('width')) ? trim($this->getParam('width')) : 640;
//        $imgHeight = trim($this->getParam('height')) ? trim($this->getParam('height')) : 640;
//        $commentsSize = trim($this->getParam('commentsSize')) ? trim($this->getParam('commentsSize')) : 4;
//        $likesSize = trim($this->getParam('likesSize')) ? trim($this->getParam('likesSize')) : 9;
        $this->logger->info($this->params);
        $postListForm = new Weiou_Form_Ios_PostList();

        $postService = new Weiou_Service_Post();
        $post = new Weiou_Model_Post();
        $timestamp = array();
        $timestamp['pre'] = null;
        $timestamp['modify'] = null;
        $timestamp['latest'] = null;
        $timestamp['more'] = null;

        if ($pageType == 'more') {
            $postDetail = $post->getPostDetail($postID);
            $timestamp['more'] = $postDetail['post_show_time'];
        }

        $myinfo = $this->getApp()->getData("userinfo");
        if ($myinfo) {
            $data = $postService->getFollowingPost($pageType, $pageSize, $timestamp);

            $latestData = $postService->_listViewDataModel($data['list'], $postListForm);

            $posts = $latestData['list'];

            for ($j = 0; $j < count($posts); $j++) {
                $posts[$j]['publishTime'] = Weiou_Util_Time::formatDatetime($posts[$j]['publishTime']);
                for ($i = 0; $i < count($posts[$j]['commentList']); $i++) {
                    $dt = Weiou_Util_Time::formatDatetime($posts[$j]['commentList'][$i]['commentTime']);
                    $posts[$j]['commentList'][$i]['commentTime'] = $dt;
                }
            }

            $this->view->photos = $posts;
        }
    }

}
