<?php

class MessageController extends Weiou_Controller_Web {
    
    //暂时还没有用
    
    /*
     * 新版，1.03之后
     */

    public function getUserMessNewAction() {
        $pageSize = $this->getParam("pageSize", 30);
        $pageType = $this->getParam("pageType", 'refresh');
        $sec = $this->getParam("sec", 0);
        $inc = $this->getParam('inc',0 );
        
        $type = $this->getParam('type');//1：点赞，2：评论，3：关注
        
        $result = new Weiou_JsonModel_ResultForIos();
        $data = array();

        $timestamp = NULL;
        if ($sec) {
            $timestamp = new MongoTimestamp($sec, $inc);
        }
        $this->logger->info($this->params);
        $messService = new Weiou_Service_Message();
        $list = $messService->getUserMessNew($pageSize, $pageType, $timestamp, $type);

        $data['list'] = $list;

        $result->state = 0;
        $result->data = $data;
        echo $result->toJson();
    }

    /*
     * 老版本，1.02及以前
     */

    public function getUserMessAction() {
        $pageSize = $this->getParam("pageSize") ? $this->getParam("pageSize") : 30;
        $pageType = $this->getParam("pageType") ? $this->getParam("pageType") : 'refresh';
        $sec = $this->getParam("sec") ? $this->getParam('sec') : 0;
        $inc = $this->getParam('inc') ? $this->getParam('inc') : 0;
        $result = new Weiou_JsonModel_ResultForIos();
        $data = array();

        $timestamp = NULL;
        if ($sec) {
            $timestamp = new MongoTimestamp($sec, $inc);
        }
        $this->logger->info($this->params);
        $messService = new Weiou_Service_Message();
        $list = $messService->getUserMess($pageSize, $pageType, $timestamp);

        $data['list'] = $list;

        $result->state = 0;
        $result->data = $data;
        echo $result->toJson();
    }

    /*
     * 动态，关注
     * 1.03之后
     */

    public function getFollowingMessAction() {
        $pageSize = $this->getParam("pageSize") ? $this->getParam("pageSize") : 30;
        $pageType = $this->getParam("pageType") ? $this->getParam("pageType") : 'refresh';
        $sec = $this->getParam("sec") ? $this->getParam('sec') : 0;
        $inc = $this->getParam('inc') ? $this->getParam('inc') : 0;

        $result = new Weiou_JsonModel_ResultForIos();
        $data = array();

        $timestamp = NULL;
        if ($sec) {
            $timestamp = new MongoTimestamp($sec, $inc);
        }
        $this->logger->info($this->params);
        $messService = new Weiou_Service_Message();
        $list = $messService->getFollowingMess($pageSize, $pageType, $timestamp);

        $data['list'] = $list;

        $result->state = 0;
        $result->data = $data;
        echo $result->toJson();
    }

    /*
     * 动态，关注，old
     * 返回格式，1.02及以前,数据获取是新表。
     */

    public function getConcernedMessAction() {
        $pageSize = $this->getParam("pageSize") ? $this->getParam("pageSize") : 30;
        $pageType = $this->getParam("pageType") ? $this->getParam("pageType") : 'refresh';
        $sec = $this->getParam("sec") ? $this->getParam('sec') : 0;
        $inc = $this->getParam('inc') ? $this->getParam('inc') : 0;

        $result = new Weiou_JsonModel_ResultForIos();
        $data = array();

        $timestamp = NULL;
        if ($sec) {
            $timestamp = new MongoTimestamp($sec, $inc);
        }
        $this->logger->info($this->params);
        $messService = new Weiou_Service_Message();
        $platform = "ios";
        $list = $messService->getFollowingMessOld($pageSize, $pageType, $timestamp, $platform);

        $data['list'] = $list;
        $data['idList'] = "";

        $result->data = $data;
        echo $result->toJson();
    }

    /*
     * 动态，关注，old
     * 1.02及以前
     */

