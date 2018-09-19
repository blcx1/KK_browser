<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo (L("AdverList")); ?></title>
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
	<?php echo (L("AdverManage")); ?><b class="tip"></b>
	<?php if((ACTION_NAME== 'ImpotAddList') OR (ACTION_NAME== 'impotaddlist')): ?>当前已导入列表<?php else: echo (L("AdverList")); endif; ?>
	</div>
<div>
	<table  style="width:90%;text-align: center; margin:0 auto;" class="table  table-striped table-bordered table-condensed " >
		<form method="post" action="/browser/Admin/Webnav/deladver?act=<?php echo (ACTION_NAME); ?>" id="from_delete">
			<tr>
				<td colspan="10" >
					<span  class="btn btn-default select_all"  style='margin: 0 5px;'><?php echo (L("AllChoose")); ?></span>
					<span  class="btn btn-default re_select_all" style='margin: 0 5px;'><?php echo (L("ContraryChoose")); ?></span>
					<a href="/browser/Admin/Webnav/addAdver" class="btn btn-default"><?php echo (L("Add")); ?></a>
					<input type="submit"  style='margin: 0 5px;' class="btn btn-default" onclick="return confirm('<?php echo (L("DelOK")); ?>？')" value="<?php echo (L("Delete")); ?>"/>
				</td>
			</tr>
			<tr class="tab_list">
				<td style="width: 10%;"><?php echo (L("Check")); ?></td>
				<td style="width: 10%;"><?php echo (L("AdminId")); ?></td>
				<td style="width: 10%;"><?php echo (L("Type")); ?></td>
				<td style="width: 20%;"><?php echo (L("Name")); ?></td>
				<td style="width: 20%;"><?php echo (L("Icon")); ?></td>
				<td style="width: 10%;"><?php echo (L("Link")); ?></td>
				<td width="10%"><?php echo (L("Operate")); ?></td>
			</tr>
			<?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					<td><input class="check" type="checkbox" name="check[]" value="<?php echo ($vo["id"]); ?>"></td>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["name"]); ?></td>
					<td><a href="<?php echo ($vo["link_address"]); ?>" target="_blank"><?php echo ($vo["tit_name"]); ?></a></td>
					<td>
						<?php
 if($vo['icon_image'] ==''){ echo '无图片'; }else{ if(stristr($vo['icon_image'],',')){ $path=explode(',',$vo['icon_image']); foreach($path as $iival){ echo "<a href='/browser/".$iival."' traget='_blank'><img class='imgh' src='/browser/".$iival."' height='30' style='margin-right:6px;'/></a>"; } }else{ echo "<a href='/browser/".$vo[icon_image]."' traget='_blank'><img class='imgh' src='/browser/".$vo[icon_image]."' height='30'/></a>"; } } ?>
					</td>
					<td><?php echo ($vo["link_address"]); ?></td>
					<td>
						<button type="button" uid="<?php echo ($vo["id"]); ?>" nid="<?php echo ($vo["nid"]); ?>" name="<?php echo ($vo["tit_name"]); ?>" images="<?php echo ($vo["icon_image"]); ?>" link="<?php echo ($vo["link_address"]); ?>" lang="<?php echo ($vo["language"]); ?>" class="btn btn-primary btn-sm uprec">修改</button>
						<a href="/browser/Admin/Webnav/deladver?check=<?php echo ($vo["id"]); ?>&act=<?php echo (ACTION_NAME); ?>" class="btn btn-warning btn-sm">删除</a>
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
<div id="updatenav" style="display:none; width:500px; min-height:480px; padding-bottom:20px;border:1px solid #eee; position:fixed;z-index:999;top:100px;left:50%; margin-left:-250px; background:white; box-shadow: 5px 15px 10px #ccc;">
	<form action="/browser/Admin/Webnav/updateAdver.html" method="post" enctype="multipart/form-data">
		<input type="hidden" id="uid" name="id" value="" />
		<div style="width: 100%;height: 35px;line-height: 35px;background:#eee;text-align:center;border-bottom: 1px solid #D5D5D5;font-size: 14px;color: #333;">修改广告内容</div>
		<div style="padding:30px;">

			<div class="input-group">
				<div class="input-group-addon">名称：</div>
				<input type="text" style="width:200px;" uid="" id="utname" class="form-control" name="name">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">语言：</div>
				<select class="form-control" name="language" >
					<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">位置：</div>
				<select class="form-control" id="uttype" name="nid">
					<?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nvo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($nvo["id"]); ?>"><?php echo ($nvo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div>图片：</div>
				<p id="images"></p>
			</div>
			<div class="input-group" style="margin-top:3px;">
				<div class="inp-up">图片一：</div>
				<input class="form-control radius" style="width:250px; left: 42px;" type="file" name="pic1" />
				<div class="shade" style="left: 33%;">未上传文件</div>    
				
			</div>
			<div class="input-group" style="margin-top:3px;">
				<div class="inp-up">图片二：</div>
				<input class="form-control radius" style="width:250px;left: 42px;" type="file" name="pic2" />
				<div class="shade" style="left: 33%;">未上传文件</div>    
				
			</div>
			<div class="input-group" style="margin-top:3px;">
				<div class="inp-up">图片三：</div>
				<input class="form-control radius" style="width:250px;left: 42px;" type="file" name="pic3" />
				<div class="shade" style="left: 33%;">未上传文件</div>    
				
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">跳转链接：</div>
				<input type="text" style="width:200px;" uid="" id="link" class="form-control" name="link">
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


        $('#upmore').click(function(){

			$('#wenjian').append('<lable class=\"filel\" style="display: inline-block;"><div class="inp-up">上传图片：</div><input class="form-control radius" style="display: inline-block;" type="file" name="pic[]"/></lable>');

        });

        //更新操作数据选中
        $('.uprec').each(function(){
            $(this).click(function(){
                $('#utname').val('');
                $('#uttype option').eq(0).prop("selected", 'selected');
                $('#updatenav').show();

                var uid = $(this).attr('uid');
                var nid = $(this).attr('nid');
                var name = $(this).attr('name');
                var link= $(this).attr('link');
                var images= $(this).attr('images');
                var lang = $(this).attr('lang');

                $('#utname').val(name);
                $('#utname').attr('uid',uid);
                $('#uid').val(uid);
                $('select[name=language] option[value='+lang+']').prop("selected", 'selected');
                language();
                setTimeout(function(){
                    $('#uttype option[value='+nid+']').prop("selected", 'selected');
				},1000);

                //插入图片显示
                var img='';
                for(i=0;i<images.split(',').length;i++){
                    img+="<img style='margin-right:3px; margin-bottom:3px;' src=\"/browser/"+images.split(',')[i]+"\" height='65'/>";
                }
                $('#images').html(img);
				$('#link').val(link);
            });
        });
         $("img").on("mouseenter",function(){
	    	$(this).parents("td").append('<img id="img-space" src="" style="width: 50px;height: 30px;"/>').addClass("posi");
	    	$(this).addClass("imgh-scale");
	    })
	    $("img").on("mouseleave",function(){
	    	$("#img-space").remove();
	    	$(this).parents("td").removeClass("posi");
	    	$(this).removeClass("imgh-scale");
	    })

        function language(){
            $.ajax({
                type:'post',
                url:'/browser/Admin/Webnav/langType.html',
                data:{language:$("select[name='language'] option:selected").val()},
                async:true,
                success:function(data){
                    var option='';
                    for(var i=0;i<data.length;i++){
                        option+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                    }
                    $("select[name='nid']").html(option);
                }
            });
        }
        $("select[name='language']").change(function(){
            language();
        });
    });
</script>
</body>
</html>