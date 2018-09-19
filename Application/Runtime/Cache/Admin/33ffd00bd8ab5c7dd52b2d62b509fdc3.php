<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo (L("apinav")); ?></title>
	<link href="/browser/Public/lib/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/browser/Public/css/admin-all.css" />
	<link rel="stylesheet" type="text/css" href="/browser/Public/css/formui.css"/>
	<link href="/browser/Public/css/listtheme.css" rel="stylesheet">
	<script type="text/javascript" src="/browser/Public/lib/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/browser/Public/lib/My97DatePicker/WdatePicker.js"></script>
	<style type="text/css">
		.form-inline{
			width: 744px;
			margin: 0 auto;
		}
		.input-group{
			width:220px;
			float:left;
			margin: 0 39px;
		}
		.tab_list td{
			font-weight:700;
		}
		.btn-xs, .btn-group-xs > .btn{
			line-height:24px;
		}
	</style>
</head>
<body >
<div class="alert alert-info" style="padding: 2px;margin-bottom: 4px;">
	<?php echo (L("Position")); ?><b class="tip"></b>
	<?php echo (L("apinav")); ?><b class="tip"></b>
	<?php echo (L("apilist")); ?>
	</div>
<div>
	<table  style="width:90%;text-align: center; margin:0 auto;" class="table  table-striped table-bordered table-condensed " >
		<form method="post" action="/browser/Admin/Webnav/delapinav" id="from_delete">
			<tr>
				<td colspan="10" >
					<span  class="btn btn-default select_all"  style='margin: 0 5px;'><?php echo (L("AllChoose")); ?></span>
					<span  class="btn btn-default re_select_all" style='margin: 0 5px;'><?php echo (L("ContraryChoose")); ?></span>
					<a href="/browser/Admin/Webnav/addapinav" class="btn btn-default"><?php echo (L("Add")); ?></a>
					<input type="submit"  style='margin: 0 5px;' class="btn btn-default" onclick="return confirm('<?php echo (L("DelOK")); ?>？')" value="<?php echo (L("Delete")); ?>"/>
				</td>
			</tr>
			<tr class="tab_list">
				<td><?php echo (L("Check")); ?></td>
				<td><?php echo (L("AdminId")); ?></td>
				<td><?php echo (L("Name")); ?></td>
				<td><?php echo (L("Icon")); ?></td>
				<td><?php echo (L("Link")); ?></td>
				<td><?php echo (L("Sort")); ?></td>
				<td><?php echo (L("Lang")); ?></td>
				<td width="10%"><?php echo (L("Operate")); ?></td>
			</tr>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					<td><input class="check" type="checkbox" name="check[]" value="<?php echo ($vo["id"]); ?>"></td>
					<td><?php echo ($vo["id"]); ?></td>
					<td><a href="<?php echo ($vo["link_address"]); ?>" target="_blank"><?php echo ($vo["tit_name"]); ?></a></td>
					<td>
						<?php
 if($vo['icon'] ==''){ echo '无图片'; }else{ if(stristr($vo['icon'],',')){ $path=explode(',',$vo['icon']); foreach($path as $iival){ echo "<a href='/browser/".$iival."' traget='_blank'><img src='/browser/".$iival."' height='30' style='margin-right:6px;'/></a>"; } }else{ echo "<a href='/browser/".$vo[icon]."' traget='_blank'><img src='/browser/".$vo[icon]."' height='30'/></a>"; } } ?>
					</td>
					<td><?php echo ($vo["link_address"]); ?></td>
					<td><?php echo ($vo["sort"]); ?></td>
					<td><?php echo ($vo["languages"]); ?></td>
					<td>
						<button type="button" uid="<?php echo ($vo["id"]); ?>" name="<?php echo ($vo["tit_name"]); ?>" images="<?php echo ($vo["icon"]); ?>" link="<?php echo ($vo["link_address"]); ?>" sort="<?php echo ($vo["sort"]); ?>" lang="<?php echo ($vo["language"]); ?>" class="btn btn-primary btn-sm uprec">修改</button>
						<a href="/browser/Admin/Webnav/delapinav?check=<?php echo ($vo["id"]); ?>" class="btn btn-warning btn-sm">删除</a>
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

<!--更新窗口 start-->
<div id="updatenav" style="display:none; width:500px; min-height:420px; padding-bottom:20px;border:1px solid #eee; position:fixed;z-index:999;top:100px;left:50%; margin-left:-250px; background:white; box-shadow: 5px 15px 10px #ccc;">
	<form action="/browser/Admin/Webnav/updateapinav.html" method="post" enctype="multipart/form-data">
		<input type="hidden" id="uid" name="id" value="" />
		<div style="width: 100%;height: 35px;line-height: 35px;background:#eee;text-align:center;border-bottom: 1px solid #D5D5D5;font-size: 14px;color: #333;">修改api导航</div>
		<div style="padding:30px;">
			<div class="input-group">
				<div class="input-group-addon">名称：</div>
				<input type="text" id="uname" style="width:200px;" class="form-control" name="name">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">跳转链接：</div>
				<input type="text" style="width:200px;" id="ulink" class="form-control" name="link">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div id="uimg"></div>
			</div>

			<input id="uimgval" type="hidden" name="pic" value=""/>

			<div class="input-group" style="margin-top:10px;">
				<lable id="wenjian" class="filel" style="display: inline-block;">上传图片：&nbsp;
					<input type="file" name="pic" class="form-control"/>
				</lable>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon"><?php echo (L("language")); ?>：</div>
				<select id="uttype" name="lang" class="form-control">
					<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">排序：</div>
				<select class="form-control" id="usort" name="sort">

				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<input type="submit" class="btn btn-info" id="updates" style="margin-right:10px;" value="更新">&nbsp;&nbsp;&nbsp;
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
                $('#updatenav').show();

                var uid = $(this).attr('uid');
                var name = $(this).attr('name');
                var link= $(this).attr('link');
                var images= $(this).attr('images');
                var sort = $(this).attr('sort');
                var lang = $(this).attr('lang');

                $('#uname').val(name);
                $('#uid').val(uid);
                $('#ulink').val(link);
                $('#uimgval').val(images);
                $('#usort option[value='+sort+']').prop("selected", 'selected');
                $('#uttype option[value='+lang+']').prop("selected", 'selected');
                //插入图片显示
                var img='';
                for(i=0;i<images.split(',').length;i++){
                    img+="<img style='margin-right:3px;' src=\"/browser/"+images.split(',')[i]+"\" height='65'/>";
                }
                $('#uimg').html(img);
            });
        });
		var pxs = '';
        for(var u=1;u<=255;u++){
			pxs +='<option name="'+u+'">'+u+'</option>';
		}
		$('#usort').html(pxs);
    });
</script>
</body>
</html>