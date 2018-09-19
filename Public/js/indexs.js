$(function () {
    //日历
    $("#datepicker").datepicker();
    //左边菜单
    $('.one').click(function () {
        $('.one').removeClass('one-hover');
        $(this).addClass('one-hover');
        $(this).parent().find('.kid').toggle();
    });
    //影藏菜单
    var l = $('.left_c');
    var r = $('.right_c');
    var c = $('.mainFrame');
    $('.nav-tip').click(function () {
        if (l.css('left') == '8px') {
            l.animate({
                left: -300
            }, 500);
            r.animate({
                left: 21
            }, 500);
            c.animate({
                left: 29
            }, 500);
            $(this).animate({
                "background-position-x": "-12"
            }, 300);
        } else {
            l.animate({
                left: 8
            }, 500);
            r.animate({
                left: 260
            }, 500);
            c.animate({
                left: 268
            }, 500);
            $(this).animate({
                "background-position-x": "0"
            }, 300);
        };
    });
    //横向菜单
    $('.top-menu-nav li').click(function () {
        $('.kidc').hide();
        $(this).find('.kidc').show();
        
    });
    $('.kidc').bind('mouseleave', function () {
        $('.kidc').hide();
    });

function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return unescape(arr[2]);
    else
        return null;
}

var language=getCookie('think_language');
var left3=390;
if(language=="en-us"){
	$(".top-menu").css("margin-left","480px")
	$(".top-menu .top-menu-nav li").css("padding","0 6px")
	$(".warp").css("min-width","1650px")
left3=480
}

window.onload=function(){
var w = window.innerWidth-left3-52

$(".top-scroll").css("width",w)
var mousee;
var x,X,left1=0, left2;
var len=parseInt($(".top-menu-nav").width())-parseInt($(".top-scroll").width());
$(".top-menu-nav").on("mousedown",function(e){
	mousee=true;
	x=e.clientX;
	left2=parseInt($(".top-menu-nav").css("left"))
})

$(".top-menu-nav").on("mousemove",function(e){
	if(mousee==true){
		e.preventDefault();
		X=e.clientX;
		left1=left2+X-x;
		if(left1>=0 || left1<=-len){
			return false;
		}
		$(".top-menu-nav").css("left",left1)
		
	}
})


$(".top-menu-nav").on("mouseup",function(){
	mousee=false;
})
$(".top-menu-nav").on("mouseleave",function(){
	mousee=false;
})
}

$(".top-menu-nav .kidc li").on("mouseenter",function(){
	$(this).css("overflow","inherit")
	
})
$(".top-menu-nav .kidc li").on("mouseleave",function(){
	$(this).css("overflow","hidden")
})


$(window).resize(function(){
//	window.location.reload();
   onload()
})


});