$(function () {

    //login-phone
    $("#countryCode").on("click", function () {
        $(this).toggleClass("expand");
        $(".country-list").toggle();
    });

    $(".country-list li").on("click", function () {
        var num = $(this).find("span").attr("data-num");
        $("#countryCode").text(num).removeClass("expand");
        $(".country-list").hide();
    });

    $("#countrySearchInput").bind('input propertychange', function () {
        var searchKey = $("#countrySearchInput").val();
        $("#countryCodeList").children("li").each(function () {
            var text = $(this).text();
            if (text.indexOf(searchKey) >= 0) {
                $(this).removeClass("hide");
            } else {
                $(this).addClass("hide");
            }
        });
    });

    $(".job-list h3").click(function () {
        $(this).parent("li").toggleClass("unfold");
    });

    //profile edit
    $(".addr-item .selected").click(function () {
        $(this).parent(".addr-item").toggleClass("unfold");
        $(this).parent(".addr-item").find(".select-list").toggle();
    });
    $(".addr-item .select-list li").click(function () {
        var val = $(this).attr("data-val");
        $(this).parents(".addr-item").find(".selected").text(val);
        $(this).parents(".select-list").hide();
        $(this).parents(".addr-item").removeClass("unfold");
    });

});


var postDetailOpen = false;
function openColorBox(em) {
    if (postDetailOpen) {
//        return;
    }
    postDetailOpen = true;
    $(".postDetail").colorbox({
        rel: "postDetail",
        title: "",
        innerWidth: 1028,
        innerHeight: 628,
        current: "",
        opacity: 0.7,
        onOpen: function () {
//            $("#cboxTopLeft").hide();
//            $("#cboxTopCenter").hide();
//            $("#cboxTopRight").hide();
//            $("#cboxBottomLeft").hide();
//            $("#cboxBottomCenter").hide();
//            $("#cboxBottomRight").hide();
//            $("#cboxMiddleLeft").hide();
//            $("#cboxMiddleRight").hide();
        },
        onComplete: function () {
            $("#cboxNext").hide();
            $("#cboxPrevious").hide();
            $("#cboxColse").hide();

//            $("#cboxTopLeft").hide();
//            $("#cboxTopCenter").hide();
//            $("#cboxTopRight").hide();
//            $("#cboxBottomLeft").hide();
//            $("#cboxBottomCenter").hide();
//            $("#cboxBottomRight").hide();
//            $("#cboxMiddleLeft").hide();
//            $("#cboxMiddleRight").hide();
        },
        onClose: function () {
            postDetailOpen = false;
        }
    });
}

function ajaxLoadData(url, params, successFn, errorFn, dataType) {
    $.ajax({
        type: "POST",
        url: url,
        data: params,
        dataType: dataType,
        success: successFn,
        error: errorFn
    });
}