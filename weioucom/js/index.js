/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//header
$(document).on("scroll", function () {
    var toTop = $(this).scrollTop();
    if (toTop > 200) {
        $(".header").addClass("header-dark");
    } else {
        $(".header").removeClass("header-dark");
    }
});

$(document).ready(function () {
    $(".header").removeClass("header-dark");

    getData();

    /*记录点击次数AJAX*/
    $(".down_android").click(function () {
        downloadCount("Android", "web");
    });
    $(".down_ios").click(function () {
        downloadCount("iOS", "web");
    });
});

function downloadCount(platform, type) {
    $.ajax({
        url: "/system/statDownload?platform=" + platform + "&type=" + type,
        type: "POST",
        dataType: "json",
        success: function () {

        },
        error: function (e) {
            //alert("网络问题，请点击重新发送");
        }
    });
}


$(".category-list li").on("click", function () {
    $(".category .key-word").html($(this).html());
//    $("#photo-list").html("");
    getData();
});

var tab = "latest";

$("#photo-tab-hot").on("click", function () {
    if (!$(this).hasClass("on")) {
        tab = "hot";
        $(this).addClass("on");
        $("#photo-tab-latest").removeClass("on");
//        $("#photo-list").html("");
        getData();
    }
});

$("#photo-tab-latest").on("click", function () {
    if (!$(this).hasClass("on")) {
        tab = "latest";
        $(this).addClass("on");
        $("#photo-tab-hot").removeClass("on");
//        $("#photo-list").html("");
        getData();
    }
});

function getData() {
    
    var fw = $("#photo-list").width();
    var rowHeight = 320;
    if (fw === 1000) {
        rowHeight = 280;
    }
    var params = {"fw": fw, "gap": 8, "height": rowHeight, "pageSize": 30};

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
            var html = scrollDataFormat(response.data);
            $("#photo-list").html(html);
            $("img.lazy").lazyload({
//                effect: "fadeIn",
                threshold : 200,
                effectspeed : 20
            });
        }
    }, function () {
    }, "json");
}