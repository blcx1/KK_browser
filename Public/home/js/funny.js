$(function(){
	
		$("#content-out").height(500)
	$(".list-title").each(function(){		
		var len=$(this).children("img").length;
		if(len>1){
			$(this).children("img").css("float","left")
			$(this).children("p").after($(this).children("img"))
			$(this).children("p").css("margin-bottom","0.1rem")
		}else{
			$(this).next().css({"display":"inline-block","padding-top":"0.3rem"})
		}
	})

})