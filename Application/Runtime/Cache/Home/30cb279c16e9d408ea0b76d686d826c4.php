<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>				
        <title>KENXINDA.BROWSER.<?php echo (CONTROLLER_NAME); ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/browser/Public/home/css/header.css"/>
        <link rel="stylesheet" type="text/css" href="/browser/Public/home/css/novel.css"/>
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

					<div id="refresh">
						<?php echo (L("ARefreshing")); ?>
						<img src="/browser/Public/home/img/5-121204193R2-50.gif" />
					</div>
					<div class="pursue1">
						<p class="p-title"><?php echo (L("MostSoughtAfter")); ?></p>
						<ul class="list">
							<?php if(is_array($hot)): $i = 0; $__LIST__ = $hot;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
									<div class="con" onclick="location.href='<?php echo ($vo["link_address"]); ?>'">
										<div class="con-l">
											<img src="<?php echo ($vo["icon_image"]); ?>" />
										</div>
										<div class="con-r">
											<div class="con-title">
												<?php echo ($vo["tit_name"]); ?>
											</div>
											<div class="describe">
												<?php echo ($vo["introduction"]); ?>
											</div>
										</div>
									</div>
								</li>
								<hr /><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					<div class="separate"></div>
					<div id='content_list'>
						<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="pursue">
								<p class="p-title"><?php echo ($key); ?></p>
								<ul>
									<?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><li>
											<div class="con" onclick="location.href='<?php echo ($vo1["link_address"]); ?>'">
												<div class="con-l">
													<img src="<?php echo ($vo1["icon_image"]); ?>" />
												</div>
												<div class="con-r">
													<div class="con-title">
														<?php echo ($vo1["tit_name"]); ?>
													</div>
													<div class="describe">
														<?php echo ($vo1["introduction"]); ?>
													</div>
												</div>
											</div>
										</li>
										<hr /><?php endforeach; endif; else: echo "" ;endif; ?>
								</ul>
							</div><?php endforeach; endif; else: echo "" ;endif; ?>
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
		function header() {
			n++;
			$.ajax({
				type: "post",
				url: API+"/Index/book",
				data:{
		             nav_mark: "book_list",	
		             page:n,
		             size:6,
	            },
				success: function(data) {
					var jsonData = eval('(' + data + ')');
//					console.log(jsonData)
					var resData = jsonData.result;
                      console.log(resData)
					if(resData.length==0){
		               	n=0;
		            }   
		            var result="";
					for(var i in resData) {
						var addlist = '';
						for(var v = 0; v < resData[i].length; v++) {
							var href = "'"+resData[i][v].link_address+"'";
							addlist += '<li>' +
								'<div class="con" onclick="location.href=' + href + '">' +
								'<div class="con-l">' +
								'<img src="' + resData[i][v].icon_image + '"/>' +
								'</div>' +
								'<div class="con-r">' +
								'<div class="con-title">' +
								resData[i][v].tit_name +
								'</div>' +
								'<div class="describe">' +
								resData[i][v].introduction +
								'</div>' +
								'</div>' +
								'</div>' +
								'</li>' +
								'<hr />';
						}
						result += '<div class="pursue">' +
							'<p class="p-title">' + resData[i][0].type_name + '</p>' +
							'<ul>' +
							addlist +
							'</ul>' +
							'</div>';
						
					}
                  $('.pursue1').html( result);
					$("#content-in").height($("#con-in").height());
					
					
				}
			});
		}

		function footerLoad() {
			$('#fKe').val(parseInt($('#fKe').val()) + 1);
			var keys = $('#fKe').val();
			$.ajax({
				type: "post",
				url: "footerLoad.html",
				data: "keys=" + keys,
				success: function(data) {
					var jsonData = eval('(' + data + ')');
					keys = jsonData['status']['page'];
					$('#fKe').val(keys);
					var resData = jsonData['data'];

					for(var i in resData) {
						var addlist = '';
						for(var v = 0; v < resData[i].length; v++) {
							var href = "'"+resData[i][v].link_address+"'";
							addlist += '<li>' +
								'<div class="con" onclick="location.href=' + href + '">' +
								'<div class="con-l">' +
								'<img src="' + resData[i][v].icon_image + '"/>' +
								'</div>' +
								'<div class="con-r">' +
								'<div class="con-title">' +
								resData[i][v].tit_name +
								'</div>' +
								'<div class="describe">' +
								resData[i][v].introduction +
								'</div>' +
								'</div>' +
								'</div>' +
								'</li>' +
								'<hr />';
						}
						var result = '<div class="pursue">' +
							'<p class="p-title">' + i + '</p>' +
							'<ul>' +
							addlist +
							'</ul>' +
							'</div>';
						$('#content_list').html($('#content_list').html() + result);
					}

					$("#content-in").height($("#con-in").height());
					$("#loading").css("visibility","hidden")
					
				}
			});
		}
	</script>

</html>