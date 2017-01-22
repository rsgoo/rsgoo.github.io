<?php /* Smarty version Smarty-3.1.19, created on 2017-01-10 22:16:37
         compiled from "/var/www/dev/web/index/tpl/user/getphotos.html" */ ?>
<?php /*%%SmartyHeaderCode:3537899665874ecc55b4301-19330939%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0857deb9aa84bf257418f9dd7cdd538607598bfe' => 
    array (
      0 => '/var/www/dev/web/index/tpl/user/getphotos.html',
      1 => 1484018913,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3537899665874ecc55b4301-19330939',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'photos' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5874ecc56797f4_29333648',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5874ecc56797f4_29333648')) {function content_5874ecc56797f4_29333648($_smarty_tpl) {?>
<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['i'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['i']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['name'] = 'i';
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['photos']->value) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['i']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['i']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['i']['total']);
?>

<li>
    <a class="postDetail" href="/post/detail?postID=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['id'];?>
" onclick="openColorBox(this)">
        <img class="lazy" data-original="<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['thumbnail'];?>
">
        
        <div class="postId" style="display: none"> <?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['id'];?>
</div>
        <div class="img-count">
            <div class="bot">
                <span class="count-bot count-fav"> <?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['postLikes'];?>
 </span>
                <span class="count-bot count-comment"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['comments'];?>
</span>
            </div>

            <div class="right">
                <?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['type']==2) {?>
                <span class="count-sum count-sum-1"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['picCount'];?>
</span>
                <!--<span class="count-sum count-sum-2">232</span>-->
                <?php }?>
            </div>

        </div>
    </a>
</li>

<?php endfor; endif; ?><?php }} ?>
