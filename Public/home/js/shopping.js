$(function(){
			
		$("#content-out").height(500)
		
	$(".list-title").each(function(){
		
		var len=$(this).children("img").length;
        console.log(len)

		if(len>1){
			$(this).children("img").css("float","left")
			$(this).children("p").after($(this).children("img"))
			$(this).children("p").css("margin-bottom","0.1rem")
		}else{
			$(this).next().css({"display":"inline-block","padding-top":"0.3rem"})
		}
	})
	
	
	$("#content-in").height($("#s-content").height())
	
	
	 var aa = new IScroll('#content-out',{
//				mouseWheel:true,//鼠标滚轮控制
//				scrollbars:true,//滚动条
//				bounce:true,//反弹效果
//				vScrollbar:false,
//				vScroll:false,
//				hideScrollbar:true,
		})
    $("#content-out").on("touchend",function(){
    
				var h = $("#content-in").css("transform")
				var h1=h.split(",")
			    console.log(h.split(","));
			    var n=h1.length-1
			    var h2=parseInt(h1[n])
			    	
			   console.log(parseInt(h1[n]) )
			   
			   if(h2>50){
			   	$("#refresh").css("display","block")
			   	setTimeout(function(){
			   		$("#refresh").css("display","none")
			   	},2000);
                                headRef();
			   }
			   var c= $("#content-in").height()
			   if((c-500)<-h2){	
			   	$("#loading").css("display","block")		   
			   	setTimeout(function(){
			   		$("#loading").css("display","none")
			   	},2000);
			   }
			}) 

})