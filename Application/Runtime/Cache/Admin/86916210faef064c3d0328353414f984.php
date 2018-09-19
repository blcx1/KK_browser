<?php if (!defined('THINK_PATH')) exit(); if(C('LAYOUT_ON')): endif; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首页</title> 
<link href="/browser/Public/lib/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" > 
<link rel="stylesheet" type="text/css" href="/browser/Public/css/admin-all.css" />
<style type="text/css">	
	tr td{
		text-align: center !important;
		vertical-align: middle!important; 
	}
	h3{
		text-align: center;
		margin:0px;
		border-bottom: 1px solid rgb(204, 204, 204);
		padding:5px 0px;
		background-color:#eee;
	}
	tr td.td_left{
		text-align:left !important;
	}
	.system-message{
		
		width:auto;
		height:200px;
	}
	.tips_img{
	
		margin:15px;		
		display:inline-block;
		float:left;
	}
	.tips_message{
		
		margin-left:15px;
		float:left;
		display:inline-block;
		
	}
	.message_main{
		
		margin:15px auto;
		width:700px;
	}
	.error,.success{
		
		font-size:22px;
		font-weight:500;
	}
</style>
</head>
<body> 
<div class="alert alert-info" style="padding: 2px;margin-bottom: 4px;">
	<?php echo (L("Position")); ?> <b class="tip"></b><?php echo (L("Home")); ?><b class="tip"></b><?php echo (L("Tips")); ?>
</div>
<div  style="width: 780px; margin: 0 auto;border:1px solid #c3c3c3;border-radius:8px;background-color:#f9f9f9;">
	<div class="system-message">
		<h3><?php echo (L("Tips")); ?></h3>
		<div class="message_main">
		<?php if(isset($message)): ?><div class="tips_img"><img src="/browser/Public/images/success.jpg" width="94" alt="success pic" /></div>
			<div class="tips_message">
				<p class="success"><?php echo ($message); ?> </p>
		<?php else: ?>
			<div class="tips_img"><img src="/browser/Public/images/error.jpg" width="95" alt="error pic" /></div>
			<div class="tips_message">
				<p class="error"><?php echo ($error); ?> </p><?php endif; ?>
				<p class="detail"></p>
				<p class="jump">
				<?php echo (L("ThePage")); ?><a id="href" href="<?php echo ($jumpUrl); ?>"><?php echo (L("WillAutoJump")); ?></a> , <?php echo (L("WaitTime")); ?>： <b id="wait"><?php echo ($waitSecond); ?></b><?php echo (L("TimeSec")); ?> . 
				</p>
				<p><?php echo (L("MessageRedirect")); ?>,<?php echo (L("Please")); ?><a href="<?php echo ($jumpUrl); ?>" title="<?php echo (L("ClickHere")); ?>"><?php echo (L("ClickHere")); ?></a> .</p>
				<p><?php echo (L("ReturnPrePage")); ?>,<?php echo (L("Please")); ?><a href="javascript:void(0);" onclick="javascript:history.go(-1);" title="<?php echo (L("ClickHere")); ?>"><?php echo (L("ClickHere")); ?></a> .</p>
			</div>
		</div>
	</div>			
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
	</body>
</html>