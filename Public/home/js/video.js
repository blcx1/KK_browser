$(function(){
var obj1=window.parent.window.innerWidth
	
	 var obj=window.parent.window.innerHeight
// console.log(obj)
var h= window.parent.document.getElementsByTagName('body')[0]
//console.log(h)
h.style.height=obj
$('body', parent.document).height(obj); 
$('ifarme', parent.document).height(obj); 
$("#content-out").height(obj)
		
		var len=$(this).children("img").length;
      
	$(".list-title").each(function(){
		if(len>1){
			$(this).children("img").css("float","left")
			$(this).children("p").after($(this).children("img"))
			$(this).children("p").css("margin-bottom","0.1rem")
		}else{
			$(this).next().css({"display":"inline-block","padding-top":"0.3rem"})
		}
	})

//  $("#content-in").height($("#v-content").height())
	
	 var bb;
	   window.onload=function(){
   
    	 bb = new iScroll('content-out',{
				mouseWheel:true,//鼠标滚轮控制
				scrollbars:true,//滚动条
//				bounce:true,//反弹效果
				vScrollbar:false,
				fixedScrollbar:true,
				//vScroll:false,//禁止垂直滚动
//				lockDirection:false,
//				hideScrollbar:true,
                 onScrollMove: function () {
            if((this.x < this.maxScrollX) && (this.pointX < 1)){
                this.scrollTo(0, this.maxScrollX, 400);
                return;
            } else if (this.x > 0 && (this.pointX > window.innerWidth- 1)) {
                this.scrollTo(0, 0, 400);
                return;
            }

            
        },
				onScrollEnd: function (e) {
					var resultH = $("#content-out").height();
					$("#content-out").css("content-out", resultH);
						setTimeout(function () {
						bb.refresh();
						bb.options.snap = true;
					}, 100);
				}
		})
    	
   }
	   function refresh(){
	   	
	   	setTimeout(function () {
			bb.refresh();
			bb.options.snap = true;
		}, 1000);
	   }
//	   var x= window.parent.document.getElementsByClassName('nav')
//	   console.log(x)
$("#content-out").on("touchend",function(){
	
    var h = $("#content-in").css("transform")
    console.log(h)
    var h1=h.split(",")
    console.log(h.split(","));
    var n=h1.length-1
    var n1=h1.length-2
    var h2=parseInt(h1[n])
    var w=parseInt(h1[n1])
    var sl=window.parent.$(".nav").width()
    console.log()

   
    var index;

	window.parent.$(".nav").each(function(){
		if($(this).children("a").hasClass("border-bottom")){
			index=$(this).index()
			return false;
		}
    })
	console.log(index)
    if(w>30){
    	if(index==0){
    		return false;
    	}
    	if(index>0&&index<9){
    	window.parent.$(".nav-in").scrollLeft(window.parent.$(".nav-in").scrollLeft()-sl)
    }
    window.parent.$(".nav").eq(index).prev().children("a").css("color","#0000FF").addClass("border-bottom")
	window.parent.$(".nav").eq(index).children("a").css("color","black").removeClass("border-bottom")
	window.parent.$("#content div").eq(index).prev().css("display","block")
	window.parent.$("#content div").eq(index).css("display","none")
	window.parent.$("#list-1 li").eq(index).prev().addClass("shadow").css({"border":"blue","color":"blue"})
    window.parent.$("#list-1 li").eq(index).removeClass("shadow").css({"border":"#c9c9c4","color":"black"})
    }
    if(w<-30){
	if(index>0&&index<9){
    	window.parent.$(".nav-in").scrollLeft(window.parent.$(".nav-in").scrollLeft()+sl)
        }
    window.parent.$(".nav").eq(index).next().children("a").css("color","#0000FF").addClass("border-bottom")
	window.parent.$(".nav").eq(index).children("a").css("color","black").removeClass("border-bottom")
	window.parent.$("#content div").eq(index).next().css("display","block")
	window.parent.$("#content div").eq(index).css("display","none")
	window.parent.$("#list-1 li").eq(index).next().addClass("shadow").css({"border":"blue","color":"blue"})
    window.parent.$("#list-1 li").eq(index).removeClass("shadow").css({"border":"#c9c9c4","color":"black"})
    }
   if(h2>50){
        $("#refresh").css("display","block")
        setTimeout(function(){
                $("#refresh").css("display","none")
//              headRef();
                
        },1000)
        
   }
   var c= $("#content-in").height()

   if((c-obj)<-h2){	
        $("#loading").css("display","block")		   
        setTimeout(function(){
            $("#loading").css("display","none")
            // footerLoad();
//           ergodic()
//           $("#content-out").css("padding",0)

        },1000)
//       console.log(00)


   }
}) 
   function headRef(){
        window.location.reload();
        
   }
})