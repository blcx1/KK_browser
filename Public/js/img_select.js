var infopath_arr=getInfoPath.split('/');
infopath_arr.pop();
var cotroller_path=infopath_arr.toString().replace(/\,/g,'/');
if($('#comeFrom').length>0){   
    $.ajax({
        type:'post',
        url:cotroller_path+'/commit.html',
        async:false,
        success:function(data){
            var result=eval('('+data+')');
            for(var v=0;v<result.length;v++){
                $('#comeFrom td').append('<input type="button" value="'+result[v]+'" class="btn">');
            }
        }
    });
}
$("#addForm input[type='submit']").click(function(){
    if(!($("#addForm input[name='tit_name']").val())){
        alert('标题不能为空');
        return false;
    }else if(!($("#addForm input[name='link_address']").val())){
        alert('链接地址不能为空');
        return false;
    }else if(!($("#addForm input[name='image[]']:first").val()) && !($("input[name='imageURL[]']:first").val())){
        //搞笑添加时文本类型无图判断
        if($('#ftxt').val()=='txt'){
            
        }else{
            alert('展示首图不能为空');
            return false;
        }
    }
    $('.img-loading').css("display","block");
});
//csv文件导入
$('#csvimp').click(function(){
    if($('#csvfile').css('display')=='none'){
        $('#csvfile').css('display','block');
    }else{
        $('#csvfile').css('display','none');
    }
});
//url图片检测
$('#btn').click(function(){
    if($("input[name='link_address']").val()){
        $.ajax({
            beforeSend:function(){
                $('.img-loading').css("display","block");
            },
            type:'post',
            url:getInfoPath,
            data:{url:$("input[name='link_address']").val()},
            async:true,
            success:function(data){
                $('.img-loading').css("display","none");
                if(data){
                    var result=eval('('+data+')');
                    var imgurl=result.imgurl;
                    if(imgurl[0]){
                        var imghtml='';
                        var uphtml='';
                        var lohtml='';
                        for(var i=0;i<imgurl.length;i++){
                            imghtml+='<label style="position:relative;"><img src="'+imgurl[i]+'" class="img" width="250"><input class="img-checkbox" type="checkbox" name="img'+i+'" value="'+imgurl[i]+'"></label><br>';                       
                        }

                        $('.imgurl').html(imghtml);
                        $('.imgselect').show();
                        $("#imgSelect").show();
                        
                        $(".imgurl").on("mouseenter","label",function(){
                        	var transposition ='<div class="t-out"><div id="t-up">↑</div> <div id="t-lo">↓</div>';
                        	$(this).append(transposition);
                        })
                        $(".imgurl").on("mouseleave","label",function(){
                        	$(".t-out").remove();
                        })
                        
                        $(".imgurl").on("mouseenter","#t-up,#t-lo",function(){
                        	$(this).css("background-color","deepskyblue")
                        })
                        
                         $(".imgurl").on("mouseleave","#t-up,#t-lo",function(){
                         	$(this).css("background-color","chocolate")
                         })
                         
                         var len=$("label").length;                        
                       $(".imgurl").on("click","#t-up",function(){                    
                       	    var i=$(this).parents("label").index()
                       	    if(i==0){
                       	    	return false;
                       	    }
                        	var img=$(this).parents("label").find("img").attr("src")
                        	$(this).parents("label").prev().remove()                        	
                        	$(this).parents("label").prev().before('<label style="position:relative;"><img class="img" src="'+img+'" width="250"><input type="checkbox" class="img-checkbox" name="img'+i+'" value="'+img+'"></label><br>')
                        	$(this).parents("label").remove()
                        	
                        })
                        $(".imgurl").on("click","#t-lo",function(){
                        	var j=$(this).parents("label").index()/2+1
                        	if(j==len){
                        		return false;
                        	}
                        	var img=$(this).parents("label").find("img").attr("src")
                        	$(this).parents("label").next().remove()
                        	$(this).parents("label").next().after('<br><label style="position:relative;"><img class="img" src="'+img+'" width="250"><input type="checkbox" class="img-checkbox" name="img'+i+'" value="'+img+'"></label>')                       	
                        	$(this).parents("label").remove()
                        })
                     

                    }else{
                        image();
                        alert('无法获取到该站点图片信息');
                    }
                    $("input[name='tit_name']").val(result.title);
                    $("input[name='introduction']").val(result.description);
                }else{
                    alert('没有检测到内容');
                    $("input[type='reset']").click();
                }
            }
        });
    }else{
        alert('请正确填写页面url');
        return false;
    }
});

$('.img-comit').click(function(){
    	$('#btn').css("display","inline-block")
        $('.imgselect').hide();
        image();
        $('#imgSelect').show();
        for(var i=0;i<$(".imgurl input[type='checkbox']:checked").length;i++){
            $('#image'+i).html('<input type="hidden" name="imageURL[]" value="'+$(".imgurl input[type='checkbox']:checked:eq("+i+")").val()+'"><img src="'+$(".imgurl input[type='checkbox']:checked:eq("+i+")").val()+'" height="100">');
            if(i>=3){
                break;
            }
        }
    });
    $('.img-recet').click(function(){
    	 $('#btn').css("display","inline-block")
        $('.imgselect').hide();
    });
    function image(){
        for(var i=0;i<3;i++){
            $('#image'+i).html('<input class="form-control radius" type="file" name="image[]"><div class="shade" >未上传任何文件</div>');
        }
        $('#imgSelect').hide();
    }
    $("input[type='reset']").click(function(){
        image();
    });
    $('[type="submit"]').on("click",function(){
    	if($("#more").val().length!=0)
    	 $('.img-loading').css("display","block")
    })
   
   $('#comeSelect').click(function(){
       if($('#comeFrom').css('display')=='none'){
           $('#comeFrom').show();
           $(this).val('︿');
       }else{
           $('#comeFrom').css('display','none');
           $(this).val('↓');
       }
   });

   var Comf=$('#comeFrom input[type="button"]');
   Comf.each(function(){
       $(this).click(function(){
            $('input[name="come_from"]').val($(this).attr('value'));
       });
   });
   
   $("#imgSelect input").click(function(){
       $('.imgselect').show();
   });
   
   //停止加载
   $(".img-loading input[type='button']").click(function(){
       if(!!(window.attachEvent && !window.opera)){
           document.execCommand("stop"); //IE
       }else{
           window.stop();
       }
       $(".img-loading").hide();
   });
     $("input[type=file]").live("change",function(e){
    	$(this).next().text(e.currentTarget.files[0].name)
    })
   	$("#comeFrom .btn").css({"min-width":"80px","height":"30px","margin":"0 0 10px 10px","padding":"0"})
   
