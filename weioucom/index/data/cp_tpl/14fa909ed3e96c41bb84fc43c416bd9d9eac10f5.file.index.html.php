<?php /* Smarty version Smarty-3.1.19, created on 2017-01-10 22:16:53
         compiled from "/var/www/dev/web/index/tpl/follow/index.html" */ ?>
<?php /*%%SmartyHeaderCode:20796795375874ecd562d606-10346689%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '14fa909ed3e96c41bb84fc43c416bd9d9eac10f5' => 
    array (
      0 => '/var/www/dev/web/index/tpl/follow/index.html',
      1 => 1484018911,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20796795375874ecd562d606-10346689',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5874ecd5664947_81317289',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5874ecd5664947_81317289')) {function content_5874ecd5664947_81317289($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <title>为偶-我的关注</title>
        <?php echo $_smarty_tpl->getSubTemplate ('css.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </head>
    <body>
        <!--header start-->
        <?php echo $_smarty_tpl->getSubTemplate ('header.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <!--header end-->
        <!--main start-->
        <div class="pic-list" id="pic-list">


        </div>
        <div id="photo-list-loading" class="none loading">
            <img src="/images/loading.gif" />
        </div>
        <div class="pic-list no-content" style="display: none;"> <!--当没有内容时显示这个div-->
            <p>TA还没有留下任何内容~~</p>
        </div>
        <!--main end-->
        <!--popup start-->
        <div style="display: none;">
            <div class="popup-content pic-detail" id="pic-detail">
                <div class="img-scroll">
                    <div class="bd">
                        <a class="prev" href="javascript:void (0);"></a>
                        <a class="next" href="javascript:void (0);"></a>
                        <ul>
                            <li><a href=""><img src="/images/pic-detail.jpg" alt=""/></a></li>
                            <li><a href=""><img src="/images/pic-detail.jpg" alt=""/></a></li>
                            <li><a href=""><img src="/images/pic-detail.jpg" alt=""/></a></li>
                        </ul>
                    </div>
                </div>
                <div class="img-detail">
                    <div class="item-top">
                        <a href="" class="user">
                            <div class="avatar"><img src="/images/user-avatar.png" alt=""/></div>
                            <div class="user-info">
                                <span class="name">SUNDAY</span>
                                <span class="time">2小时</span>
                            </div>
                        </a>
                        <div class="item-top-r">
                            <!--<a href="" class="btn-focus">+ 关注</a>-->
                            <a class="btn-focused">已关注</a>
                        </div>
                    </div>
                    <div class="item-info">
                        <div class="item-share">
                            <a href="" class="btn like"></a>
                            <a href="" class="btn reply"></a>
                            <a href="" class="btn collect"></a>
                            <div class="share-box">
                                <div class="bshare-custom"><div class="bsPromo bsPromo2"></div><a title="更多平台" class="bshare-more bshare-more-icon more-style-addthis"></a></div><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&amp;uuid=&amp;pophcol=1&amp;lang=zh"></script><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script>
                            </div>
                            <!--<div class="more">-->
                            <!--<div class="sub">-->
                            <!--<ul>-->
                            <!--<li><a href="">举报</a></li>-->
                            <!--<li><a href="">删除</a></li>-->
                            <!--</ul>-->
                            <!--</div>-->
                            <!--</div>-->
                            <a class="btn delete"></a>
                        </div>
                        <p class="location">北京市海淀区</p>
                    </div>
                    <div class="comment-list">
                        <ul>
                            <li>
                                <div class="date">11:23</div>
                                <a href="" class="user">Bryan:</a>
                                内容内容内容内容内容内容内容内容内容内容内容内容内容内
                            </li>
                            <li>
                                <a href="" class="more">查看全部评论</a>
                            </li>
                            <li>
                                <div class="date">11:23</div>
                                <a href="" class="user">Bryan</a>回复 <a href="" class="user">Peter:</a>
                                内容内容内容内容内容内容内容内容内容内容内容内容内容
                                <a href="" class="btn reply">回复</a>
                                <a href="" class="btn delete">删除</a>
                            </li>
                        </ul>
                        <div class="comment-box">
                            <textarea name="" id="" placeholder="添加评论..."></textarea>
                        </div>
                    </div>
                </div>
                <!-- popup tip start-->
                <div class="popup-content msg-tip" id="msg-delete" style="display: none;">
                    <p class="tip-1">确定要删除照片?</p>
                    <div class="btn-group">
                        <a href="" class="btn btn-cancel">否</a>
                        <a href="" class="btn btn-confirm">是</a>
                    </div>
                </div>
                <!-- popup tip end-->
            </div>
        </div>
        <!--popup end-->

        <?php echo $_smarty_tpl->getSubTemplate ('js.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


        <script type="text/javascript">
            $(function () {
                $(".color-box").colorbox({inline: true});

                //delete confirm
                $(".delete").click(function () {
                    $("#msg-delete").show();
                });


            });

            var isLoad = 0;
            var picNoMoreData = 0;
            $(document).ready(function () {

                $("#photo-list-loading").removeClass("none");
                setTimeout("getData(\"pre\")", 1000);

                $(this).scroll(function () {
                    if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                        if (isLoad === 0 && picNoMoreData < 2) {
                            isLoad = 1;

                            $("#photo-list-loading").removeClass("none");
                            setTimeout("getData(\"more\")", 1000);
                        }
                    }
                });
            });

            function getData(pageType) {
                isLoad = 1;

                var url = "/follow/getPhotos";
                var params = {"pageType": pageType, "width": 600, "pageSize": 30};

                if (pageType === "more") {
                    var postId = $("#pic-list").children("div").last().find('.postId').text();
                    params["postID"] = postId;
                }

                ajaxLoadData(url, params, function (response) {
                    renderPic(response, pageType);
                    isLoad = 0;
                    $("#photo-list-loading").addClass("none");
                }, function () {
                    isLoad = 0;
                    $("#photo-list-loading").addClass("none");
                }, "html");
            }

            function renderPic(html, pageType) {
                if (pageType === "more") {
                    if (html === "") {
                        picNoMoreData += 1;
                    }
                    $("#pic-list").append(html);
                } else {
                    if (html === "") {
                        picNoMoreData = 2;
                    }
                    $("#pic-list").html(html);
                }
                
                $("img.lazy").lazyload({
//                    effect: "fadeIn",
                    placeholder : "/images/pic_bg_color.png",
                    threshold: 200
                });
            }
        </script>
    </body>
</html>
<?php }} ?>