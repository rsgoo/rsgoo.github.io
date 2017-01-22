<?php /* Smarty version Smarty-3.1.19, created on 2017-01-10 16:33:14
         compiled from "/var/www/dev/web/index/tpl/signup/mobile.html" */ ?>
<?php /*%%SmartyHeaderCode:153908430858749c4ae73b53-09173667%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3b43b0f1908784cb59c9095eb6040809a868ed89' => 
    array (
      0 => '/var/www/dev/web/index/tpl/signup/mobile.html',
      1 => 1484018911,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '153908430858749c4ae73b53-09173667',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58749c4aeb99b9_77983193',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58749c4aeb99b9_77983193')) {function content_58749c4aeb99b9_77983193($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <title>为偶-手机注册</title>
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
                        <form name="signupForm" id="signupForm">
                            <ul>
                                <li class="phone">
                                    <div class="country" id="countryList">
                                        <div class="country-code" id="countryCode">+86</div>
                                        <div class="country-list">
                                            <div class="country-search"><input type="text" name="countryCode" value="" id="countryCodeInput"/></div>
                                            <?php echo $_smarty_tpl->getSubTemplate ('countryCode_zh.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

                                        </div>
                                    </div>
                                    <input type="text" placeholder="手机" class="form-input" name="phone" id="phone"/>
                                </li>
                                <li class="extra">或使用<a href="/signup/email">邮箱注册</a></li>
                                <li class="error-msg" id="error-msg-phone">输入的邮箱有误!</li>
                                <li><input type="password" placeholder="密码" name="password" class="form-input" id='password'/></li>
                                <li class="error-msg" id="error-msg-password"></li>
                                <li>
                                    <input type="text" placeholder="验证码" class="form-input form-input-short" id="vCode" name="vCode"/>
                                    <img class="yz-img" src="/system/vCode" alt="点击刷新" onclick="this.src = '/system/vCode?' + Math.random()"/>
                                </li>
                                <li class="error-msg" id="error-msg-vCode">asdf</li>
                                <li>
                                    <input type="text" placeholder="短信验证码" class="form-input form-input-short" name="verifyCode" id='verifyCode'/>
                                    <!--                                <div class="yz-call" title="" id="verification-code">点击获取</div>-->

                                    <input class="yz-call" type="button" value="点击获取"  id="verifyCodeBtn"/>

                                </li>
                                <li class="error-msg" id="error-msg-verifyCode"></li>
                                <li><input type="submit" class="btn-login" value="注册" id="signupFormBtn" /></li>
                                
                                <li class="extra">
                                    你也可以通过以下账号快速登录<br/>
                                    <a href="/login/qq">QQ</a> | 
                                    <a href="/login/wechat">微信</a> | 
                                    <a href="/login/weibo">新浪微博</a>
                                </li>
                            </ul>
                        </form>
                    </div>
                    <div class="tip">
                        <label for=""><input class="vm mr5" type="checkbox" checked="1" id='terms'/>同意<a href="/about/terms">《为偶用户协议》</a>和<a href="/about/privacy">《隐私政策》</a></label>
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

            $("#verifyCodeBtn").click(function () {
                $("#error-msg-vCode").removeClass("show");
                var countryCode = $("#countryCode").text();
                var phone = $('#phone').val();
                var vCode = $("#vCode").val();

                if (countryCode !== "" && phone !== "" && vCode !== "") {
                    var url = "/system/getSmsCode";
                    var params = {
                        "countryCode": countryCode,
                        "phone": phone,
                        "vCode": vCode
                    };
                    $.post(url, params, function (resp) {
                        if (resp.state === 1) {
                            $("#error-msg-vCode").text("验证码错误");
                            $("#error-msg-vCode").addClass("show");
                        } else if (resp.state === 0) {
                            settime($("#verifyCodeBtn"));
                        }
                    }, "json");
                } else {
                    alert("输入的信息不完整，请先完成输入");
                }
                ;
            });

//            倒计时
            var countdown = 60;
            function settime(obj) {
                if (countdown === 0) {
                    obj.removeAttr("disabled");
                    obj.val("点击获取");
                    countdown = 60;
                    return;
                } else {
                    obj.attr("disabled", true);
                    obj.val(countdown + "S");
                    countdown--;
                }
                setTimeout(function () {
                    settime(obj);
                }
                , 1000);
            }


            $("#signupForm").submit(function () {
                $(".error-msg").removeClass("show");
                var ajax_option = {
                    url: "/signup/register", //默认是form action
                    type: "post",
                    data: {'type': 1},
                    dataType: "json",
                    beforeSerialize: function () {
                        $("#countryCodeInput").val($("#countryCode").text());
                    },
                    //data:{'txt':"JQuery"},//自定义提交的数据
                    beforeSubmit: function () {
                        
                        if (!$("#terms").is(":checked")) {
                            alert("请先阅读用户协议和隐私政策，并勾选同意！");
                            return false;
                        }
                        if ($("#phone").val() === "") {
                            alert("请输入手机号!");
                            return false;
                        }
                        if ($("#password").val() === "") {
                            alert("请输入密码!");
                            return false;
                        }
                        if ($("#verifyCode").val() === "") {
                            alert("请输入短信验证码!");
                            return false;
                        }
                        
                        //if($("#txt1").val()==""){return false;}//如：验证表单数据是否为空
                    },
                    success: function (resp) {//表单提交成功回调函数
                        if (resp.state === 0) {
                            location.href = "/user";//location.href实现客户端页面的跳转
                        } else if (resp.state === 102) {
                            $("#error-msg-verifyCode").text("短信验证码错误");
                            $("#error-msg-verifyCode").addClass("show");
                        } else if (resp.state === 101) {
                            $("#error-msg-phone").text("该手机号已注册");
                            $("#error-msg-phone").addClass("show");
                        } else if(resp.state === 103) {
                            alert("输入的信息有误，请重新输入!");
                        } else {
                            alert("服务异常，请稍后再试！");
                        }
                    },
                    error: function (err) {
//                        alert("表单提交异常！" + err.msg);
                    }
                };

                $('#signupForm').ajaxSubmit(ajax_option);
                return false;
            });

        </script>

    </body>
</html><?php }} ?>
