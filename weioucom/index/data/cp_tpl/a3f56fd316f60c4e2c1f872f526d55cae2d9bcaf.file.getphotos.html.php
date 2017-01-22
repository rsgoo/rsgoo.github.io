<?php /* Smarty version Smarty-3.1.19, created on 2017-01-10 22:17:00
         compiled from "/var/www/dev/web/index/tpl/follow/getphotos.html" */ ?>
<?php /*%%SmartyHeaderCode:912145555874ecdc19c717-54877351%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a3f56fd316f60c4e2c1f872f526d55cae2d9bcaf' => 
    array (
      0 => '/var/www/dev/web/index/tpl/follow/getphotos.html',
      1 => 1484018911,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '912145555874ecdc19c717-54877351',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'photos' => 0,
    'myUserId' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5874ecdc2b1c81_88403361',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5874ecdc2b1c81_88403361')) {function content_5874ecdc2b1c81_88403361($_smarty_tpl) {?>
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

<div class="item clearfix">
    <div class="postId" style="display: none"> <?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['id'];?>
</div>
    <div class="item-top">
        <a href="/user?uid=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['userId'];?>
" class="user">
            <div class="avatar"><img src="<?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['userLogo']=='') {?>/images/user-avatar.png<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['userLogo'];?>
<?php }?>" alt=""/></div>
            <div class="user-info">
                <span class="name"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['userName'];?>
</span>
                <span class="time"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['publishTime'];?>
</span>
            </div>
        </a>
<!--        <div class="item-top-r">
            <a href="" class="btn-focus">+ 关注</a>
            <a href="" class="btn-focused">已关注</a>
        </div>-->
    </div>
    <div class="item-main">
        <!--<a href="#pic-detail" class="color-box">-->
            <img class="lazy" height="<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['imageHeight'];?>
" data-original="<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['thumbnail'];?>
" alt=""/>
        <!--</a>-->
        <div class="item-info">
            <?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['address']!='') {?>
            <p class="location"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['address'];?>
</p>
            <?php }?>
            <div class="item-share">
                <a href="javascript:;" class="btn like <?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['isLiked']==1) {?>on<?php }?>" onclick=""></a>
                <a href="javascript:;" class="btn reply"></a>
                <a href="javascript:;" class="btn collect <?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['isFavorited']==1) {?>on<?php }?>"></a>
<!--                <div class="share-box">
                    <div class="bshare-custom"><div class="bsPromo bsPromo2"></div><a title="更多平台" class="bshare-more bshare-more-icon more-style-addthis"></a></div><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&amp;uuid=&amp;pophcol=1&amp;lang=zh"></script><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script>
                </div>-->
                <div class="more">
                    <div class="sub">
                        <ul>
                            <?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['userId']!=$_smarty_tpl->tpl_vars['myUserId']->value) {?>
                            <li><a href="javascript:;">举报</a></li>
                            <?php } else { ?>
                            <li><a href="javascript:;">删除</a></li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="item-count">
                <span class="post-likes"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['postLikes'];?>
</span>
                <span class="post-views"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['viewCount'];?>
</span>
            </div>
        </div>
    </div>
    <div class="comment-list">
        <ul>
            <?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['content']!='') {?>
            <li>
                <div class="date"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['publishTime'];?>
</div>
                <a href="/user?uid=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['userId'];?>
" class="user"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['userName'];?>
</a> : 
                <?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['content'];?>

            </li>
            <?php }?>
            
            <?php if (count($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['commentList'])>$_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['comments']) {?>
            <li>
                <a href="" class="more">查看全部评论</a>
            </li>
            <?php }?>
            
            <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['j'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['j']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['name'] = 'j';
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['commentList']) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['j']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['j']['total']);
?>
            <li>
                <div class="date"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['commentTime'];?>
 </div>
                <a href="/user?uid=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['fromUserId'];?>
" class="user"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['fromUserName'];?>
</a>
                <?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['toUserId']!='') {?>
                回复 <a href="/user?uid=<?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['toUserId'];?>
" class="user"><?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['toUserName'];?>
</a>
                <?php }?>
                : 
                <?php echo $_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['commentContent'];?>

                <a href="" class="btn reply">回复</a>
                <?php if ($_smarty_tpl->tpl_vars['photos']->value[$_smarty_tpl->getVariable('smarty')->value['section']['i']['index']]['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['fromUserId']==$_smarty_tpl->tpl_vars['myUserId']->value) {?>
                <a href="" class="btn delete">删除</a>
                <?php }?>
            </li>
            <?php endfor; endif; ?>
        </ul>
        <div class="comment-box">
            <textarea name="bb" id="t" placeholder="添加评论..."></textarea>
        </div>
    </div>
</div>

<?php endfor; endif; ?><?php }} ?>
