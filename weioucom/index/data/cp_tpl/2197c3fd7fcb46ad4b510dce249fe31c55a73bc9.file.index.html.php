<?php /* Smarty version Smarty-3.1.19, created on 2017-01-13 11:42:57
         compiled from "/var/www/dev/web/index/tpl/post/index.html" */ ?>
<?php /*%%SmartyHeaderCode:186640815458784cc125cc33-25693538%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2197c3fd7fcb46ad4b510dce249fe31c55a73bc9' => 
    array (
      0 => '/var/www/dev/web/index/tpl/post/index.html',
      1 => 1484018909,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '186640815458784cc125cc33-25693538',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'post_id' => 0,
    'post' => 0,
    'myUserId' => 0,
    'morePic' => 0,
    'androidDownloadUrl' => 0,
    'iosDownloadUrl' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58784cc13ff363_44143423',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58784cc13ff363_44143423')) {function content_58784cc13ff363_44143423($_smarty_tpl) {?><!DOCTYPE html>
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
        <input id = "post_id" type = "hidden" value = "<?php echo $_smarty_tpl->tpl_vars['post_id']->value;?>
" />
        <div id = "pc-header" class="pc-header"></div>
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

                        <?php if (count($_smarty_tpl->tpl_vars['post']->value['commentList'])>$_smarty_tpl->tpl_vars['post']->value['comments']) {?>
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

            <div id="download_qr" align="center">
                <br />
                <img src="/images/ewm-img.png" width="150px"/>
                <br /><br />
                扫描二维码下载APP
            </div>

            <div class="clearfix" style="height: 30px"></div>

        </div>
        
        <div id="xuanfu_pc">
            <center>
                <div id="xuanfu_in">
                    <table width=100% style="margin-top: 5px;">
                        <tr>
                            <td width=58%<?php ?>>
                                <a href="http://www.weiou.com/">
                                    <img alt="" src="/images/share/weiou_logo_gold.png" height="40px" >
                                </a>
                            </td>
                            <td width=20% style="text_align:center;vertical-align: central;">
                                <a class="down_android" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['androidDownloadUrl']->value;?>
">
                                    <img  height=40px alt="" src="/images/share/Android.png" > 
                                </a>
                            </td>
                            <td width="2%">
                                &nbsp;
                            </td>
                            <td width=20% style="text_align:center;vertical-align: central;">
                                <a class="down_ios" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['iosDownloadUrl']->value;?>
">
                                    <img height=40px alt="" src="/images/share/IOS.png" >
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </center>
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

                $(".weiou_click_post").click(function () {
                    var postId = $(this).attr("data-postId");
                    if (postId) {

                    } else {
                        postId = $("#post_id").val();
                    }
                    window.location = "http://www.weiou.com/post?id=" + postId;
                    
                });

                $(".weiou_click_user").click(function () {
                    var userId = $(this).attr("data-userId");
                    if (userId) {
                        window.location = "http://www.weiou.com/user?id=" + userId;
                    }
                });

                /*记录点击次数AJAX*/
                $(".down_android").click(function () {
                    downloadCount("Android", "web");
                });
                $(".down_ios").click(function () {
                    downloadCount("iOS", "web");
                });
            });

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
        </script>
    </body>
</html><?php }} ?>
