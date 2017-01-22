<?php

/**
 * @Description
 * @Author jiangyuchao
 * @E-mail jiangyc0125@163.com
 * @Date 2015-1-14 上午10:29:10
 * @Version V1.0
 */
class SearchController extends Weiou_Controller_Web {
    //暂时没用
    public function searchUserAction_del() {
        $keyWords = trim($this->getParam('input'));
        $pageSize = $this->getParam('pageSize') != null ? $this->getParam('pageSize') : 100;
        $pageNum = $this->getParam('pageNum') != null ? $this->getParam('pageNum') : 1;
        $userService = new Weiou_Service_User();
        $list = $userService->searchUser($keyWords, $pageSize, $pageNum);

        $result = new Weiou_JsonModel_ResultForIos();
        $data = array();

        $data['list'] = $list;
        $result->state = 0;
        $result->data = $data;

        echo $result->toJson();
    }

    public function queryUserByNameAction_del() {
        $nickName = trim($this->getParam('name'));
        $pageSize = $this->getParam('pageSize') != null ? $this->getParam('pageSize') : 100;
        $pageNum = $this->getParam('pageNum') != null ? $this->getParam('pageNum') : 1;
        $userService = new Weiou_Service_User();

        $list = $userService->queryUserByName($nickName, $pageSize, $pageNum);

        $result = new Weiou_JsonModel_ResultForIos();
        $data = array();

        $data['list'] = $list;
        $result->state = 0;
        $result->data = $data;

        echo $result->toJson();
    }

    public function queryAutoCompleteAction_del() {
        $input = trim($this->getParam('input'));
        $json = array();
        $jsonData = array();
        if ($input) {
            $url = 'http://207.226.143.241/autoComplete.php?input=' . $input;
            $re = json_decode(file_get_contents($url, false), true);
            if ($re['state'] == 1) {
                $jsonData['list'] = $re['list'];
                $json['state'] = 0;
                $json['data'] = $jsonData;
            } else {
                $jsonData['list'] = array();
                $json['state'] = 22;
                $json['data'] = $jsonData;
            }
        } else {
            $jsonData['list'] = array();
            $json['state'] = 22;
            $json['data'] = $jsonData;
        }
        echo json_encode($json);
    }

    /**
     * 搜索地点
     */
    public function searchPlaceAction_del() {
        $lat = trim($this->getParam('lat'));
        $lng = trim($this->getParam('lng'));
        $radius = trim($this->getParam('radius')) ? trim($this->getParam('radius')) : 2000;
        $json = array();
        $jsonData = array();
        $jsonData['list'] = array();
        if ($lat && $lng) {
            $post = new Weiou_Model_Post();
            $postService = new Weiou_Service_Post();
            $cursor = $post->getNearPostsByAmapLoc('c_user_post', (float) $lat, (float) $lng, (float) $radius);
            $list = $postService->_mapViewDataModel($cursor);
            $jsonData['list'] = $list['list'];
        }
        $json['state'] = 0;
        $json['data'] = $jsonData;
        echo json_encode($json);
    }

}
