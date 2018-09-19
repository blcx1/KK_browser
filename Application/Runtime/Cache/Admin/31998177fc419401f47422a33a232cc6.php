<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo (L("FunnyList")); ?></title>
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
	<?php echo (L("EntertainmentManage")); ?><b class="tip"></b>
        <?php if((ACTION_NAME== 'ImpotAddList') OR (ACTION_NAME== 'impotaddlist')): ?>当前已导入列表<?php elseif(ACTION_NAME== 'off_put_list'): ?>娱乐列表(未发布)<?php else: echo (L("EntertainmentList")); endif; ?>
</div>
<div>
	<table  style="width:90%;text-align: center; margin:0 auto;" class="table  table-striped table-bordered table-condensed " >
		<form method="post" action="/browser/Admin/Entertainment/delEntertainmentList.html?act=<?php echo (ACTION_NAME); ?>" id="from_delete">
			<tr>
				<td colspan="10" >
					<span  class="btn btn-default select_all"  style='margin: 0 5px;'><?php echo (L("AllChoose")); ?></span>
					<span  class="btn btn-default re_select_all" style='margin: 0 5px;'><?php echo (L("ContraryChoose")); ?></span>
					<a href="/browser/Admin/Entertainment/addEntertainment_list" class="btn btn-default"><?php echo (L("Add")); ?></a>
					<input type="submit"  style='margin: 0 5px;' class="btn btn-default" onclick="return confirm('<?php echo (L("DelOK")); ?>？')" value="<?php echo (L("Delete")); ?>"/>
                                        <?php if((ACTION_NAME== 'ImpotAddList') OR (ACTION_NAME== 'impotaddlist') OR (ACTION_NAME== 'off_put_list')): ?><input type="submit" id="put" style="margin: 0 5px;" class="btn btn-default" onclick="return confirm('确认对选中的项提交发布？')" value="发布" /><?php endif; ?>
                                        <?php if(ACTION_NAME== 'entertainment_list'): ?><input type="submit" id="removed" style="margin: 0 5px;" class="btn btn-default" onclick="return confirm('确认对选中的项下架处理？')" value="下架" /><?php endif; ?>
                                </td>
			</tr>
			<tr class="tab_list">
				<td width="5%"><?php echo (L("Check")); ?></td>
				<td width="5%"><?php echo (L("AdminId")); ?></td>
				<td width="25%"><?php echo (L("Name")); ?></td>
				<td width="20%"><?php echo (L("Icon")); ?></td>
				<td width="10%"><?php echo (L("CreateTime")); ?></td>
				<td width="10%"><?php echo (L("From")); ?></td>
				<td width="10%"><?php echo (L("language")); ?></td>
                <td width="5%"><?php echo (L("IsTop")); ?></td>
				<td width="10%"><?php echo (L("Operate")); ?></td>
			</tr>
			<?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
					<td><input class="check" type="checkbox" name="check[]" value="<?php echo ($vo["id"]); ?>"></td>
					<td><?php echo ($vo["id"]); ?></td>
					<td ><div style="width:100%;line-height:30px;height:30px;overflow:hidden;">
						<a href="<?php echo ($vo["link_address"]); ?>" title="<?php echo ($vo["tit_name"]); ?>" target="_blank"><?php echo ($vo["tit_name"]); ?></a>
					</div></td>
					<td>
					<?php if(is_array($vo["icon_image"])): $i = 0; $__LIST__ = $vo["icon_image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$image): $mod = ($i % 2 );++$i;?><a href="<?php echo ($image); ?>"><img class="imgh" src="<?php echo ($image); ?>" height='30' style="margin-right:2px"></a><?php endforeach; endif; else: echo "" ;endif; ?>
					</td>
					<td><?php echo ($vo["create_date"]); ?></td>
					<td><?php echo ($vo["come_from"]); ?></td>
					<td><?php echo ($vo["language"]); ?></td>
                    <td><?php echo ($vo["is_top"]); ?></td>
					<td>
						<button type="button" uid="<?php echo ($vo["id"]); ?>" name="<?php echo ($vo["tit_name"]); ?>" images="<?php echo implode(',',$vo[icon_image]); ?>" link="<?php echo ($vo["link_address"]); ?>" from="<?php echo ($vo["come_from"]); ?>" lang="<?php echo ($vo["languages"]); ?>" istop="<?php echo ($vo["is_top"]); ?>" class="btn btn-primary btn-sm uprec">修改</button>
						<a href="/browser/Admin/Entertainment/delEntertainmentList.html?check=<?php echo ($vo["id"]); ?>&act=<?php echo (ACTION_NAME); ?>" class="btn btn-warning btn-sm">删除</a>
                                                <?php if(ACTION_NAME== 'entertainment_list'): ?><a href="/browser/Admin/Entertainment/removed.html?check=<?php echo ($vo["id"]); ?>" class="btn btn-warning btn-sm">下架</a><?php endif; ?>
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
<div id="updatenav" style="display:none; width:500px; padding-bottom:20px;min-height:520px; border:1px solid #eee; position:fixed;z-index:999;top:100px;left:50%; margin-left:-250px; background:white; box-shadow: 5px 15px 10px #ccc;">
	<form action="/browser/Admin/Entertainment/updateEmter.html" method="post" enctype="multipart/form-data">
		<div style="width: 100%;height: 35px;line-height: 35px;background:#eee;text-align:center;border-bottom: 1px solid #D5D5D5;font-size: 14px;color: #333;">修改娱乐内容</div>
		<div style="padding:30px;">
			<input type="hidden" value="" name="id" id="uid"/>
			<div class="input-group">
				<div class="input-group-addon">名称：</div>
				<input type="text" uid="" id="utname" style="width:270px" class="form-control" name="name">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon"><?php echo (L("language")); ?>：</div>
				<select id="uttype" name="language" class="form-control">
					<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">头条：</div>
				<select class="form-control" id="utnav" name="cate">
					<option value="no">no</option>
					<option value="yes">yes</option>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div>图片：</div>
				<p id="images"></p>
			</div>
			<div class="input-group" style="margin-top:3px;">
				<div class="inp-up">图片一：</div>
				<input class="form-control radius t-radius"  type="file" name="pic1"value="点击上传图片" />
				 <div class="shade" style="left: 40%;">未上传文件</div>  
			</div>
			<div class="input-group" style="margin-top:3px;">
				<div class="inp-up">图片二：</div>
				<input class="form-control radius t-radius"  type="file" name="pic2" />
				 <div class="shade" style="left: 40%;">未上传文件</div>  
			</div>
			<div class="input-group" style="margin-top:3px;">
				<div class="inp-up">图片三：</div>
				<input class="form-control radius t-radius"  type="file" name="pic3" />
				 <div class="shade" style="left: 40%;">未上传文件</div>  
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">跳转链接：</div>
				<input id="links" style="width:250px;" class="form-control" type="text" name="link">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">来源：</div>
				<input class="form-control" id="from" type="text" name="from">
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
    var CONTROLLER='/browser/Admin/Entertainment/';
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


        //更新操作数据选中和写入
        $('.uprec').each(function(){
            $(this).click(function(){
                $('#updatenav').show();

                var uid = $(this).attr('uid');
                var name= $(this).attr('name');
                var images= $(this).attr('images');
                var link= $(this).attr('link');
                var from= $(this).attr('from');
                var lang= $(this).attr('lang');
                var istop= $(this).attr('istop');


                $('#utname').val(name);
                $('#utname').attr('uid',uid);
                $('#uid').val(uid);
                $('#uttype option[value='+lang+']').prop("selected", 'selected');
                $('#utnav option[value='+istop+']').prop("selected", 'selected');
                var img='';
                for(i=0;i<images.split(',').length;i++){
                    img+="<img  style='margin-right:3px;margin-bottom:1px;' src='"+images.split(',')[i]+"' height='55'/>";
                }
                $('#images').html(img);
                $('#links').val(link);
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