<?php /* Smarty version Smarty-3.1.19, created on 2017-01-10 22:16:43
         compiled from "/var/www/dev/web/index/tpl/user/userlist.html" */ ?>
<?php /*%%SmartyHeaderCode:1181967005874eccbc2f250-00551268%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd92baba6095d50c2dc51b26d2dc1eca102a7e66f' => 
    array (
      0 => '/var/www/dev/web/index/tpl/user/userlist.html',
      1 => 1484018913,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1181967005874eccbc2f250-00551268',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'dataUrl' => 0,
    'userId' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5874eccbc88a07_42450080',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5874eccbc88a07_42450080')) {function content_5874eccbc88a07_42450080($_smarty_tpl) {?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <title>pic list</title>
        <?php echo $_smarty_tpl->getSubTemplate ('css.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


    </head>
    <body>

        <div class="following-list">
            <div class="following-list-top">
                <h3><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
<span></span></h3>
            </div>
            <div class="following-list-main">
                <ul id="user-list">

                    <?php echo $_smarty_tpl->getSubTemplate ('user/getusers.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


                </ul>
            </div>
            <div id="user-list-loading" class="none loading">
                <img src="/images/loading.gif" />
            </div>
        </div>

        <?php echo $_smarty_tpl->getSubTemplate ('js.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>



        <script type="text/javascript">

            var userIsLoad = 0;
            var userNoMoreData = 0;

            $(".following-list-main").scroll(function () {
//                var scrollTop = $(this).scrollTop();
                //滚动条距离顶部的距离 >= 内部div高度-父div高度
                if ($(this).scrollTop() >= $("#user-list").height() - $(".following-list-main").height() - 400) {
                    if (userIsLoad === 0 && userNoMoreData < 2) {
                        userIsLoad = 1;
                        $("#user-list-loading").removeClass("none");
//                        $(this).scrollTop(scrollTop + $("#user-list-loading").height());
                        setTimeout("getUserList(\"more\")", 1000);
                    }
                }
            });


            function getUserList(pageType) {
                userIsLoad = 1;

                var url = "<?php echo $_smarty_tpl->tpl_vars['dataUrl']->value;?>
";
                var params = {"pageType": pageType, "pageSize": 20, 'uid': "<?php echo $_smarty_tpl->tpl_vars['userId']->value;?>
"};

                if (pageType === "more") {
                    var sec = $("#user-list").children("li").last().find('.user-sec').text();
                    var inc = $("#user-list").children("li").last().find('.user-inc').text();
                    params["sec"] = sec;
                    params["inc"] = inc;
                }

                ajaxLoadData(url, params, function (response) {
                    renderUserList(response, pageType);
                    userIsLoad = 0;
                    $("#user-list-loading").addClass("none");
                }, function () {
                    userIsLoad = 0;
                    $("#user-list-loading").addClass("none");
                }, "html");
            }

            function renderUserList(html, pageType) {
                if (html === "") {
                    userNoMoreData += 1;
                }
                if (pageType === "more") {
                    $("#user-list").append(html);
                }
            }
        </script>

    </body>
</html><?php }} ?>