    public function getConcernedMess_bk() {
        $userinfo = $this->getData("userinfo");
        $pageSize = $this->getParam("pageSize") ? $this->getParam("pageSize") : 30;
        $pageType = $this->getParam("pageType") ? $this->getParam("pageType") : 'refresh';
        $sec = $this->getParam("sec") ? $this->getParam('sec') : 0;
        $inc = $this->getParam('inc') ? $this->getParam('inc') : 0;
        $idlist = $this->getParam("idlist") ? $this->getParam("idlist") : "";
        $jsonData = array();
        if ($sec) {
            $timestamp = new MongoTimestamp((int) $sec, (int) $inc);
        } else {
            $timestamp = NULL;
        }
        $message = new message();
        $concerned_list = $message->getConcernedList("c_user_concerned", $userinfo['user_id']);
        if (!isset($concerned_list['concerned_list']) || count($concerned_list['concerned_list']) == 0) {
            $jsonData['list'] = array();
            $json1['state'] = 0;
            $json1['data'] = $jsonData;
        } else {
            foreach ($concerned_list['concerned_list'] as $k => $v) {
                $useridlist[$k] = $v['concerned_id'];
            }
            if ($idlist == "") {
                $messidlist = array();
            } else {
                $messidlist = explode(",", $idlist);
            }
            $mess_list = $message->getConcernedMess("c_user_mess", $useridlist, $messidlist, (int) $pageSize, $pageType, $timestamp);
            $json = $this->getJsondate($mess_list, $idlist);
            if ($json['list'] != array()) {
                $num = 1;
                $arr = $json['list']['list'];
                $sum = 0;
                while ($json['list'] != array()) {
                    $idlist = $json['idlist'];
                    if ($idlist == "") {
                        $messidlist = array();
                    } else {
                        $messidlist = explode(",", $idlist);
                    }
                    $p = $json['p'];
                    $k = $json['k'];
                    $list = $json['list'];
                    if ($num == 1) {
                        $jsonData['list'] = $json['list']['list'];
                        $num ++;
                    } else {
                        $jsonData['list'] = array_merge($arr, $json['list']['list']);
                        $arr = $jsonData['list'];
                    }
                    $sum += $p + $k;
                    if ($sum == $pageSize) {
                        break;
                    }
                    $mess_list = $this->addMess($useridlist, $messidlist, $pageSize - $sum, 'more', new MongoTimestamp($list['list'][$p + $k - 1]['sec'], $list['list'][$p + $k - 1]['inc']));
                    $json = $this->getJsondate($mess_list, $idlist);
                }
                $jsonData['idlist'] = "";
                $json1['state'] = 0;
                $json1['data'] = $jsonData;
            } else {
                $jsonData['idlist'] = "";
                $jsonData['list'] = array();
                $json1['state'] = 0;
                $json1['data'] = $jsonData;
            }
        }
        echo json_encode($json1);
    }

    public function changeMessIsreadAction() {
        $userinfo = $this->getData("userinfo");
        $messid = $this->getParam("messid") ? $this->getParam("messid") : "";
        if ($messid == "") {
            $this->view->setData((object) NULL);
            $this->view->setMsg("");
            $this->view->setState("-80032");
        } else {
            $message = new message();
            $mess = $message->changeMessIsread("c_user_mess", $userinfo['user_id'], $messid);
            $mess['result'][0]['mess_list']['mess_isRead'] = 1;
            $r = $message->changeMessIsreadByMess("c_user_mess", $userinfo['user_id'], $mess['result'][0]['mess_list']);
            if ($r['ok'] == 1) {
                $this->view->setData((object) NULL);
                $this->view->setMsg("");
                $this->view->setState("80031");
            } else {
                $this->view->setData((object) NULL);
                $this->view->setMsg("");
                $this->view->setState("-80030");
            }
        }
        $this->view->display("json");
    }

    public function addMess($useridlist, $messidlist, $pageSize, $pageType, $timestamp) {
        $message = new message();
        return $message->getConcernedMess("c_user_mess", $useridlist, $messidlist, (int) $pageSize, $pageType, $timestamp);
    }

