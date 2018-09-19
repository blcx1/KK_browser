<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo (L("ShopType")); ?></title>
<link href="/browser/Public/lib/bootstrap/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/browser/Public/css/admin-all.css" />
<link rel="stylesheet" type="text/css" href="/browser/Public/css/formui.css"/>
<link href="/browser/Public/css/listtheme.css" rel="stylesheet">
<script type="text/javascript" src="/browser/Public/lib/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/browser/Public/lib/My97DatePicker/WdatePicker.js"></script>

</head>
<body >
<div class="alert alert-info" style="padding: 2px;margin-bottom: 4px;">
	<?php echo (L("Position")); ?><b class="tip"></b>
	<?php echo (L("ShopManage")); ?><b class="tip"></b>
	<?php echo (L("ShopType")); ?></div>
<div>
	<table  style="width:90%;text-align: center; margin:0 auto;" class="table  table-striped table-bordered table-condensed " >
	<form method="post" action="/browser/Admin/Shop/delShop_type" id="from_delete">
			<tr>
				<td colspan="10" >
					<span  class="btn btn-default select_all"  style='margin: 0 5px;'><?php echo (L("AllChoose")); ?></span>
					<span class="btn btn-default re_select_all" style='margin: 0 5px;'><?php echo (L("ContraryChoose")); ?></span>
					<input id="addBtn" type="button" class="btn btn-default" value="<?php echo (L("Add")); ?>" />
					<input type="submit"  style='margin: 0 5px;' class="btn btn-default" onclick="return confirm('<?php echo (L("DelOK")); ?>？')" value="<?php echo (L("Delete")); ?>"/>
				</td>
			</tr>
			<tr class="tab_list">
				<td style="width: 10%;"><?php echo (L("Check")); ?></td>
				<td style="width: 10%;"><?php echo (L("AdminId")); ?></td>
				<td style="width: 25%;"><?php echo (L("Name")); ?></td>
				<td style="width: 10%;"><?php echo (L("Language")); ?></td>
				<td style="width: 20%;"><?php echo (L("Operate")); ?></td>
			</tr>
			<?php if(is_array($game_type)): $i = 0; $__LIST__ = $game_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td><input class="check" type="checkbox" name="check[]" value="<?php echo ($vo["id"]); ?>"></td>
				<td><?php echo ($vo["id"]); ?></td>
				<td><?php echo ($vo["type_name"]); ?></td>
				<td><?php echo ($vo["languages"]); ?></td>
				<td>
					<button type="button" uid="<?php echo ($vo["id"]); ?>" name="<?php echo ($vo["type_name"]); ?>" lang="<?php echo ($vo["language"]); ?>" class="btn btn-primary btn-sm uprec">修改</button>
					<a href="/browser/Admin/Shop/delShop_type.html?check=<?php echo ($vo["id"]); ?>" class="btn btn-warning btn-sm">删除</a>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		<tr>
			<td colspan="10" style="text-align: center;">
				<span id="page-count"><?php echo ($page); ?></span>
				<span id="page-counts"><?php echo (L("Amount")); ?> : <?php echo ($count); ?></span>
			</td>
		</tr>
		
	</form>
	</table>	
</div>
<!--添加窗口 start-->
<div id="addnav" style="display:none; width:400px; height:220px; position:absolute;z-index:2;top:100px;left:50%;margin-left: -200px; background:white; box-shadow: 5px 15px 10px #ccc;">
    <form method="post" action="/browser/Admin/Shop/addShop_type">
		<div style="width: 100%;height: 35px;line-height: 35px;background:#eee;text-align:center;border-bottom: 1px solid #D5D5D5;font-size: 14px;color: #333;">添加好物类型</div>
		<div style="padding:30px;">
			<div class="input-group">
				<div class="input-group-addon">名称：</div>
				<input class="form-control" type="text" name="name">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon"><?php echo (L("language")); ?>：</div>
				<select name="language" class="form-control">
					<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<input style="margin-left:50px;margin-right:10px;" type="submit" class="btn btn-info" value="添加" />
				<input  id="unsub" type="button" class="btn btn-warning" value="取消">
			</div>
		</div>
    </form>
</div>
<!--添加窗口 end-->
<!--更新窗口 start-->
<div id="updatenav" style="display:none; width:450px; min-height:220px; border:1px solid #eee; position:fixed;z-index:999;top:100px;left:50%; margin-left:-225px; background:white; box-shadow: 5px 15px 10px #ccc;">
	<form action="/browser/Admin/Shop/updateNavShop.html" method="post">
		<input type="hidden" id="uid" name="id" value="" />
		<div style="width: 100%;height: 35px;line-height: 35px;background:#eee;text-align:center;border-bottom: 1px solid #D5D5D5;font-size: 14px;color: #333;">修改好物类型</div>
		<div style="padding:30px;">
			<div class="input-group">
				<div class="input-group-addon">名称：</div>
				<input uid="" id="utname" style="width: 154px;" class="form-control" type="text" name="name">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon"><?php echo (L("language")); ?>：</div>
				<select  id="uttype" name="language" class="form-control">
					<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<input type="submit" class="btn btn-info" style="margin-right:10px;" id="updates" value="更新"/>
				<button type="button" class="btn btn-warning" onclick="$('#updatenav').hide();">取消</button>
			</div>
		</div>
	</form>
</div>
<!--更新窗口 end-->
<script type="text/javascript">
	$(document).ready(function(){
		$(".select_all").click( function () {
			$check = $(".check");
			if($check.attr("checked") != "checked"){
				$check.attr("checked",true);
			}else{
				$check.attr("checked",false);
			}
		});
		$(".re_select_all").click( function () {
			
			$(".check").each(function()	{
				    if($(this).attr("checked") != "checked"){
				    	$(this).attr("checked",true);
					}else{
						$(this).attr("checked",false);
					}
			});
		});
                
		$("#addBtn").click(function(){
		   $("#addnav").show();
		});
		$("#unsub").click(function(){
			$("#addnav").hide();
		});

        //更新操作数据选中
        $('.uprec').each(function(){
            $(this).click(function(){
                $('#utname').val('');
                $('#uttype option').eq(0).prop("selected", 'selected');
                $('#updatenav').show();

                var uid = $(this).attr('uid');
                var name = $(this).attr('name');
                var lang= $(this).attr('lang');

                $('#utname').val(name);
                $('#utname').attr('uid',uid);
                $('#uid').val(uid);
                $('#uttype option[value='+lang+']').prop("selected", 'selected');

            });
        });
	});
</script>
</body>
</html>