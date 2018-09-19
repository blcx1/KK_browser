<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo (L("VideoList")); ?></title>
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
	<?php echo (L("VideoManage")); ?><b class="tip"></b>
        <?php if(strtolower(ACTION_NAME)== 'impotaddlist'): ?>当前已导入列表<?php elseif(ACTION_NAME== 'off_put_list'): echo (L("VideoList(Unpublished)")); else: echo (L("VideotList")); endif; ?>
</div>
<div>
	<table  style="width:90%;text-align: center; margin:0 auto;" class="table  table-striped table-bordered table-condensed " >
		<form method="post" action="/browser/Admin/Video/delVideo.html?act=<?php echo (ACTION_NAME); ?>" id="from_delete">
			<tr>
				<td colspan="10" >
					<span  class="btn btn-default select_all"  style='margin: 0 5px;'><?php echo (L("AllChoose")); ?></span>
					<span  class="btn btn-default re_select_all" style='margin: 0 5px;'><?php echo (L("ContraryChoose")); ?></span>
					<a href="/browser/Admin/Video/addVideo" class="btn btn-default"><?php echo (L("Add")); ?></a>
					<input type="submit"  style='margin: 0 5px;' class="btn btn-default" onclick="return confirm('<?php echo (L("DelOK")); ?>？')" value="<?php echo (L("Delete")); ?>"/>
                                        <?php if((strtolower(ACTION_NAME)== 'impotaddlist') OR (ACTION_NAME== 'off_put_list')): ?><input type="submit" id="put" style="margin: 0 5px;" class="btn btn-default" onclick="return confirm('确认对选中的项提交发布？')" value="发布" /><?php endif; ?>
                                        <?php if(strtolower(ACTION_NAME)== 'videolist'): ?><input type="submit" id="removed" style="margin: 0 5px;" class="btn btn-default" onclick="return confirm('确认对选中的项下架处理？')" value="下架" /><?php endif; ?>
                                </td>
			</tr>
			<tr class="tab_list">
				<td style="width: 5%;"><?php echo (L("Check")); ?></td>
				<td style="width: 5%;"><?php echo (L("AdminId")); ?></td>
				<td style="width: 25%;"><?php echo (L("Name")); ?></td>
				<td style="width: 15%;"><?php echo (L("Icon")); ?></td>
				<td style="width: 20%;"><?php echo (L("CreateTime")); ?></td>
				<td style="width: 10%;"><?php echo (L("From")); ?></td>
				<td style="width: 10%;"><?php echo (L("language")); ?></td>
				<td style="width: 10%;"><?php echo (L("Operate")); ?></td>
			</tr>
			<?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					<td><input class="check" type="checkbox" name="check[]" value="<?php echo ($vo["id"]); ?>"></td>
					<td><?php echo ($vo["id"]); ?></td>
					<td ><div class="tite-out"><a href="<?php echo ($vo["link_address"]); ?>"title="<?php echo ($vo["tit_name"]); ?>" target="_blank"><?php echo ($vo["tit_name"]); ?></a></div></td>
					<td >
                                             <a href="<?php echo ($vo["icon_image"]); ?>" traget="_blank"><img class="imgh" src="<?php echo ($vo["icon_image"]); ?>" height="30"/></a>
					</td>
					<td><?php echo ($vo["create_date"]); ?></td>
					<td><?php echo ($vo["come_from"]); ?></td>
					<td><?php echo ($vo["languages"]); ?></td>
					<td>
						<button type="button" uid="<?php echo ($vo["id"]); ?>" name="<?php echo ($vo["tit_name"]); ?>" images="<?php echo ($vo["icon_image"]); ?>" link = "<?php echo ($vo["link_address"]); ?>" from="<?php echo ($vo["come_from"]); ?>" lang = "<?php echo ($vo["language"]); ?>" class="btn btn-primary btn-sm uprec">修改</button>
						<a href="/browser/Admin/Video/delVideo.html?check=<?php echo ($vo["id"]); ?>&act=<?php echo (ACTION_NAME); ?>" class="btn btn-warning btn-sm">删除</a>
                                               <?php if(strtolower(ACTION_NAME)== 'videolist'): ?><a href="/browser/Admin/Video/removed.html?check=<?php echo ($vo["id"]); ?>" class="btn btn-warning btn-sm">下架</a><?php endif; ?>
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
<div id="updatenav" style="display:none; width:500px; min-height:400px; padding-bottom:20px;border:1px solid #eee; position:fixed;z-index:999;top:100px;left:50%; margin-left:-250px; background:white; box-shadow: 5px 15px 10px #ccc;">
	<form action="/browser/Admin/Video/updateVideo.html" method="post" enctype="multipart/form-data">
		<input type="hidden" id="uid" name="id" value="" />
		<div style="width: 100%;height: 35px;line-height: 35px;background:#eee;text-align:center;border-bottom: 1px solid #D5D5D5;font-size: 14px;color: #333;">修改视频内容</div>
		<div style="padding:30px;">
			<div class="input-group">
				<div class="input-group-addon">名称：</div>
				<input style="width: 280px;" type="text" uid="" id="utname"  class="form-control" name="name">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon"><?php echo (L("language")); ?>：</div>
				<select id="uttype" name="language" class="form-control">
					<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<p id="images"></p>
			</div>
			<div class="input-group" style="margin-top:10px;">
				<div class="inp-up">上传图片：</div>
				<input style="width: 280px;left: 28%;" type="file" name="pic" class="form-control radius"/>
				<div class="shade" style="left: 49%;">未上传文件</div>    
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">跳转链接：</div>
				<input style="width: 255px;" type="text" uid="" id="link"  class="form-control" name="link">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">来源：</div>
				<input style="width: 283px;" type="text" uid="" id="from"  class="form-control" name="from">
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
    var CONTROLLER='/browser/Admin/Video/';
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
                var link= $(this).attr('link');
                var images= $(this).attr('images');
                var lang = $(this).attr('lang');
                var from = $(this).attr('from');

                $('#utname').val(name);
                $('#utname').attr('uid',uid);
                $('#uid').val(uid);
                $('#uttype option[value='+lang+']').prop("selected", 'selected');
                //插入图片显示
				var img = '';
				img = "<img src='"+images+"' height='65'/>";

                $('#images').html(img);
                $('#link').val(link);
                $('#from').val(from);
            });
        });
        $("img").on("mouseenter",function(){
          	var imgurl=$(this).attr("src")        
          	$("body").prepend('<img src='+imgurl+' class="imgh-scale img" style=" border: 8px double #9E9E9E;"/>')	
	    })
	    $("img").on("mouseleave",function(){
	    	$(".imgh-scale").remove();    	
	    })
	    
	    $("input[type=file]").live("change",function(e){
	    	$(this).next().text(e.currentTarget.files[0].name)
	    })
        $.getScript('/browser/Public/js/put.js');
    });
</script>
</body>
</html>