<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo (L("apinavadd")); ?></title>
	<link href="/browser/Public/lib/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/browser/Public/css/admin-all.css" />
	<link rel="stylesheet" type="text/css" href="/browser/Public/css/formui.css"/>
	<link href="/browser/Public/css/listtheme.css" rel="stylesheet">
	<script type="text/javascript" src="/browser/Public/lib/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/browser/Public/lib/My97DatePicker/WdatePicker.js"></script>
	<style type="text/css">
		td {
			text-align: center;
			vertical-align: middle!important;
		}
		.table-condensed tr td:first-child{
			font-weight:700;
		}
		img{
			border: 8px double white;
			border-radius: 15px;
			margin-bottom: 30px;
		}
		[type=checkbox]{
			transform: scale(2,2);
			margin-left: 30px !important;
		}
		.form-control{width:auto;display:inline-block;}
	</style>
</head>
<body >
<div class="alert alert-info" style="padding: 2px;margin-bottom: 4px;">
	<?php echo (L("Position")); ?><b class="tip"></b>
	<?php echo (L("apinav")); ?><b class="tip"></b>
	<?php echo (L("apiadd")); ?>
</div>

<div style="width: 50%;margin:0px auto; padding-top: 10px; min-width: 675px;"  style="text-align:center;">
	<form method="post" action="/browser/Admin/Webnav/addapinav" enctype="multipart/form-data">
		<table class="table  table-striped table-bordered table-condensed " >
			<tr>
				<td>名称：</td>
				<td><input type="text" class="form-control" name="name"></td>
			</tr>

			<tr>
				<td>跳转链接：</td>
				<td><input type="text"  class="form-control" name="link"></td>
			</tr>

			<tr>
				<td>站点图标：<span style="color:red;">&nbsp;&nbsp;*</span></td>
				<td><input class="form-control" type="file" name="pic"/></td>
			</tr>

			<tr>
				<td><?php echo (L("language")); ?></td>
				<td>
					<select name="lang" class="form-control">
						<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</td>
			</tr>

			<tr>
				<td>排序：</td>
				<td>
					<select class="form-control usort" name="sort">

					</select>
				</td>
			</tr>

			<tr>
				<td colspan="2" style="text-align: center;">
					<input type="submit" value="<?php echo (L("Add")); ?>" class="btn4 btn-info btn" />
					<input type="reset" value="<?php echo (L("Reset")); ?>" class="btn4 btn-warning btn" />
				</td>
			</tr>

		</table>
	</form>
</div>
<script type="text/javascript">
    var pxs = '';
    for(var u=1;u<=255;u++){
        pxs +='<option name="'+u+'">'+u+'</option>';
    }
    $('.usort').html(pxs);
</script>
</body>
</html>