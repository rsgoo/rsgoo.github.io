<?php /* Smarty version Smarty-3.1.19, created on 2017-01-12 15:25:37
         compiled from "/var/www/dev/web/index/tpl/share/post_mobile.html" */ ?>
<?php /*%%SmartyHeaderCode:108131827758772f71375667-11435170%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4cd91b664b861712ac826948d36694dac25e7b81' => 
    array (
      0 => '/var/www/dev/web/index/tpl/share/post_mobile.html',
      1 => 1484018910,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '108131827758772f71375667-11435170',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'post_id' => 0,
    'post' => 0,
    'comments' => 0,
    'myUserId' => 0,
    'morePic' => 0,
    'downloadUrl' => 0,
    'downloadPlatform' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58772f714e5e58_99150901',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58772f714e5e58_99150901')) {function content_58772f714e5e58_99150901($_smarty_tpl) {?><!DOCTYPE html>
<html>
    <head>
        <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <link href="/css/share_1.css" type="text/css" rel="stylesheet" media="all">
        <link rel="shortcut icon" href="/favicon.ico">
        <script src = "/js/libs/jquery-2.1.1.min.js"></script>
    </head>
    <body>
        <div id = "pc-header" class="pc-header" style="display: none"></div>

        <input id = "post_id" type = "hidden" value = "<?php echo $_smarty_tpl->tpl_vars['post_id']->value;?>
" />
        <div class="container-share">
            <div class="item clearfix">
                <div class="item-top">
                    <a class="user weiou_click_user" data-userId="<?php echo $_smarty_tpl->tpl_vars['post']->value['userId'];?>
">
                        <div class="avatar"><img src="<?php if ($_smarty_tpl->tpl_vars['post']->value['userLogo']=='') {?>/images/user-avatar.png<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['post']->value['userLogo'];?>
<?php }?>" alt=""/></div>
                        <div class="user-info">
                            <span class="name"><?php echo $_smarty_tpl->tpl_vars['post']->value['userName'];?>
</span>
                            <span class="time"><?php echo $_smarty_tpl->tpl_vars['post']->value['publishTime'];?>
</span>
                        </div>
                    </a>
                    <div class="item-top-r">
                        <a href="javascript:;" class="btn-focus weiou_click_user" data-userId="<?php echo $_smarty_tpl->tpl_vars['post']->value['userId'];?>
">+ 关注</a>
                        <!--                        <a href="" class="btn-focused">已关注</a>-->
                    </div>
                </div>
                <div class="item-main">
                    <img class="im" src="<?php echo $_smarty_tpl->tpl_vars['post']->value['thumbnail'];?>
" alt=""/>
                    <!--</a>-->
                    <div class="item-info">
                        <?php if ($_smarty_tpl->tpl_vars['post']->value['address']!='') {?>
                        <p class="location weiou_click_post"><?php echo $_smarty_tpl->tpl_vars['post']->value['address'];?>
</p>
                        <?php }?>
                        <div class="item-share">
                            <a class="btn like weiou_click_post <?php if ($_smarty_tpl->tpl_vars['post']->value['isLiked']==1) {?>on<?php }?>" onclick=""></a>
                            <a class="btn reply weiou_click_post"></a>
                            <a href="javascript:;" class="btn collect weiou_click_post <?php if ($_smarty_tpl->tpl_vars['post']->value['isFavorited']==1) {?>on<?php }?>"></a>
                            <a href="javascript:;" class="btn share weiou_click_post"></a>
                        </div>
                        <div class="item-count">
                            <span class="post-likes weiou_click_post"><?php echo $_smarty_tpl->tpl_vars['post']->value['postLikes'];?>
</span>
                            <span class="post-views weiou_click_post"><?php echo $_smarty_tpl->tpl_vars['post']->value['viewCount'];?>
</span>
                        </div>
                    </div>
                </div>
                <div class="comment-list">
                    <ul>
                        <?php if ($_smarty_tpl->tpl_vars['post']->value['content']!='') {?>
                        <li>
                            <!--<div class="date"><?php echo $_smarty_tpl->tpl_vars['post']->value['publishTime'];?>
</div>-->
                            <a class="user weiou_click_user" data-userId="<?php echo $_smarty_tpl->tpl_vars['post']->value['userId'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['userName'];?>
</a> : 
                            <?php echo $_smarty_tpl->tpl_vars['post']->value['content'];?>

                        </li>
                        <?php }?>

                        <?php if (count($_smarty_tpl->tpl_vars['post']->value['commentList'])>$_smarty_tpl->tpl_vars['comments']->value) {?>
                        <li>
                            <a class="more weiou_click_post">查看全部评论</a>
                        </li>
                        <?php }?>

                        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['j'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['j']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['name'] = 'j';
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['post']->value['commentList']) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total']);
?>
                        <li>
                            <!--<div class="date"><?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['commentTime'];?>
 </div>-->
                            <a class="user weiou_click_user" data-userId="<?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['fromUserId'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['fromUserName'];?>
</a>
                            <?php if ($_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['toUserId']!='') {?>
                            回复
                            <a class="user weiou_click_user" data-userId="<?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['toUserId'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['toUserName'];?>
</a>
                            <?php }?>
                            : 
                            <?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['commentContent'];?>

                            <!--                            <a href="" class="btn reply">回复</a>
                                                        <?php if ($_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['fromUserId']==$_smarty_tpl->tpl_vars['myUserId']->value) {?>
                                                        <a href="" class="btn delete">删除</a>
                                                        <?php }?>-->
                        </li>
                        <?php endfor; endif; ?>
                    </ul>
                    <div class="comment-box weiou_click_post">
                        添加评论...
                    </div>
                </div>
                <div class="item-footer">
                    <div class="userinfo">
                        来自 <a class="user weiou_click_user" data-userId="<?php echo $_smarty_tpl->tpl_vars['post']->value['userId'];?>
"><?php echo $_smarty_tpl->tpl_vars['post']->value['userName'];?>
</a> 的更多照片
                    </div>
                    <div class="more-pic">
                        <table>
                            <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['i'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['i']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['name'] = 'i';
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['morePic']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total']);
?>
                            <tr>
                                <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['j'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['j']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['name'] = 'j';
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['morePic']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total']);
?>
                                <td>
                                    <!--<img class="weiou_click_post" data-postId="<?php echo $_smarty_tpl->tpl_vars['morePic']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['id'];?>
" width="100%" src="<?php echo $_smarty_tpl->tpl_vars['morePic']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['thumbnail'];?>
" />-->
                                    <a href="/post?id=<?php echo $_smarty_tpl->tpl_vars['morePic']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['id'];?>
">
                                        <img width="100%" src="<?php echo $_smarty_tpl->tpl_vars['morePic']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['thumbnail'];?>
" />
                                    </a>
                                </td>
                                <?php endfor; endif; ?>

                            </tr>
                            <?php endfor; endif; ?>
                        </table>
                    </div>
                </div>

            </div>

            <div class="clearfix" style="height: 70px"></div>
        </div>

        <div id="footer" class="footer-share">
            <div class="footer-content">
                <a href="http://www.weiou.com/">
                    <div class="logo">
                        <img src="/images/share/icLauncher.png"/>
                    </div>
                    <div class="footer-info">
                        <span class="name-zh">为偶-看世界</span>
                        <span class="name-en">weiou.inc</span>
                        <span class="slogan">发现缤纷世界，分享美丽感受</span>
                    </div>
                </a>
                <a class="download" href="<?php echo $_smarty_tpl->tpl_vars['downloadUrl']->value;?>
" title="" data-type="<?php echo $_smarty_tpl->tpl_vars['downloadPlatform']->value;?>
">
                    <div class="footer-r">
                        立即下载
                    </div>
                </a>
            </div>
        </div>

        <div id="prompt">
            <div id="browser_open" style="display: none">
                <img height=60px alt="" src="/images/share/browser_open.png" style="text-align: right; margin-right: 5px;">
            </div>
            <div id="safari_open" style="display: none">
                <img height=60px alt="" src="/images/share/safari_open.png" style="text-align: right; margin-right: 5px;">
            </div>
        </div>


        <script type="text/javascript" >
            $(document).ready(function () {
                $('#prompt').click(function () {
                    document.getElementById("prompt").style.display = "none";
                });

                $(".weiou_click_post").click(function () {
                    var postId = $(this).attr("data-postId");
                    if (postId) {
                        openWeiou(1, postId);
                    } else {
                        openWeiou();
                    }
                });

                $(".weiou_click_user").click(function () {
                    var userId = $(this).attr("data-userId");
                    if (userId) {
                        openWeiou(2, userId);
                    }
                });

                $(".weiou_click_ht").click(function () {
                    var htId = $(this).attr("data-htId");
                    var htName = $(this).attr("data-htName");
                    if (htId && htName) {
                        openWeiou(3, htId, htName);
                    }
                });

                /*记录点击次数AJAX*/
                $(".download").click(function () {
                    var platform = $(".download").attr("data-platform");
                    downloadCount(platform, "mobile");
                });

            });

            function check() {//检测是否微信或QQ打开
                var u = navigator.userAgent;
                if (u.indexOf("MicroMessenger") > -1 || u.indexOf("QQ\/") > -1) {
                    document.getElementById("prompt").style.display = "block";

                    if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
                        document.getElementById("browser_open").style.display = "block";
                    } else if (u.indexOf('iPhone') > -1 || u.indexOf('iPad') > -1 || u.indexOf('iPod') > -1) {
                        document.getElementById("safari_open").style.display = "block";
                    }

                    return false;
                } else {
                    return true;
                }
            }

            function downloadCount(platform, type) {
                $.ajax({
                    url: "/system/statDownload?platform=" + platform + "&type=" + type,
                    type: "POST",
                    dataType: "json",
                    success: function () {

                    },
                    error: function (e) {
                        //alert("网络问题，请点击重新发送");
                    }
                });
            }

            function openWeiou(type, id, name) {
                if (!check()) {
                    return;
                }
                var u = navigator.userAgent;
                if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
                    var timeout, t = 1000, hasApp = true;
                    setTimeout(function () {
                        if (hasApp) {
                            //alert('安装了app');
                        } else {
                            //alert('未安装app');
                            window.location = "http://www.weiou.com/a/";
                        }
                    }, 2000);

                    var t1 = Date.now();
                    var post_id = $("#post_id").val();
                    window.location = 'weiou://data/' + post_id;
                    timeout = setTimeout(function () {
                        var t2 = Date.now();
                        if (!t1 || t2 - t1 < t + 100) {
                            hasApp = false;
                        }
                    }, t);

                } else if (u.indexOf('iPhone') > -1 || u.indexOf('iPad') > -1 || u.indexOf('iPod') > -1) {//苹果手机
                    var timeout, t = 1000, hasApp = true;
                    setTimeout(function () {
                        if (hasApp) {
                        } else {
                            window.location = "itms-apps://itunes.apple.com/app/id1023094349";
                        }
                    }, 2000);

                    var t1 = Date.now();
                    if (!type) {
                        type = 1;
                        id = $("#post_id").val();
                    }
                    if (type === 1 || type === 2) {
                        window.location = 'com.weiou.weiou://openwith?' + type + ':' + id;
                    } else if (type === 3) {
                        window.location = 'com.weiou.weiou://openwith?' + type + ':' + id + ':' + name + ":2";
                    }
                    timeout = setTimeout(function () {
                        var t2 = Date.now();
                        if (!t1 || t2 - t1 < t + 100) {
                            hasApp = false;
                        }
                    }, t);
                }
            }
        </script>
    </body>
</html><?php }} ?>
