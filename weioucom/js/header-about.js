
function getAboutHeader(act) {
    
    var active1="",active2="",active3="",active4="",active5="";
    if(act === 1){
        active1 = "class=\"active\"";
    }else if(act === 2){
        active2 = "class=\"active\"";
    }else if(act === 3){
        active3 = "class=\"active\"";
    }else if(act === 4){
        active4 = "class=\"active\"";
    }else if(act === 5){
        active5 = "class=\"active\"";
    }
    
//    var header2 = '<a class="header-menu" href="http://www.weiou.com">首页</a>\n'
//                  +' <span>　|　</span>\n'
//                  +'  <a class="header-menu '+active1+' " href="http://www.weiou.com/about/about_us.html">关于我们</a>\n'
//                  +'  <span>　|　</span>\n'
//                  +'  <a class="header-menu '+active2+'" href="http://www.weiou.com/about/join_us.html">加入我们</a>\n'
//                  +'  <span>　|　</span>\n'
//                  +'  <a class="header-menu '+active3+'" href="mailto:info@weiou.com">联系我们</a>';
          
          
    var header = '<div class="header header-nofixed">' 
            + '<div class="wrap header-main">'
            + '<a href="/" class="logo"><img src="/images/logo.png" alt=""/></a>'
            + '<div class="header-r">'
            + '<ul class="nav-2">'
                + '<li '+active1+'><a href="/about/about_us">关于为偶</a></li>'
                + '<li '+active2+'><a href="/about/join_us">加入我们</a></li>'
                + '<li '+active3+'><a href="/about/contacts">联系我们</a></li>'
                + '<li '+active4+'><a href="/about/terms">用户协议</a></li>'
                + '<li '+active5+'><a href="/about/privacy">隐私政策</a></li>'
            + '</ul>'
            + '</div>'
            + '</div>'
        + '</div>';
          
    return header;

}
