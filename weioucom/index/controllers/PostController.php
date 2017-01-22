<?php

/**
 * @Description
 * @Author jiangyuchao
 * @E-mail jiangyc0125@163.com
 * @Date 2014-12-23 上午9:20:59
 * @Version V1.0
 */
class PostController extends Weiou_Controller_Web {
    /*
     * test
     */

    public function ppAction() {
        $this->view->setNoRender();
        $this->logger->info($this->params);
        $result = new Weiou_JsonModel_ResultForIos();

        echo $result->toJson();
    }

    /**
     * 分享，新页面
     * @return type
     */
    public function indexAction() {
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
        $postListForm = new Weiou_Form_Ios_PostList();
        $postListForm->commentsSize = 4;

        $postService = new Weiou_Service_Post();
        $post = $postService->_postDetailForIos($postDetail, $postListForm);


        $image_width = $postDetail['image_width'];
        $image_height = $postDetail['image_height'];
        $pic_list = $postDetail['pic_list'];
        $pic_keyf = $pic_list[0];
        $pic_key = $pic_keyf['pic_key'];

        $text = "://" . $post['userName'];
        if (isset($post['address'])) {
            $text .= "/" . $post['address'];
        }
        if (isset($post['content'])) {
            $text .= "/" . $post['content'];
        }
        $picUtil = new Weiou_Util_Pic("jpg");
        $pic_url = $picUtil->getWaterMarkForShareNew($pic_key, $text, $image_width, $image_height);

        $title = "为偶出品，" . $post['userName'] . "作品。看世界，开眼界。";
        if ($post['content'] != "") {
            $title .= $post['content'];
        }

        $hashtagList = array();
        if (isset($postDetail['hashtag_list'])) {
            $content = $post['content'];
            $hashtagList = $postDetail['hashtag_list'];
            foreach ($hashtagList as $h) {
                $content = str_replace("#" . $h["hashtag_name"] . "#", "<span class='hashtag weiou_click_ht' data-htId='" . $h["hashtag_id"] . "' data-htName='" . $h["hashtag_name"] . "'>" . "#" . $h["hashtag_name"] . "#" . "</span>", $content);
            }
            $post['content'] = $content;
        }


        $this->view->title = $title;
        $this->view->post_id = $post_id;
//        $this->view->post_content = str_replace("\n", "<br />", Weiou_Util_Html::htmlspecialchars($postDetail['post_content']));
        date_default_timezone_set('PRC');

        // $this->view->publish_time = date('Y-m-d H:i:s', (string) $postDetail['post_create_time']);
        $publish_time = date('Y-m-d H:i:s', (string) $postDetail['post_create_time']);
        $zero1 = strtotime(date('y-m-d h:i:s')); //当前时间
        $zero2 = strtotime($publish_time);  //发表时间
        // $zero2=strtotime('2015-7-10 00:01');
        if (date('Y-m-d', $zero1) != date('Y-m-d', $zero2)) {//如果不在同一天
            $publish_time = date('Y-m-d', $zero2);
        } else {//如果是同一天
            $publish_time = date('H:i:s', $zero2);
        }
        $this->view->pic_file_path = $pic_url;
        $post['thumbnail'] = $pic_url;
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

        $this->view->morePic = $morePic;

        $this->setDownloadUrl();
        if ($this->_isMobile == 1) {
            $this->view->setNoRender();
            echo $this->view->render("index_mobile.html");
        }
    }

