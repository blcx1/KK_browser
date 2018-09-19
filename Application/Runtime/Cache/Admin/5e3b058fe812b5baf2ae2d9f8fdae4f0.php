<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html >
<html>
<head>
<title><?php echo (L("AddTheme")); ?></title>
<meta charset="UTF-8">
<link href="/browser/Public/lib/bootstrap/css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/browser/Public/css/admin-all.css" />
<link rel="stylesheet" type="text/css" href="/browser/Public/css/formui.css"/>
<script src="/browser/Public/lib/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/browser/Public/lib/My97DatePicker/WdatePicker.js" type='text/javascript'></script>
<script src="/browser/Public/lib/layer/layer.min.js" type='text/javascript'></script>
<style type="text/css">
	body{
		min-width: 800px;
	}

</style>
<script type='text/javascript'>
var getInfoPath='/browser/Admin/Car/getInfomation.html';
$(document).ready(function(){
    
    $.getScript('/browser/Public/js/img_select.js');
  
})
</script>
	
</head>
<body>
<div class="alert alert-info" style="padding: 2px;margin-bottom: 4px;">
	<?php echo (L("Position")); ?><b class="tip"></b>
	<?php echo (L("CarManage")); ?><b class="tip"></b>
	<?php echo (L("carlistadd")); ?>
</div>
       
        <form action="/browser/Admin/Car/csvfile.html" method="post" enctype="multipart/form-data" style="width: 50%;margin:0px auto; padding-top: 10px; min-width: 675px;">
        	<div  style="width: 744px; margin: auto;">
        	 <input id="csvimp" type="button" style="float:left; margin:0 0 10px 0;" value="CSV格式批量导入" class="btn4 btn-info btn" />
            <div id="csvfile" style="float:right; display:none; margin:0;position: relative;">
            	 <input type="submit" value="确定" class="btn4 btn-info btn" style="float: right;margin: 0 35px 0 20px;" />
                <input type="file" name="csvfile"id="more" class="form-control radius" style="float: right; "  />
		        <div class="shade" style="width: 210px;left: 50%;">未上传任何文件</div>               
               <input type="button" class="btn4 btn" value="模板文件下载" style="float: left;" onclick="location.href='/browser/Admin/Car/doloadfile.html?name=addcar.csv'" />
            </div>
           </div>
        </form>
        <div class="img-loading">
        	<img class="loading" src="/browser/Public/images/timg.gif"  /><input class="btn-warning btn" type="button" value="取消">     	
        </div>
        <!--通过url检测到的img-->
        <div class="imgselect" style="display:none; position:absolute; background:#CCC; width:100%;min-height: 100%;z-index: 2;">
            <div style="text-align:center;">
             <div id="up"></div>
             <div id="lo"></div>
            <span class="imgurl"></span>
            <div class="btn-last">
            	 <input class="img-comit" type="button" value="确定">&nbsp;&nbsp;<input class="img-recet" type="button" value="取消">
            </div>
           
            </div>
        </div>
	<div style="width: 50%;margin:0px auto; padding-top: 10px; min-width: 675px;"  style="text-align:center;">
		<form  id="addForm" action="/browser/Admin/Car/caradd" method="post" enctype="multipart/form-data" class="form-inline" >

			<table  class="table table-bordered table-striped  table-condensed "style="position: relative;">
				<tbody>
                    <tr>
                        <td>链接地址(url)：<span style="color:red;">&nbsp;&nbsp;*</span></td>
                        <td class="link_address"><input class="form-control" type="text" name="link_address" style="width:80%;"><input id="btn" type="button" value="获取信息"></td>
                    </tr>
                    <tr>
                        <td>语言：</td>
                        <td>
                            <select name="language" class="form-control">
                                    <?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["iso_code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>标题：<span style="color:red;">&nbsp;&nbsp;*</span></td>
                        <td><input style="width:100%; font-weight:bold;" class="form-control" type="text" name="tit_name"></td>
                    </tr>
                    <tr id="imgSelect" style="display:none;">
                        <td colspan="2"><input class="btn btn-reset" type="button" value="重新选择图片"></td>
                    </tr>
                    <tr>
                        <td>展示图片：</td>
                        <td>
                            <span id="image0"><input class="form-control radius" type="file" name="image[]"><div class="shade" style="left: 53%;">未上传任何文件</div></span><span style="color:red;">*</span><br/>
                            <span id="image1"><input class="form-control radius" type="file" name="image[]"><div class="shade" style="left: 53%;">未上传任何文件</div></span><br/>
                            <span id="image2"><input class="form-control radius" type="file" name="image[]"><div class="shade" style="left: 53%;">未上传任何文件</div></span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo (L("IsTop")); ?>:</td>
                        <td>
                                <select class="form-control" name="istop">
                                        <option value="0">no</option>
                                        <option value="1">yes</option>
                                </select>
                        </td>
		    </tr>
                    <tr>
                        <td>来自：</td>
                        <td><input class="form-control" type="text" name="come_from"style="text-align: center;"><input id="comeSelect" class="btn" type="button" value="︿"><span></span></td>
                    </tr>
                    <tr id="comeFrom" >
                        <td colspan="2" style="text-align: center;">
                           
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <select class="form-control" name="is_put"><option value="1">发布</option><option value="0">不发布</option></select>
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