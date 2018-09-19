<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo (L("ShopList")); ?></title>
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
	<?php if((ACTION_NAME== 'ImpotAddList') OR (ACTION_NAME== 'impotaddlist')): ?>当前已导入列表<?php elseif(ACTION_NAME== 'off_put_list'): echo (L("ShopList(Unpublished)")); else: echo (L("ShopList")); endif; ?>
	</div>
<div>
	<table  style="width:90%;text-align: center; margin:0 auto;" class="table  table-striped table-bordered table-condensed " >
	<form method="post" action="/browser/Admin/Shop/delShop_list?act=<?php echo (ACTION_NAME); ?>" id="from_delete">
			<tr>
				<td colspan="10" >
					<span  class="btn btn-default select_all"  style='margin: 0 5px;'><?php echo (L("AllChoose")); ?></span>
					<span  class="btn btn-default re_select_all" style='margin: 0 5px;'><?php echo (L("ContraryChoose")); ?></span>
					<a href="/browser/Admin/Shop/addShop_list" class="btn btn-default"><?php echo (L("Add")); ?></a>
					<input type="submit"  style='margin: 0 5px;' class="btn btn-default" onclick="return confirm('<?php echo (L("DelOK")); ?>？')" value="<?php echo (L("Delete")); ?>"/>
                                        <?php if((strtolower(ACTION_NAME)== 'impotaddlist') OR (ACTION_NAME== 'off_put_list')): ?><input type="submit" id="put" style="margin: 0 5px;" class="btn btn-default" onclick="return confirm('确认对选中的项提交发布？')" value="发布" /><?php endif; ?>
                                        <?php if(strtolower(ACTION_NAME)== 'shoplist'): ?><input type="submit" id="removed" style="margin: 0 5px;" class="btn btn-default" onclick="return confirm('确认对选中的项下架处理？')" value="下架" /><?php endif; ?>
                                </td>
			</tr>
			<tr class="tab_list">
				<td style="width: 5%;"><?php echo (L("Check")); ?></td>
				<td style="width: 5%;"><?php echo (L("AdminId")); ?></td>
				<td style="width: 10%;"><?php echo (L("TypeName")); ?></td>
				<td style="width: 30%;"><?php echo (L("Name")); ?></td>
				<td style="width: 10%;"><?php echo (L("Icon")); ?></td>
				<td style="width: 10%;">单价</td>
				<td style="width: 10%;">已购买数量</td>
				<td style="width: 10%;"><?php echo (L("language")); ?></td>
				<td style="width: 10%;"><?php echo (L("Operate")); ?></td>
			</tr>
			<?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
				<td><input class="check" type="checkbox" name="check[]" value="<?php echo ($vo["id"]); ?>"></td>
				<td><?php echo ($vo["id"]); ?></td>
				<td><?php echo ($vo["type_name"]); ?></td>
				<td><div class="tite-out"><a href="<?php echo ($vo["link_address"]); ?>" title="<?php echo ($vo["shop_name"]); ?>" target="_blank"><?php echo ($vo["shop_name"]); ?></a></div></td>
				<td >
                                    <a href="<?php echo ($vo["shop_image"]); ?>" traget="_blank"><img class="imgh" src="<?php echo ($vo["shop_image"]); ?>" height="30" style="margin-right:6px;" /></a>
				</td>
				<td><?php echo (number_format($vo["shop_price"],2)); ?></td>
				<td><?php echo ($vo["out_count"]); ?></td>
				<td><?php echo ($vo["languages"]); ?></td>
				<td>
					<button type="button" lang="<?php echo ($vo["language"]); ?>" uid="<?php echo ($vo["id"]); ?>" tid="<?php echo ($vo["type_id"]); ?>" recom="<?php echo ($vo["recom"]); ?>" name="<?php echo ($vo["shop_name"]); ?>" images="<?php echo ($vo["shop_image"]); ?>" count="<?php echo ($vo["out_count"]); ?>" price="<?php echo ($vo["shop_price"]); ?>" link="<?php echo ($vo["link_address"]); ?>" class="btn btn-primary btn-sm uprec">修改</button>
					<a href="/browser/Admin/Shop/delShop_list.html?check=<?php echo ($vo["id"]); ?>&act=<?php echo (ACTION_NAME); ?>" class="btn btn-warning btn-sm">删除</a>
                                        <?php if(strtolower(ACTION_NAME)== 'shoplist'): ?><a href="/browser/Admin/Shop/removed.html?check=<?php echo ($vo["id"]); ?>" class="btn btn-warning btn-sm">下架</a><?php endif; ?>
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
<div id="updatenav" style="display:none; width:500px; min-height:460px; padding-bottom:20px;border:1px solid #eee; position:fixed;z-index:999;top:100px;left:50%; margin-left:-250px; background:white; box-shadow: 5px 15px 10px #ccc;">
	<form action="/browser/Admin/Shop/updateShop.html" method="post" enctype="multipart/form-data">
		<input type="hidden" value="" name="id" id="uid"/>
		<div style="width: 100%;height: 35px;line-height: 35px;background:#eee;text-align:center;border-bottom: 1px solid #D5D5D5;font-size: 14px;color: #333;">修改好物内容</div>
		<div style="padding:20px;">
			<div class="input-group">
				<div class="input-group-addon">名称：</div>
				<input style="width: 327px;" uid="" id="utname" class="form-control" type="text" name="name">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">语言：</div>
				<select class="form-control" name="language" >
					<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
			
			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">跳转链接：</div>
				<input style="width: 300px;" id="links" class="form-control" type="text" name="link">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">分类：</div>
				<select id="utnav" class="form-control" name="cate">
					<?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nvo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($nvo["id"]); ?>"><?php echo ($nvo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>

			<div class="input-group" style="margin-top:10px;">
				<p id="images"></p>
			</div>
			<div class="input-group" style="margin-top:10px;">
				<div class="inp-up">上传图片：</div>
				<input style="width: 280px;" type="file" name="pic" class="form-control radius"/>
				<div class="shade" style="width: 144px;">未上传文件</div>     
			</div>

			

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">价格：</div>
				<input type="number" step="0.01" id="pric" class="form-control" type="text" name="pric">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">已购买数量：</div>
				<input type="number" id="num" class="form-control" type="text" name="num">
			</div>

			<div class="input-group" style="margin-top:10px;">
				<div class="input-group-addon">推荐：</div>
				<select class="form-control" id="utrecom" name="recom">
					<option value="1">是</option>
					<option value="0">否</option>
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
    var CONTROLLER='/browser/Admin/Shop/';
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
                var tid = $(this).attr('tid');
                var name = $(this).attr('name');
                var images = $(this).attr('images');
                var link = $(this).attr('link');
                var recom = $(this).attr('recom');
                var pric = $(this).attr('price');
                var count = $(this).attr('count');
                var lang = $(this).attr('lang');

                $('#utname').val(name);
                $('#utname').attr('uid',uid);
                $('#uid').val(uid);
                $('#num').val(count);
                $('select[name=language] option[value='+lang+']').prop("selected", 'selected');
                language();
                setTimeout(function(){
                    $('#utnav option[value='+tid+']').prop("selected", 'selected');
				},1000);
                $('#links').val(link);
                $('#pric').val(pric);
                $('#images').html('<img src="'+images+'" height="50" />');
                $('#utrecom option[value='+recom+']').prop("selected", 'selected');
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
                url:'/browser/Admin/Shop/langType.html',
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