<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>				
        <title>KENXINDA.BROWSER.<?php echo (CONTROLLER_NAME); ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/browser/Public/home/css/header.css"/>
        <link rel="stylesheet" type="text/css" href="/browser/Public/home/css/pushing.css"/>
        <script>
    var HOST_URL="<?php echo (C("HOST_URL")); ?>";
</script>
        <script src="/browser/Public/home/js/jquery-1.7.2.js"></script>
        <script src="/browser/Public/home/js/flexible.js"></script>		
        <script src="/browser/Public/home/js/iscroll.js"></script>
        <!--<script src="/browser/Public/home/js/common.js"></script>-->
        <script src="/browser/Public/home/js/header.js"></script>
    </head>
	<body >
		
		
<!--<li role="presentation" class="nav"><a href="#" class="border-bottom ">推存</a></li>-->
<div id="header">
        <div id="nav-add">+</div>
        <div class="nav-out" id="wrapper">
                <div class="nav-in" id="scroller">
                        <ul class="nav-list"> 
                            <?php if(is_array($webNavi)): $k = 0; $__LIST__ = $webNavi;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li role="presentation" class="nav" <?php if(LANG_SET== 'en-us'): ?>style="margin-left:0.2rem;"<?php endif; ?>><a href="javascript:;"><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                </div>				
   </div>
</div>
<div id="nav-list-1">

        <div id="list-1">
                <div id="list-2">
                <p>点击进入频道</p>
                <ul>
                    <?php if(is_array($webNavi)): $k = 0; $__LIST__ = $webNavi;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li<?php if($k == 0): ?>class="shadow"<?php endif; ?>><?php echo ($vo["name"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
        </div>
        <div style="clear: both;"></div>
        </div>
</div>
		<div id="content" > 	
	<div id="content-out">
			<div id="content-in">
				<div id="con-in">
				
			
			<div id="refresh" >
					<?php echo (L("ARefreshing")); ?>
					<img src="/browser/Public/home/img/5-121204193R2-50.gif"/>
				</div>
		<div id="toutiao" style="height:271px;" onclick="location.href='<?php echo ($newtop["link_address"]); ?>'">
			<div id="img">
                            <?php if(is_array($newtop["icon_image"])): $i = 0; $__LIST__ = $newtop["icon_image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?><img src="<?php echo ($img); ?>"><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
			
			<div id="t-title">
				<?php echo ($newtop["tit_name"]); ?>
			</div>
		</div>
		<hr />
		<ul class="list">
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
				<a href="<?php echo ($vo["link_address"]); ?>">
					<div class="list-title">
                                            <?php if(is_array($vo["icon_image"])): $i = 0; $__LIST__ = $vo["icon_image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?><img src="<?php echo ($img); ?>"><?php endforeach; endif; else: echo "" ;endif; ?>
						<p><?php echo ($vo["tit_name"]); ?></p>												
					</div>
					<div class="promte">
						<p class="source">							
                                                        <?php echo ($vo["come_from"]); ?>							
						</p>
						<p class="author">
							<?php echo ($vo["create_date"]); ?>
						</p>
					</div>
				</a>
			</li>
			<hr /><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
                <input id="fKe" type="hidden" value="0">
		         <div id="loading" >
		   	<?php echo (L("Aloading")); ?>
		   	<img src="/browser/Public/home/img/5-121204193R2-50.gif"/>
		</div>
		</div>
	   </div>
	   
	</div>
	</div>
</body>
<script>
    var n=0;
    function header(){
    	n++;
         $.ajax({
            type:"post",
            url:API+"/Index/reNewsEntCar",          
            data:{
             nav_mark: "car_list",	
             page:n,
             size:1,
             tag:"top",
            },
            async:true,
            success:function(data){
            	var data=JSON.parse(data)
//          	console.log(data)
            	if(data.result[0].tag=="ordinary"){
//          		header()
            		return true;
            	}
            	var t='<div id="img" onclick="location.href="'+data.result[0].link_address+'"">'+
							'<img src="'+data.result[0].icon_image+'"></div>'+
						'<div id="t-title">'+data.result[0].tit_name+'</div>'
				$("#toutiao").html(t)
            }
          })
        $.ajax({
            type:"post",
            url:API+"/Index/reNewsEntCar",
           
            data:{
             nav_mark: "car_list",	
             page:n,
             size:6,
            },
            async:true,
            success:function(data){
            	
             var jsonData=eval('('+data+')');
//           var jsonData1=$.parseJSON(data);
//            var jsonData2=JSON.parse(data);
//             console.log(jsonData)
               if(n!=1){
	               if(jsonData.result.length<6){
	               	n=0;
	               }
               }
               
                var addlist='';
                var images='';
                 var len=jsonData.result.length
                for(var i=0;i<len;i++){
                    images='';
                    var iconImg=jsonData.result[i].icon_image.length
                    for(var m=0;m<iconImg;m++){
                        images+='<img src="'+jsonData.result[i].icon_image[m]+'">';
                    }
                    addlist+='<li><a href="'+jsonData.result[i].link_address+'">'+
					'<div class="list-title">'+
						images+
						'<p>'+jsonData.result[i].tit_name+'</p>'+												
					'</div>'+
					'<div class="promte">'+
						'<p class="source">'+							
							jsonData.result[i].come_from+						
						'</p>'+
						'<p class="author">'+
							jsonData.result[i].create_date+
						'</p>'+
					'</div>'+
				'</a>'+
			'</li><hr />';
                      
                }
                 $(".list").html(addlist);
            }
           
        });
    }
    function footerLoad(){
        $('#fKe').val(parseInt($('#fKe').val())+1);
        var keys=$('#fKe').val();
        $.ajax({
            type:"post",
            url:"footerLoad.html",
            data:'keys='+keys,
            success:function(data){
                var jsonData=eval('('+data+')');
                keys=jsonData['status']['page'];
                $('#fKe').val(keys);
                var result=jsonData['data'];
                for(var i=0;i<result.length;i++){
                    var images='';
                    for(var m=0;m<result[i]['icon_image'].length;m++){
                        images+='<img src="'+result[i]['icon_image'][m]+'">'
                    }
                    var addlist='<li>'+
				'<a href="'+result[i].link_address+'">'+
					'<div class="list-title">'+
						images+
						'<p>'+result[i].tit_name+'</p>'+											
					'</div>'+
					'<div class="promte">'+
						'<p class="source">'+							
                                                        result[i].come_from+						
						'</p>'+
						'<p class="author">'+
							result[i].create_date+
						'</p>'+
					'</div>'+
				'</a>'+
			'</li>'+
			'<hr />';
                    $('.list').html($('.list').html()+addlist);
                }
                $(".list-title").each(function(){
                    var len=$(this).children("img").length;
                    if(len>1){
                            $(this).children("img").css("float","left")
                            $(this).children("p").after($(this).children("img"))
                            $(this).children("p").css("margin-bottom","0.1rem")
                    }else{
                            $(this).next().css({"display":"inline-block","padding-top":"0.3rem"})
                    }
                });
                $("#loading").css("visibility","hidden")
            }
        });
    }
</script>
</html>