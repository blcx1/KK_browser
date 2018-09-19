$("#addForm input[type='submit']").click(function(){
    if(!($("#addForm input[name='tit_name']").val())){
        alert('标题不能为空');
        return false;
    }else if(!($("#addForm input[name='link_address']").val())){
        alert('链接地址不能为空');
        return false;
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
                            imghtml+='<label style="position:relative;"><img src="'+imgurl[i]+'" width="250"><input type="checkbox" name="img'+i+'" value="'+imgurl[i]+'"></label><br>';
                            uphtml+='<div>↑</div>';
                            lohtml+='<div>↓</div>';
                        }
                        $('#up').html(uphtml);
                        $('#lo').html(lohtml);
                        $('.imgurl').html(imghtml);
                        $('.imgselect').show();
                        $("#imgSelect").show();
                        $('.imgurl label').each(function(i){
                            $(this).mouseover(function(){
                                $('#up div:eq('+i+')').show();
                                $('#lo div:eq('+i+')').show();
                            });
                            $(this).mouseout(function(){
                                $('#up div:eq('+i+')').css('display','none');
                                $('#lo div:eq('+i+')').css('display','none');        
                            });
                        });

                        var interval=30;
                        $('#up div').each(function(i){
                            $('#up div:eq('+i+')').css('left',parseInt($('.imgurl label:eq('+(i)+')').offset().left+15)+'px');
                            $('#up div:eq('+i+')').css('top',$('.imgurl label:eq('+(i)+')').offset().top+'px');
                            $(this).click(function(){
                                if(i>0){
                                    var tmplabel=$('.imgurl label:eq('+(i-1)+')').html();
                                    $('.imgurl label:eq('+(i-1)+')').html($('.imgurl label:eq('+i+')').html());
                                    $('.imgurl label:eq('+i+')').html(tmplabel);        
                                    $('#up div:eq('+i+')').css('top',$('.imgurl label:eq('+(i)+')').offset().top+'px');
                                    $('#up div:eq('+(i-1)+')').css('top',$('.imgurl label:eq('+(i-1)+')').offset().top+'px');
                                    $('#lo div:eq('+i+')').css('top',($('.imgurl label:eq('+(i)+')').offset().top+interval)+'px');
                                    $('#lo div:eq('+(i-1)+')').css('top',($('.imgurl label:eq('+(i-1)+')').offset().top+interval)+'px');
                                }
                            });
                            $(this).mouseover(function(){
                                $('#up div:eq('+i+')').show();
                                $('#lo div:eq('+i+')').show();
                                $(this).css('background','deepskyblue');

                            });
                            $(this).mouseout(function(){
                                $(this).css('background','chocolate');

                            });
                        });
                        $('#lo div').each(function(i){
                            $('#lo div:eq('+i+')').css('left',parseInt($('.imgurl label:eq('+(i)+')').offset().left+15)+'px');
                            $('#lo div:eq('+i+')').css('top',parseInt($('.imgurl label:eq('+(i)+')').offset().top+interval)+'px');
                            $(this).click(function(){
                                if(i<$('#lo div').length-1){
                                    var tmplabel=$('.imgurl label:eq('+parseInt(i+1)+')').html();
                                    $('.imgurl label:eq('+parseInt(i+1)+')').html($('.imgurl label:eq('+i+')').html());
                                    $('.imgurl label:eq('+i+')').html(tmplabel);
                                    $('#up div:eq('+i+')').css('top',$('.imgurl label:eq('+(i)+')').offset().top+'px');
                                    $('#up div:eq('+(i+1)+')').css('top',$('.imgurl label:eq('+(i+1)+')').offset().top+'px');
                                    $('#lo div:eq('+i+')').css('top',($('.imgurl label:eq('+(i)+')').offset().top+interval)+'px');
                                    $('#lo div:eq('+(i+1)+')').css('top',($('.imgurl label:eq('+(i+1)+')').offset().top+interval)+'px');
                                }
                            });
                            $(this).mouseover(function(){
                                $('#up div:eq('+i+')').show();
                                $('#lo div:eq('+i+')').show();
                                $(this).css('background','deepskyblue');

                            });
                            $(this).mouseout(function(){
                                $(this).css('background','chocolate');
                            });
                        });

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
            $('#image'+i).html('<input class="form-control radius" type="file" name="image[]">');
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


