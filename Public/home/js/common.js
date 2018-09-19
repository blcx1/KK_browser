//$(function() {
//	var obj = window.innerHeight;
//	var obj1 = window.innerWidth;
//	var h =$("#header").height();
//	var w =$("#header").width();
////	h.style.height = obj
////	$('body', parent.document).height(obj);
////	$('ifarme', parent.document).height(obj);
//	$("#content-out").height(obj-h)
////跳转地址	
//	var arr = ["/browser/Home/Index/recom.html",
//		"/browser/Home/News/index.html",
//		"/browser/Home/Video/index.html",
//		"/browser/Home/Ent/index.html",
//		"/browser/Home/Novel/index.html",
//		"/browser/Home/Funny/index.html",		
//		"/browser/Home/Beauty/index.html",
//		"/browser/Home/Car/index.html",
//		"/browser/Home/Game/index.html",
//		"/browser/Home/Shopping/index.html",
//
//	]
//
//	//遍历图片
//	function ergodic() {
//		$(".list-title").each(function() {
//			var len = $(this).children("img").length;
//			if(len > 1) {
//				$(this).children("img").css("float", "left")
//				$(this).children("img").css("margin", 0)
//				$(this).children("p").after($(this).children("img"))
//				$(this).children("p").css("margin-bottom", "0.1rem")
//				$(this).next().css("padding-top","0.1rem")
//			} else {
//				$(this).css({
//					"display": "inline-block",
////					"padding-top": "0.3rem"
//				})
//				$(this).children("img").css("padding-right", "0.1rem")
//
//			}
//		})
//	}
//	ergodic()
//	var aa;
//	window.onload = function() {
//		//滚动条
////		$(window).resize(function() {
////		window.location.reload();
////	})
//if(obj1!=w){
//	alert(11)
//}
////setTimeout(function() { 
////window.scrollTo(0, 200) 
////}, 0); 
//
//if(document.documentElement.scrollHeight <= document.documentElement.clientHeight) { 
//bodyTag = document.getElementsByTagName('body')[0]; 
//bodyTag.style.height = document.documentElement.clientWidth / screen.width * screen.height + 'px'; 
//} 
//setTimeout(function() { 
//window.scrollTo(0, 1) 
//}, 0);  
//		aa = new IScroll('#content-out', {
//			mouseWheel: true,
//			scrollbars: true,
//			scrollX: true,
//			scrollerY: true,
//			//				freeScroll:true,
//			scrollbars: false,
//			momentum: true,
//			click:true,
//			disablePointer: true,
//			disableTouch:false,
//			disableMouse:false,
//			onScrollMove: function() {
//				if((this.x < this.maxScrollX) && (this.pointX < 1)) {
//					this.scrollTo(0, this.maxScrollX, 400);
//					return;
//				} else if(this.x > 0 && (this.pointX > window.innerWidth - 1)) {
//					this.scrollTo(0, 0, 400);
//					return;
//				}
//
//			},
//			onScrollEnd: function(e) {
//
//				var resultH = $("#content-out").height();
//				$("#content-out").css("content-out", resultH);
//				setTimeout(function() {
//					aa.refresh();
//					aa.options.snap = true;
//				}, 100);
//			}
//		})
//
//	}
//
//	
////触屏
//	$("#content-out").on("touchend", function(e) {
//	 
//
//      index=parseInt(sessionStorage.getItem("ind"));
//		var h = $("#content-in").css("transform")
//		var h1 = h.split(",")
//		var n = h1.length - 1
//		var n1 = h1.length - 2
//		var h2 = parseInt(h1[n])
//		var w = parseInt(h1[n1])
//		var sl = $(".nav").eq(index).width()
//		var len = $(".nav").length
//      var left;
//		if(isNaN(index)){
//			index=0
//		}
//
////向右滑动
//		if(w > 20) {
//			
//			if(index == 0) {
//				index = len;
//				var len2 = $(".nav-list").width()
//				var len3 = $(".nav-in").width()
//				left=len2-len3
//			}
//			if(index > 0 && index < (len)) {
//				left=$(".nav-in").scrollLeft() - sl;	 
//			}
//
//          sessionStorage.setItem("left", left)
//			sessionStorage.setItem("ind", index-1)
//			window.location.href=arr[index-1]
//		}
////向左滑动
//		if(w < -20) {
//			if(index == (len - 1)) {
//				index = -1;				
//				left=0
//			}
//			if(index > 0 && index < (len - 1)) {
//				var left=$(".nav-in").scrollLeft() + sl;				
//			}
//          sessionStorage.setItem("left", left)
//			window.location.href=arr[index+1]
//			sessionStorage.setItem("ind", (index + 1))
//		}
//
////下拉刷新
//		if(h2 > 50) {
//			$("#refresh").css("display", "block")
//			setTimeout(function() {
//				$("#refresh").css("display", "none")
//				headRef();
//
//			}, 1000)
//
//		}
//		var c = $("#content-in").height()
////上拉加载
//		if((c - obj) < -h2) {
//			console.log(1)
//			$("#loading").css("visibility", "visible")
//			footerLoad();
//			setTimeout(function() {
//				
//
//				ergodic()
//				aa.refresh()
//			}, 1000)
//		}
//	})
//
//	function headRef() {
//		window.location.reload()
//	}
////美女页面导航
//$(".b-list .nav1").on("click",function(){
//	$(".b-title").text($(this).text())
//})
//
//})