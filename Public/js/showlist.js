layer.use('extend/layer.ext.js'); //载入layer拓展模块
$(document).ready(function() {

	$('.addList').on('click', function() {
		$(".search_comment").contents().remove();
		$(".show_page").contents().remove();
		$("input[name=sequence]").attr("value","");
		$("input[name=oldentityid]").attr("value","");
		$("input[name=recid]").attr("value","");
		$("#search_name").attr("value","");
		$("#action_class").removeClass("edit_btn").addClass("add_btn").html("添加记录");
		var pagei = $.layer({
			type : 1, //0-4的选择,
			title : listname,
			border : [ 1 ],
			closeBtn : [ 0 ],
			shift : "top",
			shadeClose : true,
			area : [ '800px', '500px' ],
			page : {
				dom : '.add_list'
			}
		});
	});
	
	
	$('.edit_item').on('click', function() {
		var id = this.id;
		var itemobj = $(this).parent().parent();
		var sequence = itemobj.find(".li_type").html();
		var name = itemobj.find(".li_name").html();
		var icon = itemobj.find(".li_icon").find(":first-child").attr("src");
		
		var eid = itemobj.find(".li_eid").html();
		$("#action_class").removeClass("add_btn").addClass("edit_btn").html("修改记录");
		
		var li_check = "<ul class=\"search_result_ul\"><li class=\"result_li_check\"><input class=\"check\" type=\"radio\" checked  name=\"checkid\" value=\"";
		var li_id =  "\" ></li><li class=\"result_li_id\">";
		var li_name = "</li><li class=\"result_li_name\">";
		var li_icon = "</li><li class=\"result_li_icon\"><img  class=\"img-rounded\" style=\"width:auto;height:25px\" src=\"";
		var li_end = "\"/></li></ul>";
		
		var ul_li_list = li_check + eid + li_id + eid + li_name + name + li_icon + icon +li_end;
		$(".search_comment").contents().remove();
		$(".show_page").contents().remove();
		$(".search_comment").html(ul_li_list);
		$("input[name=sequence]").attr("value",sequence);//编辑设置默认顺序值
		$("input[name=oldentityid]").attr("value",eid);//编辑设置默认实体ID
		$("input[name=recid]").attr("value",id);//编辑设置默认纪录ID
		$("#search_name").attr("value",name);
		
		var pagei = $.layer({
			type : 1, //0-4的选择,
			title : "修改主题精选记录",
			border : [ 1 ],
			closeBtn : [ 0 ],
			shift : "top",
			shadeClose : true,
			area : [ '800px', '500px' ],
			page : {
				dom : '.add_list'
			}
		});
	});
	
	$('.search_btn').on('click', function() {
		var type = $("#search_cid").val();
		var name = $("#search_name").val();
		var oldentityid = $("input[name=oldentityid]").val();
		if(name == ""){
			 layer.msg('请输入名字',2,{
				 type: 3, shade: false
			 });
			 return;
		}
		$.post(search_btn_searchNameByPost,
				{
					name : name,
					type : type
				},
				function(jsondata){
					var i = 0;
					var ul_li_list = "";
					
					$(".search_comment").contents().remove();
					
					var li_check = "<ul class=\"search_result_ul\"><li class=\"result_li_check\"><input class=\"check\" type=\"radio\" name=\"checkid\" value=\"";
					var li_check_true = "<ul class=\"search_result_ul\"><li class=\"result_li_check\"><input class=\"check\" type=\"radio\" checked name=\"checkid\" value=\"";
					var li_id =  "\" ></li><li class=\"result_li_id\">";
					var li_name = "</li><li class=\"result_li_name\">";
					var li_icon = "</li><li class=\"result_li_icon\"><img  class=\"img-rounded\" style=\"width:auto;height:25px\" src=\"";
					var li_end = "\"/></li></ul>";
					
					var page = jsondata.show;
				    var data = jsondata.data;
					
				    if(data != null){
				    	var datalength = data.length;
	  					if(datalength == 0){
	  						layer.msg("该搜索无纪录,请换关键词",2,{
								 type: 3, shade: false
							 });
							 return;
	  					}
						for(i = 0;i<datalength;i++){
							if(oldentityid != undefined && oldentityid == data[i].id ){
								ul_li_list += li_check_true + data[i].id + li_id + data[i].id + li_name + data[i].name + li_icon + data[i].icon +li_end;
							}else{
								ul_li_list += li_check + data[i].id + li_id + data[i].id + li_name + data[i].name + li_icon + data[i].icon +li_end;
							}
	  					 }
				    }else{
				    	layer.msg("该搜索无纪录,请换关键词",2,{
							 type: 3, shade: false
						 });
				    }
				    
				    
				    $(".search_comment").html(ul_li_list);
					$(".show_page").html(page);
					$(".show_page a").addClass("showpage");
		
			});
	});	
	
	$(".showpage").live("click",function(){
		var url = $(this).attr("href");
		var urlAux = url.split('/'); 
		
		var namepath = urlAux.indexOf("name");
		var pagepath = urlAux.indexOf("p");		
		var typepath = urlAux.indexOf("type");
		
		var name = urlAux[namepath+1];
		var type = urlAux[typepath+1];
		var pagesplit = urlAux[pagepath+1].split('.');
		var page = pagesplit[0];
		
		
		var oldentityid = $("input[name=oldentityid]").val();
		
		$.get(showpage_searchNameByGet,
				{
					p	 : page,
					name : name,
					type : type
				},
				function(jsondata){
					var i = 0;
					var ul_li_list = "";
					
					$(".search_comment").contents().remove();
					$(".show_page").contents().remove();
					
					var li_check = "<ul class=\"search_result_ul\"><li class=\"result_li_check\"><input class=\"check\" type=\"radio\" name=\"checkid\" value=\"";
					var li_check_true = "<ul class=\"search_result_ul\"><li class=\"result_li_check\"><input class=\"check\" type=\"radio\" checked name=\"checkid\" value=\"";
					var li_id =  "\" ></li><li class=\"result_li_id\">";
					var li_name = "</li><li class=\"result_li_name\">";
					var li_icon = "</li><li class=\"result_li_icon\"><img  class=\"img-rounded\" style=\"width:auto;height:25px\" src=\"";
					var li_end = "\"/></li></ul>";
					
					var page = jsondata.show;
				    var data = jsondata.data;
					
				    if(data != null){
				    	var datalength = data.length;
						for(i = 0;i<datalength;i++){
							if(oldentityid != undefined && oldentityid == data[i].id ){
								ul_li_list += li_check_true + data[i].id + li_id + data[i].id + li_name + data[i].name + li_icon + data[i].icon +li_end;
							}else{
								ul_li_list += li_check + data[i].id + li_id + data[i].id + li_name + data[i].name + li_icon + data[i].icon +li_end;
							}
						}
				    }
				    $(".search_comment").html(ul_li_list);
					$(".show_page").html(page);
					$(".show_page a").addClass("showpage");
		
			});
		return false;
	});
	
	$(".add_btn").live("click",function(){
		var entityid = $('input[name="checkid"]:checked').val();
		var sequence = $("input[name=sequence]").val();
		var typeid = $("input[name=listid]").val();
		if( entityid == undefined){
			layer.msg('请选择要添加的壁纸或主题',2,{
				 type: 3, shade: false
			 });
			 return;
		}
		$.post(add_btn_addListByPost,
				{
					eid : entityid,
					sequence : sequence,
					lnid   : typeid
				},
				function(jsondata){
					if(jsondata.status){
						layer.msg(jsondata.msg,2,{
							 type: 1, shade: false
						 });
						window.location.reload();
					}else{
						layer.msg(jsondata.msg,2,{
							 type: 3, shade: false
						 });
						 return;
					}
				}
		);
		
	});
	
	$(".edit_btn").live("click",function(){
		var entityid = $('input[name="checkid"]:checked').val();
		var sequence = $("input[name=sequence]").val();
		var typeid = $("input[name=listid]").val();
		var recid =  $("input[name=recid]").val();
		if( entityid == undefined){
			layer.msg('请选择要修改的壁纸或主题',2,{
				 type: 3, shade: false
			 });
			 return;
		}
		$.post(edit_btn_editListByPost,
				{
					eid : entityid,
					sequence : sequence,
					lnid   : typeid,
					id  : recid
				},
				function(jsondata){
					if(jsondata.status){
						layer.msg(jsondata.msg,2,{
							 type: 1, shade: false
						 });
//						window.location.reload();
					}else{
						layer.msg(jsondata.msg,2,{
							 type: 3, shade: false
						 });
						 return;
					}
				}
		);
		
	});
	
	$(".dele_item").live("click",function(){
		var id = $(this).attr("id");
		if( id == undefined){
			layer.msg('数据异常',2,{
				 type: 3, shade: false
			 });
			 return;
		}
		
		layer.confirm("确认删除次条记录?", function(){
			$.post(dele_item_deleListByPost,
					{
						id : id
					},
					function(jsondata){
						if(jsondata.status){
							layer.msg(jsondata.msg,2,{
								 type: 1, shade: false
							 });
							window.location.reload();
						}else{
							layer.msg(jsondata.msg,2,{
								 type: 3, shade: false
							 });
							 return;
						}
					}
			);
		}, function(){
			return;
		});
	});
		
	
	$("input[name=sequence]").keyup(function () {
        //如果输入非数字，则替换为''，如果输入数字，则在每4位之后添加一个空格分隔
		 this.value = this.value.replace(/[^\d]/g, '');//.replace(/(\d{4})(?=\d)/g, "$1 ");
    });
	
});