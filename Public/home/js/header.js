var API=HOST_URL+"/Api";
$(function() {
	var h=window.innerHeight;	
	var index = 0;
	var add = true;

	index = Number(sessionStorage.getItem("ind"));
	$(".nav").eq(index).children("a").addClass("border-bottom");
    $("#list-1 li").eq(index).addClass("shadow")
    var left=sessionStorage.getItem("left");
	$(".nav-in").scrollLeft(left);
	
    var obj = window.innerHeight;
	var obj1 = window.innerWidth;
	var h1 =$("#header").height();
	var w =$("#header").width();
	$("#content-out").height(obj);
//跳转地址	
	var arr = [HOST_URL+"/Home/Index/recom.html",
		HOST_URL+"/Home/News/index.html",
		HOST_URL+"/Home/Video/index.html",
		HOST_URL+"/Home/Ent/index.html",
		HOST_URL+"/Home/Novel/index.html",
		HOST_URL+"/Home/Funny/index.html",		
		HOST_URL+"/Home/Beauty/index.html",
		HOST_URL+"/Home/Car/index.html",
		HOST_URL+"/Home/Game/index.html",
		HOST_URL+"/Home/Shopping/index.html",

	];

	//遍历图片
	function ergodic() {
		$(".list-title").each(function() {
			var len = $(this).children("img").length;
			if(len > 1) {
				$(this).children("img").css("float", "left");
				$(this).children("img").css("margin", 0);
				$(this).children("p").after($(this).children("img"));
				$(this).children("p").css("margin-bottom", "0.1rem");
				$(this).next(".promte").css("padding-top","0.1rem");
				$(this).next(".promte").css("position","relative");
			} else {
				$(this).css({
					"display": "inline-block",
//					"padding-top": "0.3rem"
				})
				$(this).children("img").css("padding-right", "0.1rem");

			}
		})
	}
	ergodic();
	var aa;
	window.onload = function() {

        	setTimeout(function() { 
				window.scrollTo(0, 1) ;
			}, 0);  
		aa = new IScroll('#content-out', {
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
        	setTimeout(function() { 
               window.scrollTo(0, 1);
            }, 0);  
        	var c = $("#content-in").height();
        	var y=this.y;
//          if((c - obj) < -y+500) {
//			$("#loading").css("visibility", "visible");
//			footerLoad();
//			setTimeout(function() {
//				ergodic();
//				aa.refresh();
//			}, 1000)
//		}
        })
	}

	
//触屏
	$("#content-out").on("touchend", function(e) {
        index=parseInt(sessionStorage.getItem("ind"));
		var h = $("#content-in").css("transform");
		var h1 = h.split(",");
		var n = h1.length - 1;
		var n1 = h1.length - 2;
		var h2 = parseInt(h1[n]);
		var w = parseInt(h1[n1]);
		var sl = $(".nav").eq(index).width();
		var len = $(".nav").length;
        var left;
       
		if(isNaN(index)){
			index=0;
		}

//向右滑动
		if(w > 20) {
			
			if(index == 0) {
				index = len;
				var len2 = $(".nav-list").width();
				var len3 = $(".nav-in").width();
				left=len2-len3;
			}
			if(index > 0 && index < (len)) {
				left=$(".nav-in").scrollLeft() - sl;	 
			}

            sessionStorage.setItem("left", left);
			sessionStorage.setItem("ind", index-1);
			window.location.href=arr[index-1];
		}
//向左滑动
		if(w < -20) {
			if(index == (len - 1)) {
				index = -1;				
				left=0;
			}
			if(index > 0 && index < (len - 1)) {
				var left=$(".nav-in").scrollLeft() + sl;				
			}
            sessionStorage.setItem("left", left);
			window.location.href=arr[index+1];
			sessionStorage.setItem("ind", (index + 1));
		}

//下拉刷新
		if(h2 > 50) {
			 var pull=true;
			$("#refresh").css("display", "block");
			header()
			
			setTimeout(function() {
				$("#refresh").css("display", "none");
//				headRef();
                ergodic();
           
           
			aa.refresh();
			}, 500)
			return false;

		}
		var c = $("#content-in").height();
//上拉加载
		if((c - obj) < -h2+500) {
			$("#loading").css("visibility", "visible");
			footerLoad();
			setTimeout(function() {
				ergodic();
				aa.refresh();
			}, 1000)
		}
	})

	function headRef() {
		window.location.reload();
	}
//美女页面导航
$(".b-list .nav1").on("click",function(){
	$(".b-title").text($(this).text());
})




//显示导航列表
	$("#nav-add").on("click", function() {
//		$("#list-2").height($("#list-1").height());
		if(add) {
			$("#list-1").css("display", "block");
			add = false
		} else {
			$("#list-1").css("display", "none");
			add = true
		}

	})

	//页面刷新
	function headRef() {
		window.location.reload();

	}
    
//点击导航切换页面
	$("#scroller .nav").on("click", function() {
		var index=$(this).index();

	    var left=$(".nav-in").scrollLeft();
        sessionStorage.setItem("left",left);
        sessionStorage.setItem("ind",index);
		window.location.href=arr[index];

        
	})

//点击列表切换
	$("#list-1 li").on("click", function() {
		add = true;
		index=$(this).index();
        var left=$(".nav-in li").eq(index).offset().left;
		window.location.href=arr[index];
        sessionStorage.setItem("left",left);
        sessionStorage.setItem("ind",index);
		
	})
	

})