    public function getJsondate($mess_list, $idlist) {
        $message = new message();
        $picUtil = new Weiou_Util_Pic();
        $messlist = array();
        $list = array();
        $k = 0;
        if ($mess_list['result']) {
            $user = new Weiou_Model_User();
            $post = new Weiou_Model_Post();
            foreach ($mess_list['result'] as $v) {
                if ($v['mess_list']['mess_type'] == 1) {
                    if (!in_array($v['mess_list']['from_user_id'], $list)) {
                        array_push($list, $v['mess_list']['from_user_id']);
                        $messlist[$k] = $v;
                        $k ++;
                    }
                }
            }
            foreach ($messlist as $k0 => $v0) {
                $start = new MongoTimestamp($v0['mess_list']['create_time']->sec, $v0['mess_list']['create_time']->inc);
                $end = new MongoTimestamp($v0['mess_list']['create_time']->sec - 86400, $v0['mess_list']['create_time']->inc);
                $messinfo = $message->getMessByTime("c_user_mess", $v0['mess_list']['from_user_id'], $start, $end, explode(",", $idlist));
                $touserinfo = $user->getUserinfoByID($v0['user_id']);
                $fromuserinfo = $user->getUserinfoByID($v0['mess_list']['from_user_id']);
                if (count($messinfo['result']) == 1) {
                    $postdetail = $post->getPostDetailByID($messinfo['result'][0]['mess_list']['post_id']);
                    $jsonData['list'][$k0]['pic_url'] = $picUtil->getImgView($postdetail['pic_list'][0]['pic_key']);
                    $jsonData['list'][$k0]['post_id'] = $messinfo['result'][0]['mess_list']['post_id'];
                    $jsonData['list'][$k0]['id'] = $messinfo['result'][0]['mess_list']['id'];
                } else {
                    $mess_list_list = array_column($messinfo['result'], 'mess_list');
                    $postid = array_column($mess_list_list, 'post_id');
                    $postidlist = array_unique($postid);
                    $k1 = 0;
                    foreach ($postidlist as $v1) {
                        $postdetail = $post->getPostDetailByID($v1);
                        $jsonData['list'][$k0]['post_list'][$k1]['pic_url'] = $picUtil->getImgView($postdetail['pic_list'][0]['pic_key']);
                        foreach ($mess_list_list as $val) {
                            if ($val['post_id'] == $v1) {
                                $jsonData['list'][$k0]['post_list'][$k1]['id'] = $val['id'];
                            }
                            if ($idlist == "") {
                                $idlist = $val['id'];
                            } else {
                                $idlist .= "," . $val['id'];
                            }
                        }
                        $jsonData['list'][$k0]['post_list'][$k1]['post_id'] = $v1;
                        $k1 ++;
                    }
                }
                $jsonData['list'][$k0]['to_user_id'] = $v0['user_id'];
                $jsonData['list'][$k0]['to_user_name'] = $touserinfo['user_nickname'];
                $jsonData['list'][$k0]['from_user_id'] = $v0['mess_list']['from_user_id'];
                $jsonData['list'][$k0]['user_name'] = $fromuserinfo['user_nickname'];
                if ($fromuserinfo['user_logo']) {
                    $jsonData['list'][$k0]['user_logo'] = $picUtil->getUserLogo($fromuserinfo['user_logo']);
                } else {
                    $jsonData['list'][$k0]['user_logo'] = NULL;
                }
                $jsonData['list'][$k0]['mess_type'] = 1;
                $jsonData['list'][$k0]['time'] = $v0['mess_list']['create_time']->sec;
                $jsonData['list'][$k0]['sec'] = $v0['mess_list']['create_time']->sec;
                $jsonData['list'][$k0]['inc'] = $v0['mess_list']['create_time']->inc;
            }
            if (isset($k0)) {
                $k = $k0 + 1;
            } else {
                $k = 0;
            }
            $p = 0;
            foreach ($mess_list['result'] as $v2) {
                if ($v2['mess_list']['mess_type'] != 1) {
                    $touserinfo = $user->getUserinfoByID($v2['user_id']);
                    $fromuserinfo = $user->getUserinfoByID($v2['mess_list']['from_user_id']);
                    if (isset($v2['mess_list']['post_id'])) {
                        $postdetail = $post->getPostDetailByID($v2['mess_list']['post_id']);
                        $jsonData['list'][$p + $k]['post_id'] = $v2['mess_list']['post_id'];
                        $jsonData['list'][$p + $k]['pic_url'] = $picUtil->getImgView($postdetail['pic_list'][0]['pic_key']);
                    }
                    $jsonData['list'][$p + $k]['id'] = $v2['mess_list']['id'];
                    $jsonData['list'][$p + $k]['to_user_id'] = $v2['user_id'];
                    $jsonData['list'][$p + $k]['to_user_name'] = $touserinfo['user_nickname'];
                    $jsonData['list'][$p + $k]['from_user_id'] = $v2['mess_list']['from_user_id'];
                    $jsonData['list'][$p + $k]['user_name'] = $fromuserinfo['user_nickname'];
                    if ($fromuserinfo['user_logo']) {
                        $jsonData['list'][$p + $k]['user_logo'] = $picUtil->getUserLogo($fromuserinfo['user_logo']);
                    } else {
                        $jsonData['list'][$p + $k]['user_logo'] = NULL;
                    }
                    $jsonData['list'][$p + $k]['mess_type'] = $v2['mess_list']['mess_type'];
                    $jsonData['list'][$p + $k]['time'] = $v2['mess_list']['create_time']->sec;
                    $jsonData['list'][$p + $k]['sec'] = $v2['mess_list']['create_time']->sec;
                    $jsonData['list'][$p + $k]['inc'] = $v2['mess_list']['create_time']->inc;
                    if (isset($v2['mess_list']['mess_content'])) {
                        $content = $this->replaceHtmlstr($v2['mess_list']['mess_content']);
                        $jsonData['list'][$p + $k]['mess_content'] = "\n" . $content;
                    } else {
                        $jsonData['list'][$p + $k]['mess_content'] = "\n";
                    }
                    if (isset($v2['mess_list']['comment_id'])) {
                        $jsonData['list'][$p + $k]['commentID'] = $v2['mess_list']['comment_id'];
                    }
                    $p++;
                }
            }
            $jsonData['list'] = $this->rsort_2d($jsonData['list'], "sec");
            return array('list' => $jsonData, 'p' => $p, 'k' => $k, 'idlist' => $idlist);
        } else {
            return array('list' => array());
        }
    }

    public function getJsonDataByUseridlist($idlist, $userid) {
        $message = new message();
        foreach ($idlist as $k => $v) {
            $messlist = $message->getMessByUserid("c_user_mess", $userid, $v);
            $list[$k] = $messlist['result'][0];
        }
        return $list;
    }

}
