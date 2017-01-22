<?php /* Smarty version Smarty-3.1.19, created on 2017-01-11 10:44:27
         compiled from "/var/www/dev/web/index/tpl/login/email.html" */ ?>
<?php /*%%SmartyHeaderCode:117135187058759c0b1ef804-28993324%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed0af0d50dac27bd54b784d22ba8b2ea95622799' => 
    array (
      0 => '/var/www/dev/web/index/tpl/login/email.html',
      1 => 1484018911,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '117135187058759c0b1ef804-28993324',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58759c0b272639_34030871',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58759c0b272639_34030871')) {function content_58759c0b272639_34030871($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <title>为偶-邮箱登录</title>
        <?php echo $_smarty_tpl->getSubTemplate ('css.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </head>
    <body>
        <!--header start-->
        <?php echo $_smarty_tpl->getSubTemplate ('header.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <!--header end-->
        <!--main start-->
        <div class="main">
            <div class="wrap login">
                <div class="login-wrap">
                    <div class="login-box">
                        <form name="loginForm" id="loginForm">
                            <ul>
                                <li><input type="text" placeholder="邮箱" class="form-input" name="email" id='email'/></li>
                                <li class="extra">或使用<a href="/login/mobile">手机登录</a></li>
                                <!--                            <li class="error-msg">输入的邮箱有误!</li>-->
                                <li><input type="password" placeholder="密码" class="form-input" name="password" id='password'/></li>
                                <li class="error-msg" id="error-msg-login">输入的密码有误!</li>
                                <li><input type="submit" class="btn-login" value="登录" /></li>
                                <li class="save">
                                    <label class="fl">
                                        <input type="checkbox" checked="checked" class="vm mr5" />记住我</label>
                                    <!--<a href="" class="fr">忘记密码？</a>-->
                                </li>
                                <?php echo $_smarty_tpl->getSubTemplate ('snsLogin.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

                            </ul>
                        </form>
                    </div>
                    <div class="tip">
                        没有帐户？<a href="/signup/email">马上注册</a>
                    </div>
                </div>
            </div>

            <!--main end-->
            <!--footer start-->
            <?php echo $_smarty_tpl->getSubTemplate ('footer.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            <!--footer end-->
        </div>
        <?php echo $_smarty_tpl->getSubTemplate ('js.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <script type="text/javascript">
            $(document).ready(function () {
                $("#loginForm").submit(function () {
                    $(".error-msg").removeClass("show");
                    var ajax_option = {
                        url: "/login/login", //默认是form action
                        type: "post",
                        data: {"type": 2},
                        dataType: "json",
                        beforeSerialize: function () {
                            //$("#txt2").val("java");//如：改变元素的值
                            $("#countryCodeInput").val($("#countryCode").text());
                        },
                        beforeSubmit: function () {
                            if ($("#email").val() === "") {
                                alert("请输入邮箱!");
                                return false;
                            }
                            if ($("#password").val() === "") {
                                alert("请输入密码!");
                                return false;
                            }
                            //if($("#txt1").val()==""){return false;}//如：验证表单数据是否为空
                        },
                        success: function (resp) {//表单提交成功回调函数
                            if (resp.state === 0) {
                                var href = "/user";
                                if(resp.data.callbackUrl && resp.data.callbackUrl !== ""){
                                    href = resp.data.callbackUrl;
                                }
                                location.href = href;//location.href实现客户端页面的跳转
                            } else if (resp.state === 107) {
                                $("#error-msg-login").text("账号或密码错误");
                                $("#error-msg-login").addClass("show");
                            } else if (resp.state === 103) {
                                alert("输入信息有误，请重新输入！");
                            } else {
                                alert("服务异常，请稍后再试！");
                            }
                        },
                        error: function (err) {
//                        alert("表单提交异常！" + err.msg);
                        }
                    };
                    $('#loginForm').ajaxSubmit(ajax_option);
                    return false;
                });
            });

        </script>
    </body>
</html><?php }} ?>
