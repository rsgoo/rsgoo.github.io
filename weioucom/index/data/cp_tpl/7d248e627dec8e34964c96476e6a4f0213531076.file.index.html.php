<?php /* Smarty version Smarty-3.1.19, created on 2017-01-10 22:16:50
         compiled from "/var/www/dev/web/index/tpl/discover/index.html" */ ?>
<?php /*%%SmartyHeaderCode:21103558895874ecd28d6581-84776586%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7d248e627dec8e34964c96476e6a4f0213531076' => 
    array (
      0 => '/var/www/dev/web/index/tpl/discover/index.html',
      1 => 1484018913,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21103558895874ecd28d6581-84776586',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5874ecd290f8c0_42127801',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5874ecd290f8c0_42127801')) {function content_5874ecd290f8c0_42127801($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <?php echo $_smarty_tpl->getSubTemplate ('headMeta.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <title>为偶-发现</title>
        <?php echo $_smarty_tpl->getSubTemplate ('css.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </head>
    <body>
        <!--header start-->
        <?php echo $_smarty_tpl->getSubTemplate ('header.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <!--header end-->
        <!--main start-->
        <div class="photo-tab discover-header">
            <?php echo $_smarty_tpl->getSubTemplate ('discover/photo-tab.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        </div>
        <div class="photo-list">
            <div class="wrap" id="photo-list">

            </div>
        </div>
        <div id="photo-list-loading" class="none loading">
            <img src="/images/loadingGray.gif" />
        </div>

        <!--main end-->
        <?php echo $_smarty_tpl->getSubTemplate ('js.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

        <script type="text/javascript" src="/js/scrollDataFormat.js"></script>
        <script type="text/javascript" src="/js/discover.js"></script>
        <script type = "text/javascript">

        </script>

    </body>
</html><?php }} ?>
