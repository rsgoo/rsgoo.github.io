<?php /* Smarty version Smarty-3.1.19, created on 2017-01-10 22:16:35
         compiled from "/var/www/dev/web/index/tpl/user/index.html" */ ?>
<?php /*%%SmartyHeaderCode:9118629545874ecc3bd0b66-37448634%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '342d9af43b727214683058a8e9cc84698f508ebd' => 
    array (
      0 => '/var/www/dev/web/index/tpl/user/index.html',
      1 => 1484018913,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9118629545874ecc3bd0b66-37448634',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'userLogo' => 0,
    'userId' => 0,
    'userName' => 0,
    'isFollowed' => 0,
    'userSign' => 0,
    'postCount' => 0,
    'fanCount' => 0,
    'followCount' => 0,
    'address' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5874ecc3da99c5_21198974',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5874ecc3da99c5_21198974')) {function content_5874ecc3da99c5_21198974($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <title>个人主页</title>
        <?php echo $_smarty_tpl->getSubTemplate ('css.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </head>
    <body>
        <!--header start-->
        <?php echo $_smarty_tpl->getSubTemplate ('header.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <!--header end-->
        <!--main start-->
        <div class="wrap personal-list">
            <div class="personal-info">
                <div class="avatar"><img src="<?php if ($_smarty_tpl->tpl_vars['userLogo']->value=='') {?>/images/user-avatar.png<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['userLogo']->value;?>
<?php }?>" alt=""/></div>
                <div class="info">
                    <div class="item">
                        <?php if ($_smarty_tpl->tpl_vars['userId']->value!='') {?>
                        <span class="name"><?php echo $_smarty_tpl->tpl_vars['userName']->value;?>
</span>
                        <?php if ($_smarty_tpl->tpl_vars['isFollowed']->value==0||$_smarty_tpl->tpl_vars['isFollowed']->value==3) {?>
                        <a href="javascript:;" class="btn-focus" data-uid='<?php echo $_smarty_tpl->tpl_vars['userId']->value;?>
'>关 注</a>
                        <?php } elseif ($_smarty_tpl->tpl_vars['isFollowed']->value==1||$_smarty_tpl->tpl_vars['isFollowed']->value==2) {?>
                        <a href="javascript:;" class="btn-focus btn-focused" data-uid='<?php echo $_smarty_tpl->tpl_vars['userId']->value;?>
'>取消关注</a>
                        <?php } elseif ($_smarty_tpl->tpl_vars['isFollowed']->value==-1) {?>
                        <!--<a href="" class="btn-focus">编缉资料</a>-->
                        <?php }?>
                        <?php } else { ?>
                        <br />
                        <?php }?>
                    </div>
                    <div class="item intro">
                        <p><?php echo $_smarty_tpl->tpl_vars['userSign']->value;?>
</p>
                    </div>
                    <div class="item count">
                        <ul>
                            <li class="postCount"><span><?php echo $_smarty_tpl->tpl_vars['postCount']->value;?>
</span>帖子</li>
                            <li class="fanCount click"><span><?php echo $_smarty_tpl->tpl_vars['fanCount']->value;?>
</span>粉丝</li>
                            <li class="followCount click"><span><?php echo $_smarty_tpl->tpl_vars['followCount']->value;?>
</span>关注</li>
                            <li class="location"><?php echo $_smarty_tpl->tpl_vars['address']->value;?>
</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="personal-list-main">
                <ul id="personal-photo-list">


                </ul>
            </div>
            <div id="personal-photo-list-loading" class="none loading">
                <img src="/images/loadingGray.gif" />
            </div>
            <div id="personal-list-main_no-content" class="personal-list-main no-content none"> <!--当没有内容时显示这个div-->
                <p>TA还没有留下任何内容~~</p>
            </div>
        </div>
        <!--main end-->

        <?php echo $_smarty_tpl->getSubTemplate ('js.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <!--popup content end-->
        <script type="text/javascript">
//            $(function () {
//                $(".color-box").colorbox({inline: true});
//            });
//            

            $(".fanCount").click(function () {
                $.colorbox({
                    href: "/user/fans?uid=<?php echo $_smarty_tpl->tpl_vars['userId']->value;?>
",
                    opacity: 0.7,
//                   width : "420px",
//                   initialWidth : "500px",
                    close: "close",
                    onOpen: function () {
                        $("#cboxTopLeft").show();
                        $("#cboxTopCenter").show();
                        $("#cboxTopRight").show();
                        $("#cboxBottomLeft").show();
                        $("#cboxBottomCenter").show();
                        $("#cboxBottomRight").show();
                        $("#cboxMiddleLeft").show();
                        $("#cboxMiddleRight").show();
                    }
                });
            });

            $(".followCount").click(function () {
                $.colorbox({
                    href: "/user/following?uid=<?php echo $_smarty_tpl->tpl_vars['userId']->value;?>
",
                    opacity: 0.7,
//                   width : "420px",
//                   initialWidth : "500px",
                    close: "close",
                    onOpen: function () {
                        $("#cboxTopLeft").show();
                        $("#cboxTopCenter").show();
                        $("#cboxTopRight").show();
                        $("#cboxBottomLeft").show();
                        $("#cboxBottomCenter").show();
                        $("#cboxBottomRight").show();
                        $("#cboxMiddleLeft").show();
                        $("#cboxMiddleRight").show();
                    }
                });
            });

//            $(document).ready(function () {
//                $(".postDetail").colorbox({
//                    rel: "postDetail",
//                    title: "",
//                    current : ""
//                });
//            });



            var isLoad = 0;
            var picNoMoreData = 0;
            $(document).ready(function () {

                $("#personal-photo-list-loading").removeClass("none");
                setTimeout("getData(\"pre\")", 1000);

                $(this).scroll(function () {
                    if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
                        if (isLoad === 0 && picNoMoreData < 2) {
                            isLoad = 1;

                            $("#personal-photo-list-loading").removeClass("none");
                            setTimeout("getData(\"more\")", 1000);
                        }
                    }
                });
            });

            function getData(pageType) {
                isLoad = 1;

                var url = "/user/getPhotos";
                var params = {"pageType": pageType, "width": 300, "pageSize": 40, 'uid': "<?php echo $_smarty_tpl->tpl_vars['userId']->value;?>
"};

                if (pageType === "more") {
                    var postId = $("#personal-photo-list").children("li").last().find('.postId').text();
                    params["postID"] = postId;
                }

                ajaxLoadData(url, params, function (response) {
                    renderPic(response, pageType);
                    isLoad = 0;
                    $("#personal-photo-list-loading").addClass("none");
                }, function () {
                    isLoad = 0;
                    $("#personal-photo-list-loading").addClass("none");
                }, "html");
            }

            function renderPic(html, pageType) {
                if (pageType === "more") {
                    if (html === "") {
                        picNoMoreData += 1;
                    }
                    $("#personal-photo-list").append(html);
                } else {
                    if (html === "") {
                        picNoMoreData = 2;
                        $("#personal-list-main_no-content").removeClass("none");
                    } else {
                        $("#personal-photo-list").html(html);
                    }
                }
                
                $("img.lazy").lazyload({
//                    effect : "fadeIn",
                    threshold : 200,
                    placeholder : "/images/pic_bg_color.png"
                });
            }
        </script>

        <script type="text/javascript">

            function next() {
                $("#cboxNext").click();
            }

            function prev() {
                $("#cboxPrevious").click();
            }

//            $(function () {
//                $("img.lazy").lazyload();
//            });

        </script>
    </body>
</html>
<?php }} ?>
