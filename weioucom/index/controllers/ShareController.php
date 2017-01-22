<?php

/**
 * @author: chenxu
 * @date: 2014-11-13
 * */
class ShareController extends Weiou_Controller_Web {

    protected $picUtil = null;

    public function init() {
        parent::init();
        $this->picUtil = new Weiou_Util_Pic("jpg");
    }

    public function testAction()
    {
        //控制器方法访问测试
        echo "控制器方法访问测试";
    }

    public function imgMobileViewAction() {
        $this->postAction();

        $this->view->setNoRender();
        if ($this->_isMobile == 1) {
//            echo $this->view->render("post_mobile.html");
        } else {
            echo $this->view->render("post.html");
        }
    }

    public function postMobileViewAction() {
        $this->postAction();

        $this->view->setNoRender();
        if ($this->_isMobile == 1) {
//            echo $this->view->render("post_mobile.html");
        } else {
            echo $this->view->render("post.html");
        }
    }

    /**
     * 手机显示帖子
     * 需要参数post_id
     */
    public function postOld1Action() {
//        $this->logger->info("share/postAction");
        $post_id = $this->getParam("id", "");
        if ($post_id == "") {
            print_r("该帖子已被删除");
            return;
        }

        $postModel = new Weiou_Model_Post();
        $postDetail = $postModel->getPostDetailByID($post_id);
        $this->logger->info($postDetail);
        if (!$postDetail) {
            print_r("该帖子已被删除");
            return;
        }
//        $this->logger->info("aaaa");
        $userModel = new Weiou_Model_User();
        $likesnumber = $userModel->getLikesNumByUserID("c_user_post", $postDetail['user_id']);
        $image_width = $postDetail['image_width'];
        $image_height = $postDetail['image_height'];
        $pic_list = $postDetail['pic_list'];
        $pic_keyf = $pic_list[0];
        $pic_key = $pic_keyf['pic_key'];

//        $this->logger->info("bbb");
        $postUserInfo = $userModel->getUserinfoByID($postDetail['user_id']);
        if (isset($postDetail['formatted_address'])) {
            $text1 = '/' . $postUserInfo['user_nickname'] . '/' . $postDetail['formatted_address'];
        } else {
            $text1 = '/' . $postUserInfo['user_nickname'] . '/';
        }

        if (isset($postDetail['post_content'])) {
            $text2 = $postDetail['post_content'];
        } else {
            $text2 = "";
        }
//        $this->logger->info("ccc");
        $isAward = 0;
        $bonus = 0;
        if (isset($postDetail['isAward']) && $postDetail['isAward'] == 1) {
            $isAward = 1;
            //获取奖金金额
            $gameAwards = new Weiou_Model_GameAwards();
            $awards = $gameAwards->getAwardsByPostID($postDetail['_id']->{'$id'});
            if ($awards == 0) {
                $isAward = 0;
            } else {
                $bonus = $awards;
            }
        }

//        $this->logger->info("ddd");
        $pic_url = $this->picUtil->getWaterMarkForShare($pic_key, $text1, $text2, $image_width, $image_height);
        $user_logo_url = $this->getLogoUrl($postUserInfo['user_logo']);

        $title = "为偶出品，" . $postUserInfo['user_nickname'] . "作品。看世界，开眼界。";
        if ($postDetail['post_content'] != "") {
            $title .= $postDetail['post_content'];
        }

        $hashtagList = array();
        if (isset($postDetail['hashtag_list'])) {
            $hashtagList = $postDetail['hashtag_list'];
        }

//        $this->logger->info("eeee");
        $this->view->hashtag_list = $hashtagList;
        $this->view->title = $title;
        $this->view->isAward = $isAward;
        $this->view->bonus = $bonus;
        $this->view->post_id = $post_id;
        $this->view->post_content = str_replace("\n", "<br />", Weiou_Util_Html::htmlspecialchars($postDetail['post_content']));
//        $this->view->discribe = $postDetail['postDetail'];
        date_default_timezone_set('PRC');
        $this->view->user_logo = $user_logo_url;
        $this->view->location = $postDetail['formatted_address'];
        $this->view->user_name = $postUserInfo['user_nickname'];
        $this->view->user_id = $postDetail['user_id'];
        // $this->view->publish_time = date('Y-m-d H:i:s', (string) $postDetail['post_create_time']);
        $publish_time = date('Y-m-d H:i:s', (string) $postDetail['post_create_time']);
        $zero1 = strtotime(date('y-m-d h:i:s')); //当前时间
        $zero2 = strtotime($publish_time);  //发表时间
        // $zero2=strtotime('2015-7-10 00:01');
        if (date('Y-m-d', $zero1) != date('Y-m-d', $zero2)) {//如果不在同一天
            $this->view->publish_time = date('Y-m-d', $zero2);
        } else {//如果是同一天
            $this->view->publish_time = date('H:i:s', $zero2);
        }
//        $this->logger->info("ffffff");
        $this->view->pic_file_path = $pic_url;

        $comment = new Weiou_Model_PostComment();
        $comment_cursor = $comment->getPostCommentByPostId($post_id);
        $comment_list = $comment_cursor['result'];
        $tcomment_list = array();
        for ($i = 0; $i < count($comment_list); $i ++) {
            $c = $comment_list[$i]["comment_list"];
            $from_user = $userModel->getUserinfoByID($c['from_user_id']);
            $from_user_name = $from_user['user_nickname'];
            $from_user_logo_url = $this->getLogoUrl($from_user['user_logo']);

            $to_user = $userModel->getUserinfoByID($c['to_user_id']);
            if ($to_user == null) {
                $to_user_name = null;
            } else {
                $to_user_name = $to_user['user_nickname'];
            }
            $comment_time = strtotime(date('Y-m-d H:i:s', (string) $c['comment_time']));
            $zero3 = strtotime(date('y-m-d h:i:s')); //当前时间
            $zero4 = $comment_time;  //评论时间
            if (date('Y-m-d', $zero3) != date('Y-m-d', $zero4)) {//如果不在同一天
                $t_comment_time = date('Y-m-d', $zero4);
            } else {//如果是同一天
                $t_comment_time = date('H:i:s', $zero4);
            }

            $a = array(
                "from_user_id" => $c['from_user_id'],
                "to_user_id" => $c['to_user_id'],
                "comment_time" => $t_comment_time,
                "comment_content" => $c['comment_content'],
                "from_user_name" => $from_user_name,
                "from_user_logo" => $from_user_logo_url,
                "to_user_name" => $to_user_name
            );
            $tcomment_list[] = $a;
        }

//        $this->logger->info("ggggg");
        $this->view->comment_list = $tcomment_list;
        $postLikeModel = new Weiou_Model_PostLike();
        $good_cursor = $postLikeModel->getPostLikeByPostId($post_id);
//        $post_good = $good_cursor->getNext();
        $post_good = $good_cursor;
        $good_list = $post_good['good_list'];
        $tgood_list = array();
        for ($j = 0; $j < count($good_list); $j ++) {
            $c2 = $good_list[$j];
            $from_user = $userModel->getUserinfoByID($c2['from_user_id']);
            $from_user_name = $from_user['user_nickname'];
            $from_user_logo_url = $this->getLogoUrl($from_user['user_logo']);
            $a = array(
                "from_user_id" => $c2['from_user_id'],
                "good_time" => date('Y-m-d H:i:s', (string) $c2['good_time']),
                "from_user_name" => $from_user_name,
                "from_user_logo" => $from_user_logo_url
            );
            array_push($tgood_list, $a);
        }

//        $this->logger->info("0000");
        $this->view->good_list = $tgood_list;
        $this->view->likesnumber = $likesnumber;
//        echo $this->view->render("post.html");
    }

