<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<title>KENXINDA.BROWSER.<?php echo (CONTROLLER_NAME); ?></title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="/browser/Public/home/css/header.css" />
		<link rel="stylesheet" type="text/css" href="/browser/Public/home/css/news.css" />
                <script>
    var HOST_URL="<?php echo (C("HOST_URL")); ?>";
</script>
		<script src="/browser/Public/home/js/jquery-1.7.2.js"></script>
		<script src="/browser/Public/home/js/flexible.js"></script>
		<script src="/browser/Public/home/js/iscroll.js"></script>
		<!--<script src="/browser/Public/home/js/common.js"></script>-->
		<script src="/browser/Public/home/js/header.js"></script>
	</head>

	<body>

		
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
		
		<div id="content">
			<div id="content-out">
				<div id="content-in">
					<div id="con-in">
						<div id="refresh">
							<?php echo (L("ARefreshing")); ?>
							<img src="/browser/Public/home/img/5-121204193R2-50.gif" />
						</div>

						<div id="n-out" style="width: 100%; height: 100%;">
							
							<div id="content_list">
								<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="title"><?php echo ($key); echo (L("Information")); ?></div>
									<!--<hr style="margin-top: 0;" />-->
									<ul class="list">
										<?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><li>
												<a href="<?php echo ($vo1["link_address"]); ?>">
													<div class="list-title">
														<?php if(is_array($vo1["icon_image"])): $i = 0; $__LIST__ = $vo1["icon_image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><img src="<?php echo ($vo2); ?>" /><?php endforeach; endif; else: echo "" ;endif; ?>
														<p><?php echo ($vo1["tit_name"]); ?></p>
													</div>
													<div class="promte">
														<p class="source">
															<?php echo ($vo1["come_from"]); ?>
														</p>
														<p class="author">
															<?php echo ($vo1["create_date"]); ?>
														</p>
													</div>
												</a>
											</li>
											<hr /><?php endforeach; endif; else: echo "" ;endif; ?>
									</ul><?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
						</div>
						<input id="fKe" type="hidden" value="0">
						<div id="loading" >
							<?php echo (L("Aloading")); ?>
							<img src="/browser/Public/home/img/5-121204193R2-50.gif" />
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
        var keys=$('#fKe').val();
        $.ajax({
            type:"post",
            url:API+"/Index/reNewsEntCar",
           
            data:{
             nav_mark: "news_list",	
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
                keys=jsonData.status.page;
                $('#fKe').val(keys);
                var addlist='';
                var images='';
                var len=jsonData.result.length
                len.log(len);
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
		function footerLoad() {
			$('#fKe').val(parseInt($('#fKe').val()) + 1);
			var keys = $('#fKe').val();
			$.ajax({
				type: 'post',
				url: '/browser/home/news/footerLoad.html',
				data: 'keys=' + keys,
				async: false,
				success: function(data) {
					var resData = eval("(" + data + ")");
					keys = resData.status.page;
					$('#fKe').val(keys);
					var jsonData = resData.data;
					var addlist = '';
					var images = '';
					for(var i in jsonData) {
						//images='';
						var vo = '';
						for(var m = 0; m < jsonData[i].length; m++) {
							images = '';
							for(var t = 0; t < jsonData[i][m]['icon_image'].length; t++) {
								var src = jsonData[i][m]['icon_image'][t];
								images += '<img src="' + src + '">';
							}
							vo += '<li>' +
								'<a href="' + jsonData[i][m]['link_address'] + '">' +
								'<div class="list-title">' +								
								images +
								'<p>' + jsonData[i][m]['tit_name'] + '</p>' +
								'</div>' +
								'<div class="promte">' +
								'<p class="source">' +
								jsonData[i][m]['come_from'] +
								'</p>' +
								'<p class="author">' +
								jsonData[i][m]['create_date'] +
								'</p>' +
								'</div>' +
								'</a>' +
								'</li>' +
								'<hr />';

						}
						addlist = '<div class="title">' + i + '<?php echo (L("Information")); ?></div>' +
//							'<hr style="margin-top: 0;" />' +
							'<ul class="list">' +
							vo +
							'</ul>';
						//			   '<div class="separate"></div>';
						$('#content_list').html($('#content_list').html() + addlist);
					}
					$("#loading").css("visibility","hidden")
				}
			});

			$(".list-title").each(function() {

				var len = $(this).children("img").length;
				if(len > 1) {
					$(this).children("img").css("float", "left")
					$(this).children("p").after($(this).children("img"))
					$(this).children("p").css("margin-bottom", "0.1rem")
				} else {
					$(this).next().css({
						"display": "inline-block",
						"padding-top": "0.3rem"
					})
				}
			})
		}
	</script>

</html>