    /**
     * 分享，新页面
     * @return type
     */
    public function index2Action() {
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

        $this->logger->info($this->params);
        $postId = $post_id;

        $postListForm = new Weiou_Form_Ios_PostList();


        $storyService = new Weiou_Service_Story();
        $doc = $storyService->storyDetail($postId);
        if ($doc) {
            $story = $storyService->_storyDetail($doc, $postListForm);

            $picCursor = $storyService->getAllPhotosByStory($postId);

//            $picList = $storyService->_formatPostForStoryDetail($picCursor, $postListForm->imgWidth);


            $this->view->story = $story;
//            $this->view->picList = $picList['list'];


            $title = "为偶出品，" . $story['userName'] . "作品。看世界，开眼界。";
            if ($story['title'] != "") {
                $title .= $story['title'];
            }

            $picList = array();


            foreach ($picCursor as $pic) {

                $image_width = $pic['image_width'];
                $image_height = $pic['image_height'];

                $pic_key = "";
                if (isset($pic['picKey'])) {
                    $pic_key = $pic['picKey'];
                } else {
                    foreach ($pic['pic_list'] as $k => $v) {
                        $pic_key = $v['pic_key'];
                        break;
                    }
                }

                $text = "://" . $story['userName'];
                if (isset($story['formatted_address'])) {
                    $text .= "/" . $story['formatted_address'];
                }
                if (isset($story['post_content'])) {
                    $text .= "/" . $story['post_content'];
                }
                $picUtil = new Weiou_Util_Pic("jpg");
                $pic_url = $picUtil->getWaterMarkForShareNew($pic_key, $text, $image_width, $image_height);

                $p = array();
                $p['picurl'] = $pic_url;
                $p['content'] = $pic['post_content'];
                $p['dt'] = date('Y-m-d', (string) $pic['post_create_time']);
                $p['address'] = $pic['formatted_address'];

                $picList[] = $p;
            }


            // $this->view->publish_time = date('Y-m-d H:i:s', (string) $postDetail['post_create_time']);
            $publish_time = date('Y-m-d', (string) $postDetail['post_create_time']);

//                $publish_time = date('Y-m-d', $zero2);
            

            $story['publishTime'] = $publish_time;

            $this->view->post = $story;
            $this->view->picList = $picList;
            $this->logger->info($picList);
        }


        $this->view->title = $title;
        $this->view->post_id = $post_id;
        $this->view->content = $postDetail['post_content'];
        date_default_timezone_set('PRC');






        $this->setDownloadUrl();
//        if ($this->_isMobile == 1) {
//            $this->view->setNoRender();
//            echo $this->view->render("index_mobile.html");
//        }
    }

    /**
     * 发布照片
     */
    public function publishAction() {
        $this->view->setNoRender();
        $myInfo = $this->getData("userinfo");
        $pickeys = trim($this->getParam('pickeys'));
        $lng = $this->getParam("lng");
        $lat = $this->getParam("lat");
        $content = $this->getParam("content");
        $isChina = $this->getParam("type", 0);

        $category = $this->getParam("category", "");
//		$myPosition = $this->getParam("myPosition");

        $result = new Weiou_JsonModel_ResultForIos();

        $this->logger->info($this->params);

        $locationAll = ":";
        if ($lng != 0 && $lat != 0) {
            $locationAll = $lat . ":" . $lng;

            //转gps，如果是中国的地址，需要转成gps
            if ($isChina == 1) {
                $gpsUtil = new Weiou_Util_GPS();
                $gps = $gpsUtil->gcj_decrypt_exact($lat, $lng);
                $locationAll = $gps['lat'] . ":" . $gps["lon"];
            }
        }
        if ($pickeys) {
            $pic_arr = explode(',', $pickeys);
            $postService = new Weiou_Service_Post();
            $picUtil = new Weiou_Util_Pic();
            $this->logger->info(count($pic_arr));
            $content_new = $content;
            $htService = new Weiou_Service_HashTag();
            $hashtagList = $htService->getHashtagIDS($content);
            $total = count($pic_arr);
            for ($i = 0; $i < count($pic_arr); $i++) {
                $this->logger->info("======" . $i . "=======");
                $picKey = $pic_arr[$i];

                if ($content) {
                    if ($total > 1) {
                        $cur_num = $i + 1;
                        $content_new = $content . '(' . $cur_num . '/' . $total . ')';
                    }
                } else {
                    $content_new = "";
                }

                if ($picKey != "null") {
                    $size = "0:0";
                    $location = $locationAll;
                    $exif = $picUtil->getImageExif($picKey);
                    $gpsFrom = 3;
                    if ($exif) {
                        $this->logger->info($exif);

                        $loc = $picUtil->getGPS($exif);
                        $this->logger->info($loc);
                        if ($loc) {
                            $location = $loc["latitude"] . ":" . $loc["longitude"];
                            $gpsFrom = 2;
                        }
                        $sizeTmp = $picUtil->getSize($exif);
                        $this->logger->info($sizeTmp);
                        if ($sizeTmp) {
                            $size = $sizeTmp["width"] . ":" . $sizeTmp["height"];
                        }
                    }

                    $postDao = new Weiou_Dao_Post();
                    $postDao->content = $content;
                    $postDao->picKey = $picKey;
                    $postDao->loc = $location;
                    $postDao->size = $size;
                    $postDao->gpsFrom = $gpsFrom;
                    $postDao->hashtagList = $hashtagList;
                    $postDao->category = $category;

                    $re = $postService->addPost_new($postDao);

                    if (!$re) {
                        $msg = "pickey:" . $picKey . ";content:" . $content_new . ";location:" . $location . ";size:" . $size . ";userID:" . $myInfo['user_id'];
                        $this->logger->error("publish failed." . $msg);
                        $result->state = 17;
                    }
                }
            }
        } else {
            $result->state = 16;
        }
        echo $result->toJson();
    }

