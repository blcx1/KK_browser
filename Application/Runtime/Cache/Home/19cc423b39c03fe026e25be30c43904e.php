<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>				
        <title>KENXINDA.BROWSER.<?php echo (CONTROLLER_NAME); ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/browser/Public/home/css/header.css"/>
        <link rel="stylesheet" type="text/css" href="/browser/Public/home/css/video.css"/>
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
			<div id="video-content">
				<div id="v-in">
					
					<ul class="list">
                                            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="separate"></div>
						<li>
							<div class="video-out">
								<div onclick="location.href='<?php echo ($vo["link_address"]); ?>'" class="vidoe-in">
									<div class="video-con" style="background-image:url(<?php echo ($vo["icon_image"]); ?>);">
										<div class="video-title">
											<?php echo ($vo["tit_name"]); ?>
										</div>
										<i ></i>
										<div class="promte">
											<span class="time"><?php echo ($vo["time"]); ?></span>
										</div>
									</div>
									<div class="video-source">
										<span class="source"><?php echo ($vo["come_from"]); ?></span>
									</div>
								</div>
							</div>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
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
            var n =0;
            function header(){
                n++
                $.ajax({
                    type:"post",
                    url:API+"/Index/video",
                     data:{
				             nav_mark: "video_list",	
				             page:n,
				             size:6,
				            },
                    async:true,
                    success:function(data){
                        var jsonData=eval('('+data+')');
//                      keys=jsonData['status']['page'];
//                      $('#fKe').val(keys);

                        resData=jsonData.result;
                        if(n!=1){
			               if(jsonData.result.length<6){
			               	n=0;
			               }
		               }
                        var addlist='';
                        for(var i=0;i<resData.length;i++){
                            var link_address="'"+resData[i].link_address+"'";
                            addlist+='<div class="separate"></div>'+
						'<li>'+
							'<div class="video-out">'+
								'<div onclick="location.href='+link_address+'" class="vidoe-in">'+
									'<div class="video-con" style="background-image:url('+resData[i].icon_image+');">'+
										'<div class="video-title">'+
											resData[i].tit_name+
										'</div>'+
										'<i ></i>'+
										'<div class="promte">'+
											'<span class="time">'+resData[i].time+'</span>'+
										'</div>'+
									'</div>'+
									'<div class="video-source">'+
										'<span class="source">'+resData[i].come_from+'</span>'+
									'</div>'+
								'</div>'+
							'</div>'+

						'</li>';
                           
                        } 
					 $('#v-in ul').html(addlist);
                        
                    }
            });
        }
            
            function footerLoad(){
                $('#fKe').val(parseInt($('#fKe').val())+1);
                var keys=$('#fKe').val();
                $.ajax({
                    type:"post",
                    url:"footerLoad.html",
                    data:"keys="+keys,
                    async:true,
                    success:function(data){
                        var jsonData=eval('('+data+')');
                        keys=jsonData['status']['page'];
                        $('#fKe').val(keys);
                        resData=jsonData['data'];
                        var addlist='';
                        for(var i=0;i<resData.length;i++){
                            var link_address="'"+resData[i].link_address+"'";
                            addlist='<div class="separate"></div>'+
						'<li>'+
							'<div class="video-out">'+
								'<div onclick="location.href='+link_address+'" class="vidoe-in">'+
									'<div class="video-con" style="background-image:url('+resData[i].icon_image+');">'+
										'<div class="video-title">'+
											resData[i].tit_name+
										'</div>'+
										'<i ></i>'+
										'<div class="promte">'+
											'<span class="time">'+resData[i].time+'</span>'+
										'</div>'+
									'</div>'+
									'<div class="video-source">'+
										'<span class="source">'+resData[i].come_from+'</span>'+
									'</div>'+
								'</div>'+
							'</div>'+

						'</li>';
                            $('#v-in ul').html($('#v-in ul').html()+addlist);
                        } 
					$("#loading").css("visibility","hidden")
                        
                    }
            });
        }
        </script>
</html>