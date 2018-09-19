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
<body>
<div class="alert alert-info" style="padding: 2px;margin-bottom: 4px;">
	<?php echo (L("Position")); ?><b class="tip"></b>
	<?php echo (L("Funny")); ?><b class="tip"></b>
	<?php if(strtolower(ACTION_NAME)== 'impotaddlist'): ?>当前已导入列表<?php elseif(ACTION_NAME== 'off_put_list'): echo (L("FunnyList(Unpublished)")); else: echo (L("FunnyList")); endif; ?>
</div>
<table  style="width:90%;text-align: center; margin:0 auto;" class="table  table-striped table-bordered table-condensed " >
	<form method="post" action="/browser/Admin/Funny/delFunny_list?act=<?php echo (ACTION_NAME); ?>" id="from_delete">
			<tr>
				<td colspan="10" >
					<span  class="btn btn-default select_all"  style='margin: 0 5px;'><?php echo (L("AllChoose")); ?></span>
					<span  class="btn btn-default re_select_all" style='margin: 0 5px;'><?php echo (L("ContraryChoose")); ?></span>
					<a href="/browser/Admin/Funny/addFunny_list" class="btn btn-default"><?php echo (L("Add")); ?></a>
					<input type="submit"  style='margin: 0 5px;' class="btn btn-default" onclick="return confirm('<?php echo (L("DelOK")); ?>？')" value="<?php echo (L("Delete")); ?>"/>
                                        <?php if((ACTION_NAME== 'ImpotAddList') OR (ACTION_NAME== 'impotaddlist') OR (ACTION_NAME== 'off_put_list')): ?><input type="submit" id="put" style="margin: 0 5px;" class="btn btn-default" onclick="return confirm('确认对选中的项提交发布？')" value="发布" /><?php endif; ?>
                                        <?php if(ACTION_NAME== 'funnylist'): ?><input type="submit" id="removed" style="margin: 0 5px;" class="btn btn-default" onclick="return confirm('确认对选中的项下架处理？')" value="下架" /><?php endif; ?>
				</td>
			</tr>
			<tr class="tab_list">
				<td width="5%"><?php echo (L("Check")); ?></td>
				<td width="5%"><?php echo (L("AdminId")); ?></td>
				<td width="10%"><?php echo (L("TypeName")); ?></td>
				<td width="25%"><?php echo (L("Name")); ?></td>
				<td width="20%"><?php echo (L("Icon")); ?></td>
				<td width="5%"><?php echo (L("Type")); ?></td>
				<td width="5%"><?php echo (L("language")); ?></td>
				<td width="15%"><?php echo (L("Operate")); ?></td>
			</tr>
			<?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td><input class="check" type="checkbox" name="check[]" value="<?php echo ($vo["id"]); ?>"></td>
				<td><?php echo ($vo["id"]); ?></td>
				<td><?php echo ($vo["type_name"]); ?></td>
				<td><div class="tite-out"><a href="<?php echo ($vo["link_address"]); ?>" title="<?php echo ($vo["tit_name"]); ?>" target="_blank"><?php echo ($vo["tit_name"]); ?></a></div></td>
				<td >
                                    <?php if(is_array($vo["icon_image"])): $i = 0; $__LIST__ = $vo["icon_image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?><a href="<?php echo ($img); ?>" traget="_blank"><img class="imgh" src="<?php echo ($img); ?>" height='30'/></a><?php endforeach; endif; else: echo "" ;endif; ?>
				</td>
				<td><?php echo ($vo["mimtype"]); ?></td>
				<td><?php echo ($vo["languages"]); ?></td>
				<td>
					<button type="button" uid="<?php echo ($vo["id"]); ?>" tid="<?php echo ($vo["tid"]); ?>" name="<?php echo ($vo["tit_name"]); ?>" types="<?php echo ($vo["mimtype"]); ?>" images="<?php echo (implode($vo["icon_image"],',')); ?>" link="<?php echo ($vo["link_address"]); ?>" desc="<?php echo ($vo["txt_content"]); ?>" lang="<?php echo ($vo["language"]); ?>" class="btn btn-primary btn-sm uprec">修改</button>
					<a href="/browser/Admin/Funny/delFunny_list?check=<?php echo ($vo["id"]); ?>&act=<?php echo (ACTION_NAME); ?>" class="btn btn-warning btn-sm">删除</a>
                                        <?php if(ACTION_NAME== 'funnylist'): ?><a href="/browser/Admin/Funny/removed.html?check=<?php echo ($vo["id"]); ?>" class="btn btn-warning btn-sm">下架</a><?php endif; ?>
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

<!--更新窗口 start-->
<div id="updatenav" style="display:none; width:500px; min-height:450px; padding-bottom:20px; border:1px solid #eee; position:fixed;z-index:999;top:10px;left:50%; margin-left:-250px; background:white; box-shadow: 5px 15px 10px #ccc;">
	<form action="/browser/Admin/Funny/updateFunny.html" method="post" enctype="multipart/form-data">
		<input type="hidden" value="" name="id" id="uid"/>
		<div style="width: 100%;height: 35px;line-height: 35px;background:#eee;text-align:center;border-bottom: 1px solid #D5D5D5;font-size: 14px;color: #333;">修改搞笑内容</div>
		<div style="padding:30px;">
			<div class="input-group">
				<div class="input-group-addon">名称：</div>
				<input type="text" style="width:300px" uid="" id="utname" class="form-control" name="name">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">描述：</div>
				<input style="width:300px" class="form-control" uid="" id="desc" type="text" name="desc">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">语言：</div>
				<select class="form-control" name="language" >
					<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">分类：</div>
				<select id="utnav" class="form-control" name="cate">
					<?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nvo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($nvo["id"]); ?>"><?php echo ($nvo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">类型：</div>
				<select id="uttype" name="mimtype" class="form-control">
					<option value="jpg">图片</option>
					<option value="txt">短文</option>
					<option value="mp4">视频</option>
				</select>
			</div>

			<div id="wenjian1">

			</div>

			<div class="input-group" style="margin-top:10px;">
				<lable id="uwenjian" class="filel" style="display: inline-block;">
					<p id="images" style="width:350px;"></p>
					<div class="input-group t1" style="margin:10px 0 0 0;">
						<div class="inp-up">搞笑首图：</div>
						<input class="form-control radius t-radius"  type="file" name="pic[]" />
					    <div class="shade" style="left: 40%;">未上传文件</div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
				    </div>
				    <div class="input-group t2" style="margin:10px 0 0 0;">
						<div class="inp-up">搞笑次图：</div>
						<input class="form-control radius t-radius"  type="file" name="pic[]" />
					    <div class="shade" style="left: 40%;">未上传文件</div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
				    </div>
				    <div class="input-group t2" style="margin:10px 0 0 0;">
						<div class="inp-up">搞笑次图：</div>
						<input class="form-control radius t-radius"  type="file" name="pic[]" />
					    <div class="shade" style="left: 40%;">未上传文件</div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
				    </div>
					<div class="input-group t2" style="margin:10px 0 0 0;">
						<div class="inp-up">搞笑次图：</div>
						<input class="form-control radius t-radius"  type="file" name="pic[]" />
						<div class="shade" style="left: 40%;">未上传文件</div>
					</div>
				</lable>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">跳转链接：</div>
				<input style="width:242px" class="form-control" id="links" type="text" name="link">
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
        var CONTROLLER='/browser/Admin/Funny/';
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

        //更新选择短文时隐藏文件上传按钮
        $("#uttype").change(function(){
            if($(this).val()=='txt'){
                $('.t1,.t2').hide();
            }else if($(this).val()=='mp4'){
                $('.t1').show();
                $('.t2').hide();
            }else{
                $('.t1').show();
                $('.t2').show();
            }
        });

        //更新操作数据选中
        $('.uprec').each(function(i){
            $(this).click(function(){
                $('#utname').val('');
                $('#uttype option').eq(0).prop("selected", 'selected');
                $('#updatenav').show();

                var uid = $(this).attr('uid');
                var tid = $(this).attr('tid');
                var name = $(this).attr('name');
                var desc = $(this).attr('desc');
                var images = $(this).attr('images');
                var link = $(this).attr('link');
                var from= $(this).attr('from');
                var type= $(this).attr('types');
                var lang = $(this).attr('lang');
                
                $('#utname').val(name);
                $('#utname').attr('uid',uid);
                $('#uid').val(uid);
                $('#desc').val(desc);
                $("select[name=language] option[value="+lang+"]").prop("selected", 'selected');
                language();
                setTimeout(function(){
                    //等待language()请求完，再加载这个选择
                    $('#utnav option[value='+tid+']').prop("selected", 'selected');
                },1000);

                $('#uttype option[value='+type+']').prop("selected", 'selected');
                $('#links').val(link);
                $('#from').val(from);

                //插入图片显示
                var img='';
                for(i=0;i<images.split(',').length;i++){
                    img+="<img style='margin-right:3px;margin-bottom:1px;' src=\""+images.split(',')[i]+"\" height='65'/>";
                }

                if(images.split(',').length ==1){
                    //一张图的时候插入一个input
                    $('#wenjian1').html('');
                    if(images!=''){
                        $('#wenjian1').append("<input type='hidden' value=\""+images+"\" name=\"opic[]\" />");
                        $('#wenjian1').append("<input type='hidden' value=\""+images+"\" name=\"opic[]\" />");
					}
				}else{
                    //多张图的时候插入多个input
                    $('#wenjian1').html('');
                    for(i=0;i<images.split(',').length;i++){
                        $('#wenjian1').append("<input value=\""+images.split(',')[i]+"\" type='hidden' name=\"opic[]\" />");
                    }
				}
				//没有图片的时候提示
                if(images==''){
                    $('#images').html('无图片');
                    //隐藏更多按钮
                    $('#upmore1').hide();
				}else{
                    $('#upmore1').show();
                    $('#images').html(img);
				}
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
                url:'/browser/Admin/Funny/langType.html',
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