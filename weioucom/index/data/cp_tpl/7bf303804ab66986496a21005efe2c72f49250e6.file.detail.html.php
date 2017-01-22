<?php /* Smarty version Smarty-3.1.19, created on 2017-01-11 09:02:43
         compiled from "/var/www/dev/web/index/tpl/post/detail.html" */ ?>
<?php /*%%SmartyHeaderCode:110454516587584331b24d0-93074625%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7bf303804ab66986496a21005efe2c72f49250e6' => 
    array (
      0 => '/var/www/dev/web/index/tpl/post/detail.html',
      1 => 1484018909,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '110454516587584331b24d0-93074625',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'post' => 0,
    'myUserId' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58758433354554_40740019',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58758433354554_40740019')) {function content_58758433354554_40740019($_smarty_tpl) {?>

<div class="popup-content pic-detail" id="pic-detail">
    <div class="img-scroll">
        <div class="bd">
            <a class="prev" id="post-prev" onclick="prev(this)"></a>
            <a class="next" id="post-next" onclick="next(this)"></a>
            <ul>
                <li><img src="<?php echo $_smarty_tpl->tpl_vars['post']->value['thumbnail'];?>
" alt=""/></li>
            </ul>
        </div>
    </div>
    <div class="img-detail">
        <div class="item-top">
            <a href="/user?uid=<?php echo $_smarty_tpl->tpl_vars['post']->value['userId'];?>
" class="user">
                <div class="avatar"><img src="<?php if ($_smarty_tpl->tpl_vars['post']->value['userLogo']=='') {?>/images/user-avatar.png<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['post']->value['userLogo'];?>
<?php }?>" alt=""/></div>
                <div class="user-info">
                    <span class="name"><?php echo $_smarty_tpl->tpl_vars['post']->value['userName'];?>
</span>
                    <span class="time"><?php echo $_smarty_tpl->tpl_vars['post']->value['publishTime'];?>
</span>
                </div>
            </a>
            <div class="item-top-r">
                <!--<a href="" class="btn-focus">+ 关注</a>-->
                <!--<a class="btn-focused">已关注</a>-->
            </div>
        </div>
        <div class="item-info">
            <div class="item-share">
                <a href="javascript:;" class="btn like <?php if ($_smarty_tpl->tpl_vars['post']->value['isLiked']==1) {?>on<?php }?>" onclick=""></a>
                <a href="javascript:;" class="btn reply"></a>
                <a href="javascript:;" class="btn collect <?php if ($_smarty_tpl->tpl_vars['post']->value['isFavorited']==1) {?>on<?php }?>"></a>

                <!--                <div class="share-box">
                                    <div class="bshare-custom"><div class="bsPromo bsPromo2"></div><a title="更多平台" class="bshare-more bshare-more-icon more-style-addthis"></a></div><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/buttonLite.js#style=-1&amp;uuid=&amp;pophcol=1&amp;lang=zh"></script><script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script>
                                </div>-->

                <div class="more">
                    <div class="sub">
                        <ul>
                            <?php if ($_smarty_tpl->tpl_vars['post']->value['userId']!=$_smarty_tpl->tpl_vars['myUserId']->value) {?>
                            <li><a href="javascript:;">举报</a></li>
                            <?php } else { ?>
                            <li><a href="javascript:;">删除</a></li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['post']->value['userId']==$_smarty_tpl->tpl_vars['myUserId']->value) {?>
                <!--<a href="javascript:;" class="btn delete"></a>-->
                <?php }?>
            </div>
            <div class="item-count">
                <span class="post-likes"><?php echo $_smarty_tpl->tpl_vars['post']->value['postLikes'];?>
</span>
                <span class="post-views"><?php echo $_smarty_tpl->tpl_vars['post']->value['viewCount'];?>
</span>
            </div>
            <?php if ($_smarty_tpl->tpl_vars['post']->value['address']!='') {?>
            <p class="location"><?php echo $_smarty_tpl->tpl_vars['post']->value['address'];?>
</p>
            <?php }?>
        </div>
        <div class="comment-list">
            <ul>
                <?php if ($_smarty_tpl->tpl_vars['post']->value['content']!='') {?>
                <li>
                    <div class="date"><?php echo $_smarty_tpl->tpl_vars['post']->value['publishTime'];?>
</div>
                    <a href="/user?uid=<?php echo $_smarty_tpl->tpl_vars['post']->value['userId'];?>
" class="user"><?php echo $_smarty_tpl->tpl_vars['post']->value['userName'];?>
</a> : 
                    <?php echo $_smarty_tpl->tpl_vars['post']->value['content'];?>

                </li>
                <?php }?>

                <?php if (count($_smarty_tpl->tpl_vars['post']->value['commentList'])>$_smarty_tpl->tpl_vars['post']->value['comments']) {?>
                <li>
                    <a href="javascript:;" class="more">查看全部评论</a>
                </li>
                <?php }?>

                <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['j'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['j']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['name'] = 'j';
$_smarty_tpl->tpl_vars['smarty']->value['section']['j']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['post']->value['commentList']) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
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
                    <div class="date"><?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['commentTime'];?>
 </div>
                    <a href="/user?uid=<?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['fromUserId'];?>
" class="user"><?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['fromUserName'];?>
</a>
                    <?php if ($_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['toUserId']!='') {?>
                    回复 <a href="/user?uid=<?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['toUserId'];?>
" class="user"><?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['toUserName'];?>
</a>
                    <?php }?>
                    : 
                    <?php echo $_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['commentContent'];?>

                    <a href="" class="btn reply">回复</a>
                    <?php if ($_smarty_tpl->tpl_vars['post']->value['commentList'][$_smarty_tpl->getVariable('smarty')->value['section']['j']['index']]['fromUserId']==$_smarty_tpl->tpl_vars['myUserId']->value) {?>
                    <a href="" class="btn delete">删除</a>
                    <?php }?>
                </li>
                <?php endfor; endif; ?>
            </ul>
            <div class="comment-box">
                <textarea name="" id="a" placeholder="添加评论..."></textarea>
            </div>
        </div>
    </div>
    <!-- popup tip start-->
    <div class="popup-content msg-tip" id="msg-delete" style="display: none;">
        <p class="tip-1">确定要删除照片?</p>
        <div class="btn-group">
            <a href="javascript:;" class="btn btn-cancel">否</a>
            <a href="javascript:;" class="btn btn-confirm">是</a>
        </div>
    </div>
    <!-- popup tip end-->
</div>


<script type="text/javascript">



    function next() {
        $("#cboxNext").click();

//        window.parent.next();
    }

    function prev() {
        $("#cboxPrevious").click();
//        window.parent.prev();
    }

</script><?php }} ?>