    /**
     * 添加藏宝
     */
    public function addPostAction_del() {
        $content = trim($this->getParam('content'));
        $picKey = trim($this->getParam('picKey'));
        $location = trim($this->getParam('location'));
        $picWidth = trim($this->getParam('width'));
        $picHeight = trim($this->getParam('height'));

        $category = $this->getParam('category', "");

        $this->logger->info($_POST);

        $myinfo = $this->getData('userinfo');
        $json = array();
        $servicePost = new Weiou_Service_Post();

        $json['state'] = 0;
        $json['data'] = (object) NULL;

        if ($picKey) {
            $size = ":";
            if ($picWidth && $picHeight) {
                $size = $picWidth . ":" . $picHeight;
            }

            $postDao = new Weiou_Dao_Post();
            $postDao->content = $content;
            $postDao->picKey = $picKey;
            $postDao->loc = $location;
            $postDao->size = $size;
            $postDao->hashtagList = null;
            $postDao->category = $category;

            $re = $servicePost->addPost_new($postDao);

            if (!$re) {
                $json['state'] = 17;
            }
        } else {
            $json['state'] = 16;
        }
        echo json_encode($json);
    }

    /**
     * 发布帖子
     */
    public function addPostMultiAction_del() {
        $content = trim($this->getParam('content'));
        $pic_str = trim($this->getParam('picKeyList'));
        $point_str = $this->getParam('coorList') ? trim($this->getParam('coorList')) : "";
        $size_str = $this->getParam('imageSizeList') ? trim($this->getParam('imageSizeList')) : "";

        $category = $this->getParam('category', "");

        $myinfo = $this->getData('userinfo');

        $this->logger->info($this->params);
        $result = new Weiou_JsonModel_ResultForIos();

        if ($pic_str) {

            $this->logger->info($myinfo['user_id']);
            $this->logger->info($pic_str);
            $this->logger->info($point_str);
            $this->logger->info($size_str);
            $this->logger->info($content);
            $this->logger->info($category);

            $pic_arr = explode(',', $pic_str);
            $total = count($pic_arr);
            $point_arr = explode(',', $point_str);
            $size_arr = explode(',', $size_str);
            $servicePost = new Weiou_Service_Post();
            $content_new = $content;
            $htService = new Weiou_Service_HashTag();
            $hashtagList = $htService->getHashtagIDS($content);
            foreach ($pic_arr as $k => $v) {

                if ($content) {
                    if ($total > 1) {
                        $cur_num = $k + 1;
                        $content_new = $content . '(' . $cur_num . '/' . $total . ')';
                    }
                } else {
                    $content_new = "";
                }
                $picKey = $v;
                $location = ":";
                if ($point_arr[$k]) {
                    $location = $point_arr[$k];
                }

                $size = "0:0";
                if (isset($size_arr[$k])) {
                    $size = $size_arr[$k];
                }

                $postDao = new Weiou_Dao_Post();
                $postDao->content = $content;
                $postDao->picKey = $picKey;
                $postDao->loc = $location;
                $postDao->size = $size;
                $postDao->hashtagList = $hashtagList;
                $postDao->category = $category;

                $re = $servicePost->addPost_new($postDao);

//                $re = $servicePost->addPost_new($content_new, $picKey, $location, $size, $myinfo, $hashtagList);
                if (!$re) {
                    $msg = "pickey:" . $picKey . ";content:" . $content_new . ";location:" . $location . ";size:" . $size . ";userID:" . $myinfo['user_id'];
                    $this->logger->error("publish failed." . $msg);
                    $result->state = 17;
                }
            }
        } else {
            $result->state = 16;
        }

        echo $result->toJson();
    }

