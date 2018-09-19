<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo (L("BeautyList")); ?></title>
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
	<?php echo (L("BeautyManage")); ?><b class="tip"></b>
	<?php if(strtolower(ACTION_NAME)== 'impotaddlist'): ?>当前已导入列表<?php elseif(ACTION_NAME== 'off_put_list'): echo (L("BeautyList(Unpublished)")); else: echo (L("BeautyList")); endif; ?>
	</div>
<div>
	<table  style="width:90%;text-align: center; margin:0 auto;" class="table  table-striped table-bordered table-condensed " >
	<form method="post" action="/browser/Admin/Beauty/delBeauty?act=<?php echo (ACTION_NAME); ?>" id="from_delete">
			<tr>
				<td colspan="10" >
					<span  class="btn btn-default select_all"  style='margin: 0 5px;'><?php echo (L("AllChoose")); ?></span>
					<span  class="btn btn-default re_select_all" style='margin: 0 5px;'><?php echo (L("ContraryChoose")); ?></span>
					<a class="btn btn-default" href="/browser/Admin/Beauty/addBeauty"><?php echo (L("Add")); ?></a>
					<input type="submit"  style='margin: 0 5px;' class="btn btn-default" onclick="return confirm('<?php echo (L("DelOK")); ?>？')" value="<?php echo (L("Delete")); ?>"/>
                                        <?php if((strtolower(ACTION_NAME)== 'impotaddlist') OR (ACTION_NAME== 'off_put_list')): ?><input type="submit" id="put" style="margin: 0 5px;" class="btn btn-default" onclick="return confirm('确认对选中的项提交发布？')" value="发布" /><?php endif; ?>
                                        <?php if(strtolower(ACTION_NAME)== 'beautylist'): ?><input type="submit" id="removed" style="margin: 0 5px;" class="btn btn-default" onclick="return confirm('确认对选中的项下架处理？')" value="下架" /><?php endif; ?>
                                </td>
			</tr>
			<tr class="tab_list">
				<td style="width: 10%;"><?php echo (L("Check")); ?></td>
				<td style="width: 10%;"><?php echo (L("AdminId")); ?></td>
				<td style="width: 25%;"><?php echo (L("TypeName")); ?></td>
				<td style="width: 10%;"><?php echo (L("Icon")); ?></td>
				<td style="width: 10%;"><?php echo (L("language")); ?></td>
				<td style="width: 15%;"><?php echo (L("Operate")); ?></td>
			</tr>
			<?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td><input class="check" type="checkbox" name="check[]" value="<?php echo ($vo["id"]); ?>"></td>
				<td><?php echo ($vo["id"]); ?></td>
				<td><?php echo ($vo["type_name"]); ?></td>
				<td >
                                    <a href="<?php echo ($vo["icon_image"]); ?>" target="_blank"><img class="imgh" src="<?php echo ($vo["icon_image"]); ?>" height="30" /></a>
				</td>
				<td><?php echo ($vo["languages"]); ?></td>
				<td>
					<button type="button" uid="<?php echo ($vo["id"]); ?>" cate="<?php echo ($vo["cid"]); ?>" lang="<?php echo ($vo["language"]); ?>" link="<?php echo ($vo["link_address"]); ?>" image="<?php echo ($vo["icon_image"]); ?>" class="btn btn-primary btn-sm uprec">修改</button>
					<a href="/browser/Admin/Beauty/delBeauty.html?check=<?php echo ($vo["id"]); ?>&act=<?php echo (ACTION_NAME); ?>" class="btn btn-warning btn-sm">删除</a>
                                        <?php if(strtolower(ACTION_NAME)== 'beautylist'): ?><a href="/browser/Admin/Beauty/removed.html?check=<?php echo ($vo["id"]); ?>" class="btn btn-warning btn-sm">下架</a><?php endif; ?>
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
<div id="updatenav" style="display:none; width:500px; min-height:320px; padding-bottom:20px;border:1px solid #eee; position:fixed;z-index:999;top:100px;left:50%; margin-left:-250px; background:white; box-shadow: 5px 15px 10px #ccc;">
	<form action="/browser/Admin/Beauty/updateBeautyList.html" method="post" enctype="multipart/form-data">
		<input type="hidden" id="uid" name="id" value="" />
		<div style="width: 100%;height: 35px;line-height: 35px;background:#eee;text-align:center;border-bottom: 1px solid #D5D5D5;font-size: 14px;color: #333;">修改美女内容</div>
		<div style="padding:30px;">
			<div class="input-group">
				<div class="input-group-addon">语言：</div>
				<select class="form-control" name="language" >
					<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group">
				<div class="input-group-addon">分类：</div>
				<select id="utnav" class="form-control" name="cate">
					<?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cateval): $mod = ($i % 2 );++$i;?><option class="form-control" value="<?php echo ($cateval["id"]); ?>"><?php echo ($cateval["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<p id="images"></p>
			</div>
			<div class="input-group" style="margin-top:10px;">
				<div class="inp-up" style="left: 0;">上传图片：</div>
				<input  type="file" name="pic" class="form-control radius" style="margin-left: 100px;"/>
				<div class="shade">未上传文件</div>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">跳转链接：</div>
				<input style="width: 240px;" type="text" uid="" id="link"  class="form-control" name="link">
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
    var CONTROLLER='/browser/Admin/Beauty/';
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

        //更新操作数据选中
        $('.uprec').each(function(){
            $(this).click(function(){
                $('#utname').val('');
                $('#uttype option').eq(0).prop("selected", 'selected');
                $('#updatenav').show();

                var uid = $(this).attr('uid');
                var name = $(this).attr('name');
                var lang= $(this).attr('lang');
                var image = $(this).attr('image')
				var cid = $(this).attr('cate');
                var link = $(this).attr('link');

                $('#uid').val(uid);
                $('select[name=language] option[value='+lang+']').prop("selected", 'selected');
                language();
                setTimeout(function(){
                    $('#utnav option[value='+cid+']').prop("selected", 'selected');
				},1000);

                //插入图片显示
                var img = "<img src='"+image+"' height='65'/>";
                $('#images').html(img);
                $('#link').val(link);

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

        function language(){
            $.ajax({
                type:'post',
                url:'/browser/Admin/Beauty/langType.html',
                data:{language:$("select[name='language'] option:selected").val()},
                async:true,
                success:function(data){
                    var option='';
                    for(var i=0;i<data.length;i++){
                        option+='<option value="'+data[i].id+'">'+data[i].type_name+'</option>';
                    }
                    $("select[name='cate']").html(option);
                }
            });
        }
        $("select[name='language']").change(function(){
            language();
        });
        $.getScript('/browser/Public/js/put.js');
	});
</script>
</body>
</html>