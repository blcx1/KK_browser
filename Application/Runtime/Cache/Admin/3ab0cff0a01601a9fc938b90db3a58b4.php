<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo (L("adverlistadd")); ?></title>
	<link href="/browser/Public/lib/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/browser/Public/css/admin-all.css" />
	<link rel="stylesheet" type="text/css" href="/browser/Public/css/formui.css"/>
	<link href="/browser/Public/css/listtheme.css" rel="stylesheet">
	<script type="text/javascript" src="/browser/Public/lib/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/browser/Public/lib/My97DatePicker/WdatePicker.js"></script>
	<style type="text/css">
		body{
			min-width: 800px;
		}
		.form-inline .form-control {
		    display: inline-block;
		    width: auto;
		    vertical-align: middle;
		}
	</style>
	<script type="text/javascript">
        var getInfoPath='/browser/Admin/Webnav/getInfomation.html';
        $(function(){
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
            language();
            $("select[name='language']").change(function(){
                language();
            });
              $("input[type=file]").live("change",function(e){
			    	$(this).next().text(e.currentTarget.files[0].name)
			    })
            $.getScript('/browser/Public/js/img_select.js');
        });
	</script>
</head>
<body >
<div class="alert alert-info" style="padding: 2px;margin-bottom: 4px;">
	<?php echo (L("Position")); ?><b class="tip"></b>
	<?php echo (L("AdverManage")); ?><b class="tip"></b>
	<?php echo (L("adverlistadd")); ?>
</div>

<form action="/browser/Admin/Webnav/csvfile.html" method="post" enctype="multipart/form-data" style="width: 50%;margin:0px auto; padding-top: 10px; min-width: 675px;">
	<input id="csvimp" type="button" style="float:left; margin:0 0 10px 0;" value="CSV格式批量导入" class="btn4 btn-info btn" />
	<div id="csvfile" style="float:right; display:none; margin:0;position: relative;">
		<input type="submit" value="确定" class="btn4 btn-info btn" style="float: right;margin: 0 35px 0 20px;" />
		<input type="file" name="csvfile"id="more" class="form-control radius" style="float: right; "  />
		        <div class="shade" style="width: 210px;left: 50%;">未上传任何文件</div>               
		<input type="button" class="btn4 btn" value="模板文件下载" style="float: left;" onclick="location.href='/browser/Admin/Webnav/doloadfile.html?name=addadver.csv'" />
	</div>
</form>
<div class="img-loading">
	<img class="loading" src="/browser/Public/images/timg.gif"  /><input class="btn" type="button" value="取消">
</div>
<!--通过url检测到的img-->
<div class="imgselect" style="display:none; position:absolute; background:#CCC; width:100%;min-height: 100%;z-index: 2;">
	<div style="text-align:center;">
		<span class="imgurl"></span>
		<div class="btn-last">
			<input class="img-comit" type="button" value="确定">&nbsp;&nbsp;<input class="img-recet" type="button" value="取消">
		</div>

	</div>
</div>

<div style="width: 50%;margin:0px auto; padding-top: 10px; min-width: 675px;"  style="text-align:center;">
	<form method="post" action="/browser/Admin/Webnav/addAdver" enctype="multipart/form-data" >
		<table class="table  table-striped table-bordered table-condensed "style="position: relative;" ><tbody>
				 <tr>
                        <td>链接地址(url)：<span style="color:red;">&nbsp;&nbsp;*</span></td>
                        <td class="link_address"><input class="form-control" type="text" name="link_address" style="width:80%;display: inline-block;"><input id="btn" type="button" value="获取信息"></td>
                    </tr>
			<!--</tr>-->
			<tr>
				<td>位置：</td>
				<td>
					<select class="form-control" name="language"style="display: inline-block;
    width: auto;" >
						<?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
					<select class="form-control" name="nid" style="display: inline-block;
    width: auto;">
						<?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nvo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($nvo["id"]); ?>"><?php echo ($nvo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</td>
			</tr>

			<tr>
				<td>名称：</td>
				<td><input type="text" class="form-control" name="tit_name"></td>
			</tr>

		 <tr>
                        <td>展示图片：</td>
                        <td>
                            <span id="image0"><input class="form-control radius" type="file" name="image[]" style="width: auto;"><div class="shade" >未上传任何文件</div></span><span style="color:red;">*</span><br/>
                            <span id="image1"><input class="form-control radius" type="file" name="image[]" style="width: auto;"><div class="shade" >未上传任何文件</div></span><br/>
                            <span id="image2"><input class="form-control radius" type="file" name="image[]" style="width: auto;"><div class="shade" >未上传任何文件</div></span>
                        </td>
                    </tr>
			<tr>
				<td colspan="2" style="text-align: center;">
					<input type="submit" value="<?php echo (L("Add")); ?>" class="btn4 btn-info btn" />
					<input type="reset" value="<?php echo (L("Reset")); ?>" class="btn4 btn-warning btn" />
				</td>
			</tr>
        </tbody>
		</table>
	</form>
</div>


</body>
</html>