    private function getLogoUrl($userLogo) {
        $user_logo_url = $this->_defaultUserLogo;
        if ($userLogo != "") {
            $user_logo_url = $this->picUtil->getUserLogo($userLogo);
        }
        return $user_logo_url;
    }

    /**
     * 分享，新水印
     * @return type
     */
    public function postOld2Action() {
        $post_id = $this->getParam("id", "");
        if ($post_id == "") {
            print_r("该帖子已被删除");
            return;
        }

        $postModel = new Weiou_Model_Post();
        $postDetail = $postModel->getPostDetailByID($post_id);
        $this->logger->info($postDetail);
        if (!$postDetail) {
            print_r("该帖子已被删除");
            return;
        }
//        $this->logger->info("aaaa");
        $userModel = new Weiou_Model_User();
        $likesnumber = $userModel->getLikesNumByUserID("c_user_post", $postDetail['user_id']);
        $image_width = $postDetail['image_width'];
        $image_height = $postDetail['image_height'];
        $pic_list = $postDetail['pic_list'];
        $pic_keyf = $pic_list[0];
        $pic_key = $pic_keyf['pic_key'];

//        $this->logger->info("bbb");
        $postUserInfo = $userModel->getUserinfoByID($postDetail['user_id']);
        $text = "://" . $postUserInfo['user_nickname'];
        if (isset($postDetail['formatted_address'])) {
            $text .= "/" . $postDetail['formatted_address'];
        }
        if (isset($postDetail['post_content'])) {
            $text .= "/" . $postDetail['post_content'];
        }
//        $this->logger->info("ccc");

        $pic_url = $this->picUtil->getWaterMarkForShareNew($pic_key, $text, $image_width, $image_height);

        $isAward = 0;
        $bonus = 0;
//        if (isset($postDetail['isAward']) && $postDetail['isAward'] == 1) {
//            $isAward = 1;
//            //获取奖金金额
//            $gameAwards = new Weiou_Model_GameAwards();
//            $awards = $gameAwards->getAwardsByPostID($postDetail['_id']->{'$id'});
//            if ($awards == 0) {
//                $isAward = 0;
//            } else {
//                $bonus = $awards;
//            }
//        }
//        $this->logger->info("ddd");

        $user_logo_url = $this->getLogoUrl($postUserInfo['user_logo']);

        $title = "为偶出品，" . $postUserInfo['user_nickname'] . "作品。看世界，开眼界。";
        if ($postDetail['post_content'] != "") {
            $title .= $postDetail['post_content'];
        }

        $hashtagList = array();
        if (isset($postDetail['hashtag_list'])) {
            $hashtagList = $postDetail['hashtag_list'];
        }

//        $this->logger->info("eeee");
        $this->view->hashtag_list = $hashtagList;
        $this->view->title = $title;
        $this->view->isAward = $isAward;
        $this->view->bonus = $bonus;
        $this->view->post_id = $post_id;
        $this->view->post_content = str_replace("\n", "<br />", Weiou_Util_Html::htmlspecialchars($postDetail['post_content']));
//        $this->view->discribe = $postDetail['postDetail'];
        date_default_timezone_set('PRC');
        $this->view->user_logo = $user_logo_url;
        $this->view->location = isset($postDetail['formatted_address']) ? $postDetail['formatted_address'] : "";
        $this->view->user_name = $postUserInfo['user_nickname'];
        $this->view->user_id = $postDetail['user_id'];
        // $this->view->publish_time = date('Y-m-d H:i:s', (string) $postDetail['post_create_time']);
        $publish_time = date('Y-m-d H:i:s', (string) $postDetail['post_create_time']);
        $zero1 = strtotime(date('y-m-d h:i:s')); //当前时间
        $zero2 = strtotime($publish_time);  //发表时间
        // $zero2=strtotime('2015-7-10 00:01');
        if (date('Y-m-d', $zero1) != date('Y-m-d', $zero2)) {//如果不在同一天
            $this->view->publish_time = date('Y-m-d', $zero2);
        } else {//如果是同一天
            $this->view->publish_time = date('H:i:s', $zero2);
        }
//        $this->logger->info("ffffff");
        $this->view->pic_file_path = $pic_url;

        $comment = new Weiou_Model_PostComment();
        $comment_cursor = $comment->getPostCommentByPostId($post_id);
        $comment_list = $comment_cursor['result'];
        $tcomment_list = array();
        for ($i = 0; $i < count($comment_list); $i ++) {
            $c = $comment_list[$i]["comment_list"];
            $from_user = $userModel->getUserinfoByID($c['from_user_id']);
            $from_user_name = $from_user['user_nickname'];
            $from_user_logo_url = $this->getLogoUrl($from_user['user_logo']);

            $to_user = $userModel->getUserinfoByID($c['to_user_id']);
            if ($to_user == null) {
                $to_user_name = null;
            } else {
                $to_user_name = $to_user['user_nickname'];
            }
            $comment_time = strtotime(date('Y-m-d H:i:s', (string) $c['comment_time']));
            $zero3 = strtotime(date('y-m-d h:i:s')); //当前时间
            $zero4 = $comment_time;  //评论时间
            if (date('Y-m-d', $zero3) != date('Y-m-d', $zero4)) {//如果不在同一天
                $t_comment_time = date('Y-m-d', $zero4);
            } else {//如果是同一天
                $t_comment_time = date('H:i:s', $zero4);
            }

            $a = array(
                "from_user_id" => $c['from_user_id'],
                "to_user_id" => $c['to_user_id'],
                "comment_time" => $t_comment_time,
                "comment_content" => $c['comment_content'],
                "from_user_name" => $from_user_name,
                "from_user_logo" => $from_user_logo_url,
                "to_user_name" => $to_user_name
            );
            $tcomment_list[] = $a;
        }

//        $this->logger->info("ggggg");
        $this->view->comment_list = $tcomment_list;
        $postLikeModel = new Weiou_Model_PostLike();
        $good_cursor = $postLikeModel->getPostLikeByPostId($post_id);
//        $post_good = $good_cursor->getNext();
        $post_good = $good_cursor;
        $good_list = $post_good['good_list'];
        $tgood_list = array();
        for ($j = 0; $j < count($good_list); $j ++) {
            $c2 = $good_list[$j];
            $from_user = $userModel->getUserinfoByID($c2['from_user_id']);
            $from_user_name = $from_user['user_nickname'];
            $from_user_logo_url = $this->getLogoUrl($from_user['user_logo']);
            $a = array(
                "from_user_id" => $c2['from_user_id'],
                "good_time" => date('Y-m-d H:i:s', (string) $c2['good_time']),
                "from_user_name" => $from_user_name,
                "from_user_logo" => $from_user_logo_url
            );
            array_push($tgood_list, $a);
        }

//        $this->logger->info("0000");
        $this->view->good_list = $tgood_list;
        $this->view->likesnumber = $likesnumber;
//        echo $this->view->render("post.html");
    }

