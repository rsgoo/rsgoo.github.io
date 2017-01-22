<?php /* Smarty version Smarty-3.1.19, created on 2017-01-11 10:20:48
         compiled from "/var/www/dev/web/index/tpl/header-about.html" */ ?>
<?php /*%%SmartyHeaderCode:23558323458759680a70f87-86865921%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9bc7e9a1aca79e8854df3403cf19203c59b7cba1' => 
    array (
      0 => '/var/www/dev/web/index/tpl/header-about.html',
      1 => 1484018911,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23558323458759680a70f87-86865921',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'active1' => 0,
    'active2' => 0,
    'active3' => 0,
    'active4' => 0,
    'active5' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58759680a9da87_48631352',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58759680a9da87_48631352')) {function content_58759680a9da87_48631352($_smarty_tpl) {?>

<div class="header header-nofixed">
    <div class="wrap header-main">
        <a href="/" class="logo"><img height="50" src="/images/logo.png" alt=""/></a>
        <div class="header-r">
            <ul class="nav-2">
                <li class="<?php echo $_smarty_tpl->tpl_vars['active1']->value;?>
"><a href="/about/about_us">关于为偶</a></li>
                <li class="<?php echo $_smarty_tpl->tpl_vars['active2']->value;?>
"><a href="/about/join_us">加入我们</a></li>
                <li class="<?php echo $_smarty_tpl->tpl_vars['active3']->value;?>
"><a href="/about/contacts">联系我们</a></li>
                <li class="<?php echo $_smarty_tpl->tpl_vars['active4']->value;?>
"><a href="/about/terms">用户协议</a></li>
                <li class="<?php echo $_smarty_tpl->tpl_vars['active5']->value;?>
"><a href="/about/privacy">隐私政策</a></li>
            </ul>
        </div>
    </div>
</div><?php }} ?>
