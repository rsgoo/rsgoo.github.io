

$(document).ready(function () {
    $(".user-like-btn").click(function () {
        if (!$(this).hasClass("liked")) {
            var id = $(this).attr("data-id");
//            $(this).addClass("liked");
//            $(this).removeClass("notliked");
//            var v = parseInt($(this).val());
//            $(this).val(v + 1);
            like(id, $(this));
        }
    });
});
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

var isLoad = 0;
function like(id, btn) {
    isLoad = 1;
    var url = "/mazu2/like";
    var params = {"id": id, "respType": "json"};
    ajaxLoadData(url, params, function (response) {
        if (response.state === 0) {
            btn.removeClass("notliked");
            btn.addClass("liked");
            btn.val(parseInt(btn.val()) + 1);
        } else if (response.state === 1) {
            var href = "/login";
            location.href = href;
        }
    }, function () {
        btn.removeClass("liked");
        btn.addClass("notliked");
        btn.val(parseInt(btn.val()) - 1);
    }, "json");
}