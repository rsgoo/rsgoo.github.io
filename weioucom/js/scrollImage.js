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