    /**
     * 添加评论
     */
    public function addCommentAction_del() {
        $postId = trim($this->getParam('postID'));
        $toUserId = $this->getParam('toUserId') ? $this->getParam('toUserId') : '';
        $content = trim($this->getParam('content'));

        $this->logger->info($this->params);

        $result = new Weiou_JsonModel_ResultForIos();
        $result->state = 18;
        if ($postId && $content) {
            try {
                $postService = new Weiou_Service_Post();
                $re = $postService->addComment($postId, $content, $toUserId);
                if ($re["state"] == 0) {
                    $result->state = 0;
                    unset($re['data']['commentTime']);
                    $result->data = $re['data'];
                }
            } catch (Exception $e) {
                $this->logger->error($e->getMessage() . "\n" . $e->getTraceAsString());
            }
        }
        echo $result->toJson();
    }

    /**
     * 帖子点赞
     */
    public function addPostLikeAction() {
        $postID = trim($this->getParam('postID'));

        $state = 0;
        $result = new Weiou_JsonModel_ResultForIos();

        if ($postID) {
            $postService = new Weiou_Service_Post();
            $state = $postService->addPostLike($postID);
        }
        $result->state = $state;
        echo $result->toJson();
    }

    /**
     * 取消帖子点赞
     */
    public function cancelPostLikesAction_del() {
        $postID = trim($this->getParam('postID'));
        $state = 0;
        $postService = new Weiou_Service_Post();
        $result = new Weiou_JsonModel_ResultForIos();
        if ($postID) {
            $state = $postService->cancelPostLikes($postID);
        }
        $result->state = $state;
        echo $result->toJson();
    }

    /**
     * 删除我的帖子
     */
    public function delMyPostByPostIDAction_del() {
        $postID = trim($this->getParam('postID'));
        $result = new Weiou_JsonModel_ResultForIos();
        if ($postID) {
            $postService = new Weiou_Service_Post();
            $re = $postService->delMyPostByPostID($postID);
            if ($re == 1) {
                $result->state = 0;
            } else {
                $result->state = 105;
            }
        } else {
            $result->state = 103;
        }
        echo $result->toJson();
    }

    /**
     * 获取评论
     */
    public function getCommentsByPostIDAction() {
        $postID = trim($this->getParam('postID'));
        $json = array();
        $jsonData = array();
        if ($postID) {
            $post = new Weiou_Model_Post();
            $commentsList = $post->getCommentsByPostId('c_post_comment', $postID);
            $postService = new Weiou_Service_Post();
            if (!empty($commentsList['result'])) {
                foreach ($commentsList['result'] as $ck => $cv) {
                    $data = $postService->_formatCommentForIos($cv);
                    $jsonData['list'][] = $data;
                }
            } else {
                $jsonData['list'] = array();
            }
            $json['state'] = 0;
            $json['data'] = $jsonData;
        } else {
            $json['state'] = 26;
            $json['data'] = (object) NULL;
        }
        echo json_encode($json);
    }

    /*
     * 
     */

