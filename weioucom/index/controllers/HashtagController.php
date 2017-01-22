<?php

/**
 * Description of HashtagController.php
 * 
 * @author 
 * @Date 2015-7-14 15:43:00
 */
class HashtagController extends Weiou_Controller_Web {
    
    //暂时还没有用

    public function getHashtagsAction() {
        $hashtag = new Weiou_Model_Hashtag();
        $list = $hashtag->getAllHashtagName();

        $json = array();
        if ($list) {
            $json['state'] = 0;
            $json['data']["list"] = $list;
        } else {
            $json['state'] = 10;
            $json['data'] = array();
        }

        echo json_encode($json);
    }

    public function getHashtagInfoAction() {
        $hashtagID = $this->getParam('hashtagID') ? trim($this->getParam('hashtagID')) : null;
        $hashtagName = $this->getParam('hashtagName') ? trim($this->getParam('hashtagName')) : null;

        if ($hashtagID == null && $hashtagName != null) {
            $ht = new Weiou_Model_Hashtag();
            $r = $ht->getIDByName($hashtagName);
            if ($r) {
                $hashtagID = $r['hashtagID'];
            } else {
                $hashtagID = -1; //不存在的id
            }
        }

        $json = array();
        $htService = new Weiou_Service_HashTag();

        $data = $htService->getHashtagInfo($hashtagID);
        $this->logger->info($data);
        $json["state"] = 0;
        $json["data"] = $data;
        if (count($data) == 0) {
            $json["state"] = 10;
        }

        echo json_encode($json);
    }

    public function getHashtagListAction() {
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'refresh';
        $pageSize = $this->getParam('pageSize') ? $this->getParam('pageSize') : 30;
        $hashtagID = trim($this->getParam('hashtagID')) ? trim($this->getParam('hashtagID')) : '';

        $top = null;
        $dt = null;
        $hashtag = new Weiou_Model_Hashtag();
        if ($pageType == "more") {
            $hashtagInfo = $hashtag->getHashtagInfo($hashtagID);
            $top = $hashtagInfo["top"];
            $dt = $hashtagInfo["hashtagCreateTime"];
        } else {
            $pageType = "refresh";
        }

        $json = array();
        $jsonData = array();
        $list = $hashtag->getHashtagList($pageSize, $pageType, $top, $dt);
        $latestList = array();
        $ht = array();
        $post = new Weiou_Model_Post();
        $picUtil = new Weiou_Util_Pic();
        foreach ($list as $v) {
            $ht = $v;
            $ht['latestPostPicUrl'] = "";
            $ht['latestPostContent'] = "";
            if (isset($v["latestPostID"]) && $v["latestPostID"] != "") {
                $pd = $post->getPostDetail($pageType, $v);
                if (isset($pd['post_content'])) {
                    $ht['latestPostContent'] = $pd['post_content'];
                }
                if (isset($pd['pic_list'])) {
                    $ht['latestPostPicUrl'] = $picUtil->getImgView($pd['pic_list'][0]['pic_key']);
                }
            } else {
                $ht["latestPostID"] = "";
            }
            if (isset($v["homePicKey"]) && $v["homePicKey"] != "") {
                $ht["homePicUrl"] = $picUtil->createDownloadUrl($v["homePicKey"]);
            } else {
                $ht["homePicUrl"] = "";
            }
            unset($ht["homePicKey"]);
            $latestList[] = $ht;
        }

        $json['state'] = 0;
        $jsonData['latestList'] = $latestList;
        $jsonData['modifyList'] = array();
        $jsonData['delList'] = array();
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

}