    /**
     * 分享，新页面
     * @return type
     */
    public function postAction() {

        $post_id = $this->getParam("id", "");   //origin
//        $post_id = "56cec7d0861a7f3c54ab72fc";    //测试构建一个postId  闻相如用户
        $post_id = "56b3cd75d043dc9758c429bb";    //测试构建一个postId  刘老师用户
//        $post_id = "56b28dc8861a7f3e3f8c558f";    //测试构建一个postId  刘老师用户
//        $post_id = "56cec016861a7f3c540441e3";    //测试构建一个postId  闻相如用户
//        $post_id = "54dca949d043dca86d6c1c30";    //测试构建一个postId  闻相如用户
        if ($post_id == "") {
            print_r("该帖子已被删除");
//            return;   //origin
            exit;
        }
        $postModel    = new Weiou_Model_Post();                    //包含了mysql和mongo两个数据库

        //Mongo命令：db.c_user_post.find({'_id':ObjectId("56b3cd75d043dc9758c429bb")}).pretty();
        $postDetail   = $postModel->getPostDetailByID($post_id);   //通过画报id获取postid
//        echo "<pre>";
//        print_r($postDetail);
//        exit;
        $post_content = $postDetail['post_content'];
        $post_brief   = $postDetail['brief'];
        $user_id      = $postDetail['user_id'];
        $postIdDetail = array();
        foreach ($postDetail['picList'] as $postidinfo) {
            $postIdDetail[] = $postModel->getPostDetailByID($postidinfo["postId"]);
        }
        $picUrlArr= array();                   //画报中的图片url/content存储为一个数组
        foreach ($postIdDetail as $infodetail) {   //获取每一个postid具体信息
            $info = array();                       //将数组$info作为元素存入数组picUrlArr
            if (!$infodetail) {
                print_r("postId为".$infodetail['_id']['$id']."记录不存在");
                continue;
            }
            $postListForm= new Weiou_Form_Ios_PostList();
            $postListForm->commentsSize = 4;
            $postService = new Weiou_Service_Post();

            $post = $postService->_postDetailForIos($infodetail, $postListForm);
            $text = "://" . $post['userName'];
            if (!empty($post['address'])) {
                $text .= "/" . $post['address'];
            }
            if (!empty($post['content'])) {
                $text .= "/" . $post['content'];
            }

            $image_width  = $infodetail['image_width'];
            $image_height = $infodetail['image_height'];
            $pic_key      = $infodetail['picKey'];
            $pic_url      = $this->picUtil->getWaterMarkForShareNew($pic_key, $text, $image_width, $image_height);
            $info['url']  = $pic_url;
            $info['post_content']  = $infodetail['post_content'];
            $picUrlArr[]  = $info;
            $title = "为偶出品，" . $post['userName'] . "作品。看世界，开眼界。";
            if ($post['content'] != "") {
                $title .= $post['content'];
            }
        }
        /*******我的代码****************/

        $hashtagList = array();
        if (isset($postDetail['hashtag_list'])) {
            $content = $post['content'];
            $hashtagList = $postDetail['hashtag_list'];
            foreach ($hashtagList as $h) {
                $content = str_replace("#" . $h["hashtag_name"] . "#", "<span class='hashtag weiou_click_ht' data-htId='" . $h["hashtag_id"] . "' data-htName='" . $h["hashtag_name"] . "'>" . "#" . $h["hashtag_name"] . "#" . "</span>", $content);
            }
            $post['content'] = $content;
        }

        date_default_timezone_set('PRC');

        $publish_time = date('Y-m-d H:i:s', (string) $postDetail['post_create_time']);

        $zero1 = strtotime(date('y-m-d h:i:s')); //当前时间
        $zero2 = strtotime($publish_time);  //发表时间
        if (date('Y-m-d', $zero1) != date('Y-m-d', $zero2)) {//如果不在同一天
            $publish_time = date('Y-m-d', $zero2);
        } else {//如果是同一天
            $publish_time = date('H:i:s', $zero2);
        }
        $this->view->pic_file_path = $picUrlArr;
        $post['thumbnail'] = $picUrlArr;             //画报内图片的url地址

        $post['publishTime'] = $publish_time;
        $this->view->post = $post;

        $morePicCursor = $postModel->getPostByUserID($post['userId']);

        $morePicData = $postService->_gridViewForWebDataModel($morePicCursor);
        $morePic = array();
        $row = array();
        $c = 0;
        foreach ($morePicData['list'] as $p) {
            $d = array();
            if ($p['id'] != $post_id) {
                $d['id'] = $p['id'];
                $d['thumbnail'] = $p['thumbnail'];
                $row[] = $d;
                $c ++;
                if (count($row) == 3) {
                    $morePic[] = $row;
                    $row = array();
                }
            }
            if ($c >= 6) {
                break;
            }
        }
//        $this->view->morePic = $morePic;
        $this->view->picUrlArr   = $picUrlArr;
        $this->view->post_content= $post_content;
        $this->view->user_name   = $post['userName'];
        $this->view->title       = $title;
        $this->view->post_id     = $post_id;
        $this->view->publish_time= $publish_time;
        $this->view->post_brief  = $post_brief;
        $this->view->user_id     = $user_id;
        $this->setDownloadUrl();
//        if ($this->_isMobile == 1) {         //移动端
        if (1 == 1) {                      //移动端测试使用

            $this->view->setNoRender();
//            echo $this->view->render("post_share_mobile.html");      //以前静态页面版
            echo $this->view->render("post_0121.html");
        } else {
            $this->view->setNoRender();
            echo $this->view->render("post_share.html");
        }
    }

}
