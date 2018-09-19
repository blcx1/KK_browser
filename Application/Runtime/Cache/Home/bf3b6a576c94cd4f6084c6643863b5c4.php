<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>				
        <title>KENXINDA.BROWSER.<?php echo (CONTROLLER_NAME); ?></title>
        <meta charset="UTF-8">
          <script>
    var HOST_URL="<?php echo (C("HOST_URL")); ?>";
</script>
          <script src="/browser/Public/home/js/flexible.js"></script>		
        <link rel="stylesheet" type="text/css" href="/browser/Public/home/css/header.css"/>
        <link rel="stylesheet" type="text/css" href="/browser/Public/home/css/pushing.css"/>
        <script src="/browser/Public/home/js/jquery-1.7.2.js"></script>

        <script src="/browser/Public/home/js/iscroll.js"></script>
        <script src="/browser/Public/home/js/common.js"></script>
        <script src="/browser/Public/home/js/header.js"></script>
              
    </head>
	<body id="cc">
		<div id="dd">
		
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
		<ul class="list">
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
				<a href="<?php echo ($vo["link_address"]); ?>">
					<div class="list-title">
                                            <?php if(is_array($vo["images"])): $i = 0; $__LIST__ = $vo["images"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$im): $mod = ($i % 2 );++$i;?><img src="/browser/<?php echo ($im); ?>"><?php endforeach; endif; else: echo "" ;endif; ?>
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
		<div id="loading" style="visibility: hidden;">
			   	<?php echo (L("Aloading")); ?>
			   	<img src="/browser/Public/home/img/5-121204193R2-50.gif"/>
		</div>
	  </div>
	</div>	
	
	</div>
	</div>
	</div>
</body>
<script>
   
    function headRef(){
        window.location.reload();    
    }
    var n=0;
    function header(){
    	n++;
       
        $.ajax({
            type:"post",
            url:API+"/Index/reNewsEntCar",
           
            data:{
             nav_mark: "recommend_list",	
             page:n,
             size:6,
            },
            async:true,
            success:function(data){           
//          	console.log(jsonData)
            	 var jsonData=eval('('+data+')');
////           var jsonData=$.parseJSON(data);
////            var jsonData=JSON.parse(data);

               if(n!=1){
	               if(jsonData.result.length<6){
	               	n=0;
	               }
               }
                keys=jsonData.status.page;
                $('#fKe').val(keys);
                var addlist='';
                var images='';
                for(var i=0;i<jsonData.result.length;i++){
                    images='';
                    for(var m=0;m<jsonData.result[i].images.length;m++){
                        images+='<img src="'+jsonData.result[i].images[m]+'">';
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
            url:"/browser/Home/Index/footerLoad.html",
            data:'keys='+keys,
            async:true,
            success:function(data){
                var jsonData=eval('('+data+')');
                keys=jsonData.status.page;
                $('#fKe').val(keys);
                var addlist='';
                var images='';
                for(var i=0;i<jsonData.data.length;i++){
                    images='';
                    for(var m=0;m<jsonData.data[i].images.length;m++){
                        images+='<img src="/browser/'+jsonData.data[i].images[m]+'">';
                    }
                    addlist='<li><a href="'+jsonData.data[i].link_address+'">'+
					'<div class="list-title">'+
						images+
						'<p>'+jsonData.data[i].tit_name+'</p>'+												
					'</div>'+
					'<div class="promte">'+
						'<p class="source">'+							
							jsonData.data[i].come_from+						
						'</p>'+
						'<p class="author">'+
							jsonData.data[i].create_date+
						'</p>'+
					'</div>'+
				'</a>'+
			'</li><hr />';
                        $(".list").html($(".list").html()+addlist);
                }
                 $("#loading").css("visibility", "hidden")
            }
           
        });
        
    }
</script>
</html>