    public function getLikesByPostIDAction() {
        $postID = trim($this->getParam('postID'));
        $sec = $this->getParam('sec') ? $this->getParam('sec') : 0;
        $inc = $this->getParam('inc') ? $this->getParam('inc') : 0;
        $pageType = $this->getParam('pageType') ? $this->getParam('pageType') : 'pre';
        $pageSize = $this->getParam('pageSize') ? $this->getParam('pageSize') : 50;

        $timestamp = NULL;
        if ($sec) {
            $timestamp = new MongoTimestamp($sec, $inc);
        }
        if ($timestamp == NULL) {
            $pageType = 'refresh';
        }

        $result = new Weiou_JsonModel_ResultForIos();
        $postService = new Weiou_Service_Post();
        $list = $postService->getLikesByPostID($postID, $timestamp, $pageType, $pageSize);

        $result->state = 0;
        $result->data = array(
            "list" => $list
        );
        echo $result->toJson();
    }

    /*
     * 举报
     */

    public function addReportAction() {
        $postID = trim($this->getParam('postID'));
        $userInfo = $this->getData('userinfo');
        $json = array();
        if ($postID) {
            $post = new Weiou_Model_Post();
            $postDetail = $post->getPostDetailByID($postID);
            if ($postDetail) {
                $existReport = $post->getPostReportByPostidAndUserid('c_post_report', $postID, $userInfo['user_id']);
                if ($existReport) {
                    $json['state'] = 25;
                    $json['data'] = (object) NULL;
                } else {
                    $re = $post->addPostReport('c_post_report', $postID, $userInfo['user_id']);
                    if ($re) {
                        $json['state'] = 0;
                        $json['data'] = (object) NULL;
                    } else {
                        $json['state'] = 25;
                        $json['data'] = (object) NULL;
                    }
                }
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

    public function addFavoriteAction() {
        $userInfo = $this->getData('userinfo');
        $postID = trim($this->getParam('postID'));
        $json = array();
        if ($postID) {
            $post = new Weiou_Model_Post();
            $postDetail = $post->getPostDetailByID($postID);
            if ($postDetail) {
                $favList = $post->getCollectListById('c_user_collect', $userInfo['user_collect_list']);
                if (!empty($favList['collect_list'])) {
                    $favArr = array_column($favList['collect_list'], 'post_id');
                    $isFavorited = Weiou_Utils::inArray($postID, $favArr);
                } else {
                    $isFavorited = false;
                }
                if ($isFavorited) {
                    $json['state'] = 0;
                    $json['data'] = (object) NULL;
                } else {
                    $re = $post->addCollectByID('c_user_collect', $userInfo['user_collect_list'], $postID, $postDetail['pic_list'][0]['pic_key']);
                    if ($re) {
                        $json['state'] = 0;
                        $json['data'] = (object) NULL;
                        $post->changeModifyTime('c_user_post', $postID);
                    } else {
                        $json['state'] = 20;
                        $json['data'] = (object) NULL;
                    }
                }
            } else {
                $json['state'] = 20;
                $json['data'] = (object) NULL;
            }
        } else {
            $json['state'] = 20;
            $json['data'] = (object) NULL;
        }
        echo json_encode($json);
    }

    public function cancelFavoriteAction() {
        $myinfo = $this->getData('userinfo');
        $postID = trim($this->getParam('postID'));
        $json = array();
        if ($postID) {
            $post = new Weiou_Model_Post();
            $postDetail = $post->getPostDetailByID($postID);
            if ($postDetail) {
                $re = $post->cancelCollect('c_user_collect', $myinfo['user_collect_list'], $postID);
                if ($re) {
                    $json['state'] = 0;
                    $json['data'] = (object) NULL;
                    $post->changeModifyTime('c_user_post', $postID);
                } else {
                    $json['state'] = 22;
                    $json['data'] = (object) NULL;
                }
            } else {
                $json['state'] = 22;
                $json['data'] = (object) NULL;
            }
        } else {
            $json['state'] = 22;
            $json['data'] = (object) NULL;
        }
        echo json_encode($json);
    }

    public function delCommentAction() {
        $myinfo = $this->getData("userinfo");
        $postId = trim($this->getParam('postID'));
        $commentId = trim($this->getParam('commentID'));

        $this->logger->debug("delComment, userID:" . $myinfo['user_id'] . "; postId:" . $postId . "; commentId:" . $commentId . ";");

        $result = new Weiou_JsonModel_ResultForIos();
        $result->state = 22;
        if ($postId && $commentId) {
            $postService = new Weiou_Service_Post();

            $state = $postService->delComment($postId, $commentId);

            if ($state == 0) {
                $result->state = 0;
            }
        }
        echo $result->toJson();
    }

    public function test() {
        $lat = trim($this->getParam('lat'));
        $lng = trim($this->getParam('lng'));

        if ($lat && $lng) {

            $locUtil = new Weiou_Util_Loc();

            $gpsLat = $lat;
            $gpsLng = $lng;
            $locLat = $gpsLat;
            $locLng = $gpsLng;
            $amapLat = $gpsLat;
            $amapLng = $gpsLng;

            /*
             * 国外使用gps，不需要转换
             */
            $gpsToBaiduMap = $locUtil->gpsConvBaiduMap($gpsLat, $gpsLng);
            $this->logger->info($gpsToBaiduMap);
            if ($gpsToBaiduMap['state'] == 1) {//返回0，无法解析，说明是国外的gps
                $gpsToAmap = $locUtil->gpsToAmap($gpsLat, $gpsLng);
                $this->logger->info($gpsToAmap);
                if ($gpsToAmap['state'] == 1) {
                    $amapLng = $gpsToAmap['lng'];
                    $amapLat = $gpsToAmap['lat'];
                } else {
                    $amapLng = $locLng - 0.0065;
                    $amapLat = $locLat - 0.0060;
                }
                $locLng = $gpsToBaiduMap['lng'];
                $locLat = $gpsToBaiduMap['lat'];


                $adr = $locUtil->reverseGeocodingByAmap($amapLat, $amapLng);
                print_r($adr);
            }

            echo "<hr>";
            echo "gps: " . $gpsLng . "," . $gpsLat;
            echo "<br>";
            echo "baidu: " . $locLng . "," . $locLat;
            echo "<br>";
            echo "amap: " . $amapLng . "," . $amapLat;
        }
    }

    public function detailAction() {
        $postId = $this->getParam('postID') ? $this->getParam('postID') : '';
        $commentsSize = $this->getParam('commentsSize') ? $this->getParam('commentsSize') : 30;
        $likesSize = trim($this->getParam('likesSize')) ? trim($this->getParam('likesSize')) : 9;
        $imgWidth = trim($this->getParam('width')) ? trim($this->getParam('width')) : 640;
        $imgHeight = trim($this->getParam('height')) ? trim($this->getParam('height')) : 640;

        $postListForm = new Weiou_Form_Ios_PostList();

        $result = new Weiou_JsonModel_ResultForIos();

        $postService = new Weiou_Service_Post();
        $postDetail = $postService->postDetail($postId);

        if ($postDetail) {
            $data = $postService->_postDetailForIos($postDetail, $postListForm);

//            $result->data = $data;


            $data['publishTime'] = Weiou_Util_Time::formatDatetime($data['publishTime']);
            for ($i = 0; $i < count($data['commentList']); $i++) {
                $dt = Weiou_Util_Time::formatDatetime($data['commentList'][$i]['commentTime']);
                $data['commentList'][$i]['commentTime'] = $dt;
            }


            $this->view->post = $data;
        }
//        echo $result->toJson();
    }

    //  新故事详情
    public function detail2Action() {
        $this->logger->info($this->params);
        $postId = $this->getParam("postID");

        $postListForm = new Weiou_Form_Ios_PostList();

        $postService = new Weiou_Service_Post();
        $postDetail = $postService->postDetail($postId);

        if ($postDetail["type"] == 2) {
            $storyService = new Weiou_Service_Story();
            $doc = $storyService->storyDetail($postId);
            if ($doc) {
                $data = $storyService->_storyDetail($doc, $postListForm);

                $picCursor = $storyService->getAllPhotosByStory($postId);

                $picList = $storyService->_formatPostForStoryDetail($picCursor, $postListForm->imgWidth);

//                $data['picList'] = $picList['list'];

                $this->view->story = $data;
                $this->view->picList = $picList['list'];
            }
        } else {
            if ($postDetail) {
                $data = $postService->_postDetailForIos($postDetail, $postListForm);
                $data['publishTime'] = Weiou_Util_Time::formatDatetime($data['publishTime']);
                for ($i = 0; $i < count($data['commentList']); $i++) {
                    $dt = Weiou_Util_Time::formatDatetime($data['commentList'][$i]['commentTime']);
                    $data['commentList'][$i]['commentTime'] = $dt;
                }
                $this->view->post = $data;
            }
        }
    }

    public function uploadAction() {
        
    }

    /**
     * 获取七牛上传凭证
     */
    public function getUploadTokenAction() {
        $this->view->setNoRender();
        $upToken = "";
        $userInfo = $this->getData('userinfo');
        if ($userInfo) {
            $picUtil = new Weiou_Util_Pic();
            $upToken = $picUtil->createUploadTokenForWeb($userInfo["user_id"], true);
        } else {
            $picUtil = new Weiou_Util_Pic();
            $upToken = $picUtil->createUploadTokenForWeb(null, true);
        }
        $upTokenArr = array(
            'uptoken' => $upToken
        );
        echo json_encode($upTokenArr);
    }

    public function getLocAddressAction() {
        $this->view->setNoRender();

        $lng = $this->getParam("lng");
        $lat = $this->getParam("lat");
        $result = new Weiou_JsonModel_ResultForIos();
        $locUtil = new Weiou_Util_Loc();
        $reverseGeocodeResult = $locUtil->reverseGeocodingGoogle($lat, $lng);
        if ($reverseGeocodeResult['status'] == 1) {
            $formatted_address = $reverseGeocodeResult['formatted_address'];
            $result->data = array(
                "address" => $formatted_address
            );
        } else {
            $result->state = 1;
        }
        echo $result->toJson();
    }

    public function uploadCallbackAction() {
        $this->view->setNoRender();

        $this->logger->info($this->params);

//        $key = $this->getParam("key");
        $hash = $this->getParam("hash");
        $width = $this->getParam("width");
        $height = $this->getParam("height");
        $mimeType = $this->getParam("mimeType", "");

        $ext = $this->getParam("ext", "");
        $now = new MongoTimestamp();
        $key = "img_" . $now->sec . "_" . $now->inc . $ext;

//        jpg, png, jpeg, webp, gif

        $mimeTypeArr = array(
            "image/jpeg", "image/webp", "image/png", "image/gif"
        );

        if (in_array($mimeType, $mimeTypeArr)) {
            $d = array(
                "key" => $key,
                "hash" => $hash,
                "width" => $width,
                "height" => $height
            );

            $re = array(
                "key" => $key,
                "payload" => $d
            );

            header('Content-type: application/json');
            echo json_encode($re, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            echo "error";
        }
    }

    public function getPicUrlAction() {
        $this->view->setNoRender();
        $key = $this->getParam("key");

        $picUtil = new Weiou_Util_Pic();
        $url = $picUtil->getImgView($key, 300, 300);

        $result = new Weiou_JsonModel_ResultForIos();
        $result->data = $url;

        echo $result->toJson();
    }

    public function testAction() {
        $this->view->setNoRender();
        $picKey = $this->getParam("pickey");
//        $picKey = "ljOPusmhveaaOgnhhw2SJ1yNHRf3";

        if ($picKey) {
            $picUtil = new Weiou_Util_Pic();
            $imageInfo = $picUtil->getImageInfo($picKey);
            print_r($imageInfo);
            $this->logger->info($imageInfo);
            echo "<hr>";
            $exif = $picUtil->getImageExif($picKey);
            print_r($exif);
            $this->logger->info($exif);
        } else {
            echo "no picKey";
        }
    }

}
