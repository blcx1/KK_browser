<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>浏览器●<?php echo (L("kenxinda")); ?></title>
    <link rel="stylesheet" type="text/css" href="/browser/Public/css/admin-all.css" />
    <link rel="stylesheet" type="text/css" href="/browser/Public/css/base.css" />
    <link rel="stylesheet" type="text/css" href="/browser/Public/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/browser/Public/css/ui-lightness/jquery-ui-1.8.22.custom.css" />
 	<script src="/browser/Public/lib/jquery-1.8.3.min.js" type='text/javascript'></script>
	<script src="/browser/Public/lib/layer/layer.min.js" type='text/javascript'></script>
    <script type="text/javascript" src="/browser/Public/js/jquery-1.7.2.js"></script>
    <script type="text/javascript" src="/browser/Public/js/jquery-ui-1.8.22.custom.min.js"></script>
    <script type="text/javascript" src="/browser/Public/js/indexs.js"></script>
    <style type="text/css">
  	  a:focus{outline: none;}
  	  a:link{
  	  text-decoration: none;
  	  }
  	  .kenxinda{
  	  	color: #fffbff; 
  	  	margin-left: 30px 0px 0px 180px; 
  	  	position: absolute;
    	width: 450px; 
   		bottom: 18px;
    	left: 150px; 
    	font-size:22px;
  	  }
    </style>
</head>
<body style="overflow-x:hidden;">
    <div class="warp">
        <!--头部开始-->
        <div class="top_c">
        <div class="kenxinda">浏览器●<?php echo (L("kenxinda")); ?></div>
            <div class="top-menu">
            	<div class="top-scroll">

                <ul class="top-menu-nav">
                  	<li><a href="#"><?php echo (L("Home")); ?></a></li>
                	 <?php if(is_array($cateList)): $i = 0; $__LIST__ = $cateList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vodata): $mod = ($i % 2 );++$i;?><li><a href="#"><?php echo ($vodata["topname"]); ?><i class="tip-up"></i></a>
	                    	<ul class="kidc">
	                    		<?php if(is_array($vodata["data"])): $i = 0; $__LIST__ = $vodata["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(authcheck($vo['alink'],$_SESSION['userid'])): ?><li><b class="tip"></b><a target="mainFrame" href="/browser/<?php echo ($vo["alink"]); ?>"><?php echo ($vo["catename"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	                   		</ul>
	                   	</li><?php endforeach; endif; else: echo "" ;endif; ?>
	            </ul>
	            </div>
            </div>
            <div class="top-nav" style="width:646px;"><?php echo (L("welcome")); ?>，<?php echo ($_SESSION["username"]); ?>！&nbsp;&nbsp;
            <a target="mainFrame" href="/browser/Admin/Admin/updateAdminPwd.html"><?php echo (L("pwd")); ?>
            </a> | <a id="quit" class="quit"  href=""><?php echo (L("exit")); ?>
            </a> | <a href="/browser/Admin/index/main.html?l=zh-cn" >简体中文
            </a> | <a href="/browser/Admin/index/main.html?l=en-us">English</a> | <a href="/browser/Admin/Index/cleanCache"><?php echo (L("ClearCache")); ?></a>
            </div> 
        </div>
        <!--头部结束-->
        <!--左边菜单开始-->
        <div class="left_c left">
            <h1><?php echo (L("Menu")); ?></h1>
            <div class="acc">
            
            <?php if(is_array($cateList)): $i = 0; $__LIST__ = $cateList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vodata): $mod = ($i % 2 );++$i;?><div>
	                    <a class="one"><?php echo ($vodata["topname"]); ?></a>
	                    <ul class="kid">
	                    	<?php if(is_array($vodata["data"])): $i = 0; $__LIST__ = $vodata["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(authcheck($vo['alink'],$_SESSION['userid'])): ?><li><b class="tip"></b><a target="mainFrame"  href="/browser/<?php echo ($vo["alink"]); ?>"><?php echo ($vo["catename"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	                    </ul>
	                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            <div id="datepicker"></div>
            </div>

        </div>
        <!--左边菜单结束-->
        <!--右边框架开始-->
        <div class="right_c">
            <div class="nav-tip" onclick="javascript:void(0)">&nbsp;</div>
        </div>
        <div class="mainFrame">
            <iframe name="mainFrame" id="mainFrame" src="<?php echo getBaseURL('localhost');?>Admin/Index/defaultHtml"></iframe>
        </div>
        <!--右边框架结束-->

        <!--底部开始-->
        <div class="bottom_c">kenxinda 2017 深圳垦鑫达科技有限公司</div>
        <!--底部结束-->
    </div>
    <script  type="text/javascript" >
		layer.use('extend/layer.ext.js'); //载入layer拓展模块
		$(document).ready(function(){
  			/**
			 * 退出系统
			 */
			$("#quit").click(function(){
				layer.confirm("<?php echo (L("exitOK")); ?>?",function(index){
					window.location = "/browser/Admin/Index/logout";
					layer.close(index);
				},"<?php echo (L("Warning")); ?>",function(index){
					 layer.close(index);
				});
				return false;
			});
		});
		</script>
</body>
</html>