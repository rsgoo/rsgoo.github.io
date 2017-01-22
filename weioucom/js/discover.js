/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(".category-list li").on("click", function () {
    $(".category .key-word").html($(this).html());
    $("#photo-list").html("");
    $("#photo-list-loading").removeClass("none");
    setTimeout("getData(\"pre\")", 1000);
});

var tab = "latest";

$("#photo-tab-hot").on("click", function () {
    if (!$(this).hasClass("on")) {
        tab = "hot";
        $(this).addClass("on");
        $("#photo-tab-latest").removeClass("on");
        $("#photo-list").html("");
        $("#photo-list-loading").removeClass("none");
        setTimeout("getData(\"pre\")", 1000);
    }
});

$("#photo-tab-latest").on("click", function () {
    if (!$(this).hasClass("on")) {
        tab = "latest";
        $(this).addClass("on");
        $("#photo-tab-hot").removeClass("on");
        $("#photo-list").html("");
        $("#photo-list-loading").removeClass("none");
        setTimeout("getData(\"pre\")", 1000);
    }
});


var isLoad = 0;
var picNoMoreData = 0;
var lastPostId = "";
$(document).ready(function () {

    $("#photo-list-loading").removeClass("none");
    setTimeout("getData(\"pre\")", 1000);

    $(this).scroll(function () {
        if ($(document).scrollTop() >= $(document).height() - $(window).height()) {
            if (isLoad === 0) {
                isLoad = 1;

                $("#photo-list-loading").removeClass("none");
                setTimeout("getData(\"more\")", 1000);
            }
        }
    });
});

function getData(pageType) {

    var fw = $("#photo-list").width();
    var rowHeight = 320;
    if (fw === 1000) {
        rowHeight = 280;
    }

    isLoad = 1;
    var params = {"fw": fw, "pageType": pageType, "gap": 8, "height": rowHeight, "pageSize": 60};

    if (pageType === "more") {
        params["postID"] = lastPostId;
    }

    var category = $(".category .key-word").find("span").attr("data-value");
    if (category !== "" && category !== "all") {
        params["category"] = category;
    }

    var url = "/index/getLatestPostsForScrollView";

    if (tab === "latest") {
        url = "/index/getLatestPostsForScrollView";
    } else if (tab === "hot") {
        url = "/index/gethottestPostsForScrollView";
    }

    ajaxLoadData(url, params, function (response) {
        if (response.state === 0) {
            var picData = response.data.picData;
            if (picData.length > 0) {
                var lastRow = picData[picData.length - 1];
                if (lastRow.length > 0) {
                    var lastPost = lastRow[lastRow.length - 1];
                    lastPostId = lastPost.id;
                }
            }
            var html = scrollDataFormat(response.data);
            if (pageType === "more") {
                if (html === "") {
                    picNoMoreData += 1;
                }
                $("#photo-list").append(html);
            } else {
                if (html === "") {
                    picNoMoreData = 2;
                }

                $("#photo-list").html(html);
            }

            $("img.lazy").lazyload({
//                effect: "fadeIn",
                threshold : 200,
                effectspeed : 0,
                placeholder : "/images/pic_bg_color.png"
            });
        }
        isLoad = 0;
        $("#photo-list-loading").addClass("none");
    }, function () {
        isLoad = 0;
        $("#photo-list-loading").addClass("none");
    }, "json");
}
