<?php /* Smarty version Smarty-3.1.19, created on 2017-01-10 16:21:11
         compiled from "/var/www/dev/web/index/tpl/snsLogin.html" */ ?>
<?php /*%%SmartyHeaderCode:12313229775874997736a439-20651760%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '369e2de34bbc65d5eb7690c36021e04fdda255e9' => 
    array (
      0 => '/var/www/dev/web/index/tpl/snsLogin.html',
      1 => 1484018910,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12313229775874997736a439-20651760',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5874997736d532_74193484',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5874997736d532_74193484')) {function content_5874997736d532_74193484($_smarty_tpl) {?><li class="login-sns">
    <div>
        <div class="sns-tips">
            <span class="text">or</span>
        </div>
        <div class="btns">
            <div class="btn">
                <a href="/login/qq">
                    <div class="icon icon-qq" title="使用QQ快速登录"></div>
                </a>
            </div>
            <div class="btn">
                <a href="/login/wechat">
                    <div class="icon icon-weixin" title="使用微信扫码登录"></div>
                </a>
            </div>
            <div class="btn">
                <a href="/login/weibo">
                    <div class="icon icon-weibo" title="使用新浪微博快速登录"></div>
                </a>
            </div>
        </div>
    </div>
</li><?php }} ?>
