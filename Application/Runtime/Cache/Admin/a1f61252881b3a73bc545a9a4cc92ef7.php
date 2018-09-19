<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo ($title); ?></title>
<!--<link rel="stylesheet" type="text/css" href="/browser/Public/css/reset.css"/>
<link rel="stylesheet" type="text/css" href="/browser/Public/css/common.css"/> -->
<link href="/browser/Public/lib/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" > 
<link rel="stylesheet" type="text/css" href="/browser/Public/css/admin-all.css" />
<link rel="stylesheet" type="text/css" href="/browser/Public/css/formui.css"/>
<script src="/browser/Public/lib/jquery-1.8.3.min.js" type='text/javascript'></script> 
<script src="/browser/Public/lib/My97DatePicker/WdatePicker.js" type='text/javascript'></script>
<script src="/browser/Public/lib/layer/layer.min.js" type='text/javascript'></script>
<script src="/browser/Public/js/checked.js" type='text/javascript'></script>
<script type="text/javascript">
function modify_form_action(form_name,url){
	
	var action_url = url.toString();
	form_name = "#" + form_name.toString();
	if(form_name.length > 1 && action_url.length > 0){
		
		$(form_name).attr("action",action_url);
		$(form_name).submit();
	}
	
}
function change_status(id,name,url){
	
	var display_info = '';
	var fix_name = '';
	var ajax_url = url;
	var fix_id = parseInt(id);
	if(confirm('<?php echo (L("ConfirmModify")); ?>')){
		
		if(fix_id > 0 && name.length > 0 && url.length > 0){
		
			fix_name = '#' + name + '_' + fix_id.toString();
			$.ajax({ 
				url: ajax_url,
				data:{'ajax':1,'id':fix_id,'status':name},
				dataType: "json",
				type: "POST",
				success: function(ajaxobj){
					if(ajaxobj.error > 0){
					
						display_info = '<?php echo (L("ModifyFailure")); ?>';
						alert(display_info);
					}else{				
												
						$(fix_name).html(ajaxobj.status_value);
					}			
					
				},
				error:function(ajaxobj)
				{
		//				if(ajaxobj.responseText!='')
		//				alert(ajaxobj.responseText);
				}
			});	
		}
	}
	
}
</script>
<style type="text/css">
	#addAdminUser {
		width: 80%;
	}

	#updateAdminPwdForm{margin-bottom: 5px;margin-top: 5px;}
	.num{
		margin-left: 5px;
		margin-right: 5px;
	}
	tr td{
		text-align: center !important;
		vertical-align: middle!important; 
	}
	.input-group{	
		float:left;
		padding-left:15px;
		padding-top:5px;
	}
	.input-group-addon{
		width:50px;
	}
	.form-control{
		width:120px!important;
	} 
	form{
		width:790px;
		margin:0 auto;
	}
	h3{
		margin-top: 0px;
		margin-bottom: 0px;
	}
	tr td.td_left{
		text-align:left !important;
	}
</style>
</head>
<body> 
<div class="alert alert-info" style="padding: 2px;margin-bottom: 4px;">
	<?php if(is_array($breadNav_array)): foreach($breadNav_array as $k=>$breadNav): echo ($breadNav); ?> <?php if($k != $last_breadNav): ?><b class="tip"></b><?php endif; endforeach; endif; ?>
	<div style="float:right;"> 
		<?php if(is_array($right_array)): foreach($right_array as $k=>$right_value): ?>&nbsp;&nbsp;<a href="<?php echo (filter_set_value($right_value,'href','','string')); ?>" <?php echo (filter_set_value($right_value,'other','','string')); ?>> <?php echo (filter_set_value($right_value,'title','','string')); ?>  </a> &nbsp;&nbsp;<?php endforeach; endif; ?>
		&nbsp;&nbsp;<a href="javascript:void(0);" onclick="javascript:history.go(-1);" title="<?php echo (L("Return")); ?>"> <?php echo (L("Return")); ?>  </a> &nbsp;&nbsp; 
	</div>
</div>
<style>
	tr{
		height:40px;
		line-height:40px;
	}
</style>
<div  style="width: 812px; margin: 0 auto; ">
		<table class="table table-striped table-bordered table-condensed ">
			<tr>
				<td colspan="4" >
				<h3><?php echo (L("LoginInfo")); ?></h3>
				</td>
			</tr>			
			<tr>
				<td><?php echo (L("OS")); ?></td>
				<td class="td_left">
					<?php echo ($detail_info["os"]); ?>
				</td>
				<td><?php echo (L("UseBrowse")); ?></td>
				<td class="td_left"><?php echo ($detail_info["browse"]); ?></td>
			</tr>		
			<tr>
				<td ><?php echo (L("LoginPrevIp")); ?></td>
				<td class="td_left"><?php echo ($detail_info["login_prev_ip"]); ?></td>
				<td ><?php echo (L("LoginLastIp")); ?></td>
				<td class="td_left"><?php echo ($detail_info["login_last_ip"]); ?></td>
			</tr>			
			<tr>
				<td ><?php echo (L("LoginPrev")); echo (L("Address")); ?></td>
				<td class="td_left"><?php echo ($detail_info["login_prev_ip_location"]); ?></td>
				<td ><?php echo (L("LoginLast")); echo (L("Address")); ?></td>
				<td class="td_left"><?php echo ($detail_info["login_last_ip_location"]); ?></td>
			</tr>
			<tr>
				
				<td><?php echo (L("LoginPrevDateTime")); ?></td>
				<td class="td_left"><?php echo ($detail_info["login_prev_datetime"]); ?></td>
				<td><?php echo (L("LoginLastDateTime")); ?></td>
				<td class="td_left"><?php echo ($detail_info["login_last_datetime"]); ?></td>
			</tr>			
			<tr>
				<td>UserAgent</td>
				<td colspan="3" class="td_left"><?php echo (filter_set_value($_SERVER,'HTTP_USER_AGENT','','string')); ?></td>
			</tr>
		</table>		
	</div>


	</body>
</html>