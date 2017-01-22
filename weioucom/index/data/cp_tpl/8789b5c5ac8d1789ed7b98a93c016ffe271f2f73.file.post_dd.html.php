<?php /* Smarty version Smarty-3.1.19, created on 2017-01-12 16:59:38
         compiled from "/var/www/dev/web/index/tpl/share/post_dd.html" */ ?>
<?php /*%%SmartyHeaderCode:21159651585876fdefcf9bc2-27044136%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8789b5c5ac8d1789ed7b98a93c016ffe271f2f73' => 
    array (
      0 => '/var/www/dev/web/index/tpl/share/post_dd.html',
      1 => 1484211574,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21159651585876fdefcf9bc2-27044136',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5876fdefe3af41_97016958',
  'variables' => 
  array (
    'title' => 0,
    'post_id' => 0,
    'post_content' => 0,
    'user_name' => 0,
    'publish_time' => 0,
    'post_brief' => 0,
    'picUrlArr' => 0,
    'picurl' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5876fdefe3af41_97016958')) {function content_5876fdefe3af41_97016958($_smarty_tpl) {?><!DOCTYPE html>
<html>
    <head>
        <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <link href="/css/share_1.css" type="text/css" rel="stylesheet" media="all">
        <link href="/css/share/share.css" type="text/css" rel="stylesheet" media="all">
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <input id = "post_id" type = "hidden" value = "<?php echo $_smarty_tpl->tpl_vars['post_id']->value;?>
" />
        <h3 class="text-center bold"><strong><?php echo $_smarty_tpl->tpl_vars['post_content']->value;?>
</strong></h3>
        <h5 class="text-center bold">
            <?php echo $_smarty_tpl->tpl_vars['user_name']->value;?>

            &nbsp;&nbsp;&nbsp;&nbsp;
            <?php echo $_smarty_tpl->tpl_vars['publish_time']->value;?>

        </h5>
        <hr />
        <div class="container">
            <span class="brief"><?php echo $_smarty_tpl->tpl_vars['post_brief']->value;?>
</span>
            <?php  $_smarty_tpl->tpl_vars['picurl'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['picurl']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['picUrlArr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['picurl']->key => $_smarty_tpl->tpl_vars['picurl']->value) {
$_smarty_tpl->tpl_vars['picurl']->_loop = true;
?>
            <div class="auto">
                <img src="<?php echo $_smarty_tpl->tpl_vars['picurl']->value['url'];?>
" class="img-responsive radius" alt="图片加载失败^_^" width="auto" height="auto" />
                <p class="margin">文字说明：<?php echo $_smarty_tpl->tpl_vars['picurl']->value['post_content'];?>
<p>
            </div>
            <?php } ?>
        </div>
        <hr/>
    </body>
</html><?php }} ?>
