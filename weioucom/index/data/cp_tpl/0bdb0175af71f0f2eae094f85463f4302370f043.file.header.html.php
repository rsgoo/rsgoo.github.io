<?php /* Smarty version Smarty-3.1.19, created on 2017-01-10 16:20:54
         compiled from "/var/www/dev/web/index/tpl/header.html" */ ?>
<?php /*%%SmartyHeaderCode:96175016587499660d8628-91966595%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0bdb0175af71f0f2eae094f85463f4302370f043' => 
    array (
      0 => '/var/www/dev/web/index/tpl/header.html',
      1 => 1484018909,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '96175016587499660d8628-91966595',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'isLogin' => 0,
    'headerIndexTag' => 0,
    'isFollowPage' => 0,
    'isDiscoverPage' => 0,
    'myUserLogo' => 0,
    'myUserName' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5874996614f065_86656589',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5874996614f065_86656589')) {function content_5874996614f065_86656589($_smarty_tpl) {?>
<div class="header header-dark">
    <div class="wrap header-main">
        <a href="/" class="logo"><img height="50" src="/images/logo.png" alt=""/></a>
        <?php if ($_smarty_tpl->tpl_vars['isLogin']->value==1&&$_smarty_tpl->tpl_vars['headerIndexTag']->value!=1) {?>
        <div class="left-nav">
            <ul>
                <li <?php if ($_smarty_tpl->tpl_vars['isFollowPage']->value==1) {?> class="on" <?php }?> ><a href="/follow">关注</a></li>
                <li <?php if ($_smarty_tpl->tpl_vars['isDiscoverPage']->value==1) {?> class="on" <?php }?> ><a href="/discover">发现</a></li>
            </ul>
        </div>
        <?php }?>
        <div class="header-r">
            <div class="menu">
                <div class="sub">
                    <ul>
                        <li><a href="/about/about_us">关于为偶</a></li>
                        <li><a href="/about/join_us">加入我们</a></li>
                        <li class="lr"><a href="/about/contacts">联系我们</a></li>
                        <li><a href="/about/terms">用户协议</a></li>
                        <li><a href="/about/privacy">隐私政策</a></li>
                    </ul>
                </div>
            </div>
            <!--<a href="">EN</a>-->
            <?php if ($_smarty_tpl->tpl_vars['isLogin']->value==1) {?>
            <a href="/post/upload" class="upload" id="post-upload" onclick="openUpload()">上传</a>
            <div class="user">

                <span class="avatar"><img src="<?php echo $_smarty_tpl->tpl_vars['myUserLogo']->value;?>
" alt=""/><?php echo $_smarty_tpl->tpl_vars['myUserName']->value;?>
</span>

                <div class="sub">
                    <ul>
                        <li class="lr"><a href="/user">我的主页</a></li>
                        <li><a href="/login/logout">退出</a></li>
                    </ul>
                </div>
            </div>
            <?php } else { ?>
            <a href="/signup">注册</a>
            <a href="/login">登录</a>
            <?php }?>
        </div>
    </div>
</div>

<?php if ($_smarty_tpl->tpl_vars['isLogin']->value==1) {?>
<script type="text/javascript">

    function closeUploadPage() {
        $('#cboxClose').click();
    }

    var uploadPageOpen = false;

    function openUpload() {
        if (uploadPageOpen) {
            return;
        }
        uploadPageOpen = true;
        $("#post-upload").colorbox({
            title: "",
            current: "",
            iframe: true,
            innerWidth: 800,
            innerHeight: 520,
            opacity: 0.7,
            overlayClose: false,
//            modalClose: "close",
            href: '/post/upload',
            onOpen: function () {
                $("#cboxTopLeft").hide();
                $("#cboxTopCenter").hide();
                $("#cboxTopRight").hide();
                $("#cboxBottomLeft").hide();
                $("#cboxBottomCenter").hide();
                $("#cboxBottomRight").hide();
                $("#cboxMiddleLeft").hide();
                $("#cboxMiddleRight").hide();
            },
            onComplete: function () {
//                $("#cboxNext").hide();
//                $("#cboxPrevious").hide();

//                $("#cboxColse").show();

                $("#cboxTopLeft").hide();
                $("#cboxTopCenter").hide();
                $("#cboxTopRight").hide();
                $("#cboxBottomLeft").hide();
                $("#cboxBottomCenter").hide();
                $("#cboxBottomRight").hide();
                $("#cboxMiddleLeft").hide();
                $("#cboxMiddleRight").hide();
            },
            onClose: function () {
                uploadPageOpen = true;
            }
        });
    }
</script>
<?php }?>
<?php }} ?>
