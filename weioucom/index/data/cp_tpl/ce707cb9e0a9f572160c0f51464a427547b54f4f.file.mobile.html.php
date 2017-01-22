<?php /* Smarty version Smarty-3.1.19, created on 2017-01-10 16:21:11
         compiled from "/var/www/dev/web/index/tpl/login/mobile.html" */ ?>
<?php /*%%SmartyHeaderCode:214477412058749977300e37-35303196%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ce707cb9e0a9f572160c0f51464a427547b54f4f' => 
    array (
      0 => '/var/www/dev/web/index/tpl/login/mobile.html',
      1 => 1484018911,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '214477412058749977300e37-35303196',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58749977349b04_19170941',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58749977349b04_19170941')) {function content_58749977349b04_19170941($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <title>为偶-手机登录</title>
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
                                <li class="phone">
                                    <div class="country" id="countryList">
                                        <div class="country-code" id="countryCode">+86</div>
                                        <div class="country-list">
                                            <div class="country-search"><input type="text" value="" id="countrySearchInput"/></div>
                                            <?php echo $_smarty_tpl->getSubTemplate ('countryCode_zh.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

                                        </div>
                                    </div>
                                    <input type="text" name="countryCode" value="" id="countryCodeInput" hidden='1' readonly='true'/>
                                    <input type="text" placeholder="手机" class="form-input" name="phone" id='phone'/>
                                </li>
                                <li class="extra">或使用<a href="/login/email">邮箱登录</a></li>
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
                        没有帐户？<a href="/signup/mobile">马上注册</a>
                    </div>
                </div>
            </div>
            <!--main end-->
            <!--footer start-->

            <?php echo $_smarty_tpl->getSubTemplate ('footer.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


        </div>
        <!--footer end-->
        <?php echo $_smarty_tpl->getSubTemplate ('js.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <script type="text/javascript">
            $(document).ready(function () {

                $("#loginForm").submit(function () {
                    $(".error-msg").removeClass("show");
                    var ajax_option = {
                        url: "/login/login", //默认是form action
                        type: "post",
                        data: {"type": 1},
                        dataType: "json",
                        beforeSerialize: function () {
//                            alert("表单数据序列化前执行的操作！");
                            //$("#txt2").val("java");//如：改变元素的值
                            $("#countryCodeInput").val($("#countryCode").text());
                        },
                        beforeSubmit: function () {
                            if ($("#phone").val() === "") {
                                alert("请输入手机号!");
                                return false;
                            }
                            if ($("#password").val() === "") {
                                alert("请输入密码!");
                                return false;
                            }
                            return true;
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
