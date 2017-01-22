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

});
