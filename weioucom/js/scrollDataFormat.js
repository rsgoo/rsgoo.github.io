/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
    var fwOld = $("#photo-list").width();
    $(window).resize(function () {
        var fw = $("#photo-list").width();
        if (fwOld !== fw) {

            $("#photo-list").children("div.row").each(function () {

                var n = $(this).children("div").length;

                var gap = 8;
                var rowWidth = 0;
                $(this).children("div").each(function () {
                    var w = $(this).find("img.scollPostImg").width();
                    var h = $(this).find("img.scollPostImg").height();

                    var w_new = Math.floor(w * (fw - gap * n) / (fwOld - gap * n));
                    var h_new = Math.floor(h * (fw - gap * n) / (fwOld - gap * n));

                    rowWidth += w_new;
                    $(this).find("img.scollPostImg").width(w_new);
                    $(this).find("img.scollPostImg").height(h_new);
                });
                var diff = fw - gap * n - rowWidth;
                if (diff > 0) {
                    var wT = $(this).children("div").last().find("img.scollPostImg").width();

                    $(this).children("div").last().find("img.scollPostImg").width(wT + diff);
                }

            });

            fwOld = fw;
        }
    });

});



function scrollDataFormat(data) {
    var picData = data.picData;
    var rowHeightArr = data.rowHeight;
    var rowCount = picData.length;
    var picListHtml = "";
    for (var i = 0; i < rowCount; i++) {
        var rowHeightReal = rowHeightArr[i];
        picListHtml += '<div class="row">';
        for (var j in picData[i]) {
            var pic = picData[i][j];
            var id = pic.id;
            var userId = pic.userId;
            var picWidth = pic.width;
            var picUrl = pic.thumbnail;
            var userName = pic.userName;
            var userLogo = pic.userLogo;
            if (userLogo === "") {
                userLogo = "/images/user-avatar.png";
            }
            picListHtml += '<div class="item">';
            picListHtml += '<a class="postDetail" data-pid=' + id + ' href="/post/detail?postID=' + id + '" onclick="openColorBox(this)">';
            picListHtml += '<img class="lazy post-img-bg scollPostImg" data-original="' + picUrl + '" height="' + rowHeightReal + 'px" width="' + picWidth + 'px">';
            picListHtml += '</a>';
            picListHtml += '<div class="item-info">';
            picListHtml += '<a class="user" href="/user?uid=' + userId + '" data-uid=' + userId + '>';
            picListHtml += '<img class="user-avatar" src="' + userLogo + '" alt=""/>';
            picListHtml += '<span class="user-name">' + userName + '</span>';
            picListHtml += '</a>';
            picListHtml += '<span class="favorite"></span>';
            picListHtml += '</div>';
            picListHtml += '</div>';
        }
        picListHtml += '</div>';
    }
    return picListHtml;
}