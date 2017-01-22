<?php /* Smarty version Smarty-3.1.19, created on 2017-01-13 11:46:21
         compiled from "/var/www/dev/web/index/tpl/share/post_share_mobile.html" */ ?>
<?php /*%%SmartyHeaderCode:177378165258775f780b3d61-53812098%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '75caed22a8fe341ac65288b8b3254153e3888a86' => 
    array (
      0 => '/var/www/dev/web/index/tpl/share/post_share_mobile.html',
      1 => 1484279161,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '177378165258775f780b3d61-53812098',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58775f780f1683_11044290',
  'variables' => 
  array (
    'title' => 0,
    'post_id' => 0,
    'post_content' => 0,
    'user_id' => 0,
    'user_name' => 0,
    'publish_time' => 0,
    'post_brief' => 0,
    'picUrlArr' => 0,
    'picurl' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58775f780f1683_11044290')) {function content_58775f780f1683_11044290($_smarty_tpl) {?><!DOCTYPE html>
<html>
    <head>
        <title>share_mobile<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <link href="/css/share_1.css" type="text/css" rel="stylesheet" media="all">
        <link href="/css/share/post_share_mobile.css" type="text/css" rel="stylesheet" media="all">
        <link rel="shortcut icon" href="/favicon.ico">
        <?php echo $_smarty_tpl->getSubTemplate ('cdn.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </head>
    <body>
        <input id = "post_id" type = "hidden" value = "<?php echo $_smarty_tpl->tpl_vars['post_id']->value;?>
" />
        <h3 class="text-center bold"><strong><?php echo $_smarty_tpl->tpl_vars['post_content']->value;?>
</strong></h3>
        <h5 class="text-center bold">
            <a href="http://www.weiou.com/user?uid=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['user_name']->value;?>
</a>
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
风陵渡口初相遇,一见杨过误终身。只恨我生君已老,断肠崖前忆故人。我走过山时，山不说话，我路过海时，海不说话，小毛驴滴滴答答，倚天剑伴我走天涯。大家都说我因为爱着杨过大侠，才在峨嵋山上出了家，其实我只是爱上了峨嵋山上的云和霞，像极了十六岁那年的烟花。<p>
            </div>
            <?php } ?>
        </div>
    </body>
</html><?php }} ?>
