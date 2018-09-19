$(function() {
	var h=window.innerHeight	
	var index = 0;
	var add = true;

	index = Number(sessionStorage.getItem("ind"))
	$(".nav").eq(index).children("a").addClass("border-bottom")

    var left=sessionStorage.getItem("left")
	$(".nav-in").scrollLeft(left)
	
    var obj = window.innerHeight;
	var obj1 = window.innerWidth;
	var h1 =$("#header").height();
	var w =$("#header").width();
	$("#content-out").height(obj-h1)
//跳转地址	
	var arr = ["/browser/Home/Index/recom.html",
		"/browser/Home/News/index.html",
		"/browser/Home/Video/index.html",
		"/browser/Home/Ent/index.html",
		"/browser/Home/Novel/index.html",
		"/browser/Home/Funny/index.html",		
		"/browser/Home/Beauty/index.html",
		"/browser/Home/Car/index.html",
		"/browser/Home/Game/index.html",
		"/browser/Home/Shopping/index.html",

	]

	//遍历图片
	function ergodic() {
		$(".list-title").each(function() {
			var len = $(this).children("img").length;
			if(len > 1) {
				$(this).children("img").css("float", "left")
				$(this).children("img").css("margin", 0)
				$(this).children("p").after($(this).children("img"))
				$(this).children("p").css("margin-bottom", "0.1rem")
				$(this).next().css("padding-top","0.1rem")
			} else {
				$(this).css({
					"display": "inline-block",
//					"padding-top": "0.3rem"
				})
				$(this).children("img").css("padding-right", "0.1rem")

			}
		})
	}
	ergodic()
	var aa;
	$("#content-out").height($("#content-in").height())
	$("body").height(obj)
	window.onload = function() {
setTimeout(function() { 
window.scrollTo(0, 1) 
console.log(2)
}, 0);  
        	
		aa = new IScroll('#cc', {
			mouseWheel: true,
			scrollbars: true,
			scrollX: true,
			scrollerY: true,
			//				freeScroll:true,
			scrollbars: false,
			momentum: true,
			click:true,
			disablePointer: true,
			disableTouch:false,
			disableMouse:false,
//			onScrollMove: function() {
//				console.log(111)
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
//				}, 0);
//			}
//			
		})
        aa.on("scrollEnd",function(){
        	
        	var c = $("#content-in").height()
        	var y=this.y
           if((c - obj) < -y+500) {
			$("#loading").css("visibility", "visible")
			footerLoad();
			setTimeout(function() {
				ergodic()
				aa.refresh()
			}, 1000)
		}
//         if(y==0){
//         	setTimeout(function() { 
//				window.scrollTo(0, 1) 
//			}, 0);  
//         }
        })
        
        
	}

	
//触屏
	$("#cc").on("touchend", function(e) {
        index=parseInt(sessionStorage.getItem("ind"));
		var h = $("#dd").css("transform")
		var h1 = h.split(",")
		var n = h1.length - 1
		var n1 = h1.length - 2
		var h2 = parseInt(h1[n])
		var w = parseInt(h1[n1])
		var sl = $(".nav").eq(index).width()
		var len = $(".nav").length
        var left;
		if(isNaN(index)){
			index=0
		}

//向右滑动
		if(w > 20) {
			
			if(index == 0) {
				index = len;
				var len2 = $(".nav-list").width()
				var len3 = $(".nav-in").width()
				left=len2-len3
			}
			if(index > 0 && index < (len)) {
				left=$(".nav-in").scrollLeft() - sl;	 
			}

            sessionStorage.setItem("left", left)
			sessionStorage.setItem("ind", index-1)
			window.location.href=arr[index-1]
		}
//向左滑动
		if(w < -20) {
			if(index == (len - 1)) {
				index = -1;				
				left=0
			}
			if(index > 0 && index < (len - 1)) {
				var left=$(".nav-in").scrollLeft() + sl;				
			}
            sessionStorage.setItem("left", left)
			window.location.href=arr[index+1]
			sessionStorage.setItem("ind", (index + 1))
		}

//下拉刷新
		if(h2 > 50) {
			$("#refresh").css("display", "block")
			setTimeout(function() {
				$("#refresh").css("display", "none")
				headRef();

			}, 1000)

		}
		var c = $("#content-in").height()
//上拉加载
		if((c - obj) < -h2+500) {
			console.log(1)
			$("#loading").css("visibility", "visible")
			footerLoad();
			setTimeout(function() {
				

				ergodic()
				aa.refresh()
			}, 1000)
		}
	})

	function headRef() {
		window.location.reload()
	}
//美女页面导航
$(".b-list .nav1").on("click",function(){
	$(".b-title").text($(this).text())
})




//显示导航列表
	$("#nav-add").on("click", function() {
		$("#list-2").height($("#list-1").height())
		if(add) {
			$("#list-1").css("display", "block")
			add = false
		} else {
			$("#list-1").css("display", "none")
			add = true
		}

	})

	//页面刷新
	function headRef() {
		window.location.reload();

	}
    
//点击导航切换页面
	$("#scroller .nav").on("click", function() {
		var index=$(this).index()
		var left=$(".nav-in").scrollLeft()
        sessionStorage.setItem("left",left) 
        sessionStorage.setItem("ind",index)
		window.location.href=arr[index]
//		show(index)
//		var tou = sessionStorage.getItem("tiao");
        
	})
//刷新页面加载内容
//	function show(index) {
//		$("#scroller li").eq(index).children("a").addClass("border-bottom").css("color", "#0000FF")
//		$("#scroller li").eq(index).siblings().children("a").removeClass("border-bottom").css("color", "black")
//		$("#list-1 li").eq(index).addClass("shadow")
//		$("#list-1 li").eq(index).siblings().removeClass("shadow").css({
//			"border": "1px solid #c9c9c4",
//			"color": "black"
//		})
//		
//	}
//点击列表切换
//	$("#list-1 li").on("click", function() {
//		add = true
//		var left=$(".nav-in").scrollLeft()
//      sessionStorage.setItem("left",left)
//      sessionStorage.setItem("ind",index)
//		index = $(this).index()		
//		$(this).css("color", "#0000FF").addClass("shadow")
//		$(this).siblings().removeClass("shadow").css({
//			"border": "1px solid #c9c9c4",
//			"color": "black"
//		})
//		$("iframe").attr("src",arr[index])
//		show(index)
//		$("#list-1").css("display", "none")
//		var len2=$(".nav").eq(index).offset().left;
//	    $(".nav-in").scrollLeft(len2)
//	})
	
	


//$(window).resize(function() {
//		window.location.reload();
//	})

})