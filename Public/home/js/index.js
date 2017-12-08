/**
 * Created by xian on 2017/10/16.
 */
//轮播
var t = n =0, count;

$(document).ready(function(){

    count=$("#banner_list a").length;

    $("#banner_list a:not(:first-child)").hide();

    $("#banner_info").html($("#banner_list a:first-child").find("img").attr('alt'));

    $("#banner_info").click(function(){window.open($("#banner_list a:first-child").attr('href'), "_blank")});

    $("#banner li").click(function() {

        var i = $(this).text() -1;//获取Li元素内的值，即1，2，3，4

        n = i;

        if (i >= count) return;

        $("#banner_info").html($("#banner_list a").eq(i).find("img").attr('alt'));

        $("#banner_info").unbind().click(function(){window.open($("#banner_list a").eq(i).attr('href'), "_blank")})

        $("#banner_list a").filter(":visible").fadeOut(500).parent().children().eq(i).fadeIn(1000);

        document.getElementById("banner").style.background="";

        $(this).toggleClass("on");

        $(this).siblings().removeAttr("class");

    });

    t = setInterval("showAuto()", 4000);

    $("#banner").hover(function(){clearInterval(t)}, function(){t = setInterval("showAuto()", 4000);});

})
function showAuto()
{

    n = n >=(count -1) ?0 : ++n;

    $("#banner li").eq(n).trigger('click');

}
//左右箭头轮播
$(document).ready(function(e) {
    var unslider04 = $('#b04').unslider({
            dots: true
        }),
        data04 = unslider04.data('unslider');

    $('.unslider-arrow04').click(function() {
        var fn = this.className.split(' ')[1];
        data04[fn]();
    });
});
