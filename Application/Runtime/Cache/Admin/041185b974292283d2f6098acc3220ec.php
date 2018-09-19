<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html >
<html >
<head>
	<meta charset="UTF-8">
	<title>登录</title>
	<link rel="stylesheet" type="text/css" href="/browser/Public/css/login.css" />
	<script type="text/javascript"src="/browser/Public/lib/jquery-1.8.3.min.js"></script>
	<script src="/browser/Public/lib/layer/layer.min.js"></script>
</head>
<body>
	<div>
	<div class="loginmain">
    </div>
    <div class="row-fluid">
    	
		<div class="BoxWrap">
			<div>
				<h1>垦鑫达浏览器主页后台管理系统</h1>
				<!-- <img style="margin-left: 128px;width: 50px;height: 50px; " src="/browser/Public/images/logo.png" />
			 --></div>
			<form name="form1" method="post" action="/browser/Admin/Index/login"   id="form">
				<div class="row">
					<div class="account bgWrapper">
						<input name="username" type="text" value="请输入帐号"
							class="input_text"
							onfocus="if(this.value == this.defaultValue) this.value = ''"
							onblur="if(this.value == '') this.value = this.defaultValue" />
					</div>
				</div>
				<div class="row">
					<div class="pwd bgWrapper">
						<input name="psw" type="password" value="" class="input_text" />
					</div>
				</div>
				<div class="row"     >
					<div class="code bgWrapper" >
						<input name="code" type="text" value="请输入验证码"
							class="input_code input_text"
							maxlength="4"
							autocomplete="off"
							onfocus="if(this.value == this.defaultValue) this.value = ''"
							onblur="if(this.value == '') this.value = this.defaultValue" />
						<img src="/browser/Admin/Index/verify/87555546145"  id= "verifyimg"/>
					</div>
				</div>
				<div class="row">
					<div class="bgWrapper">
						<input name="button" value="登录" type="submit" id="sub" />
					</div>
				</div>
			</form>
		</div>
	</div>
	</div>
<script type="text/javascript">
//layer.use('extend/layer.ext.js'); //载入layer拓展模块
	$(document).ready(function(){
		
	  $("#verifyimg").attr("src",'/browser/Admin/Index/verify/'+ (new Date()).valueOf() );
	  $("#verifyimg").on("click",function(){
		  $(this).attr("src",'/browser/Admin/Index/verify/'+ (new Date()).valueOf() );
	  });
	  $("#form").submit(function(){
		  	var username = $("input[name='username']").attr("value");
			var password = $("input[name='psw']").attr("value");
			var code = $("input[name='code']").attr("value"); 
			//用户名
			if (username.length == 0 || username == "请输入帐号") {
				 layer.msg("请输入帐号.",1,3);
				return false;
			}
			//密码
			if (password.length == 0) {
				 layer.msg("请输入密码.",1,3);
				return false;
			}
			//验证码
			if (code.length == 0 || code == "请输入验证码") {
				 layer.msg("请输入验证码.",1,3);
				return false;
			}
			return true;
	  });
	  
	  });
</script>
</body>
</html>