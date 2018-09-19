//点击发布按扭
if($('#put').length>0){
    $('#put').click(function(){
        $('#from_delete').attr('action',CONTROLLER+'put.html');
    });
}

//点击下架按扭
if($('#removed').length>0){
    $('#removed').click(function(){
        $('#from_delete').attr('action',CONTROLLER+'removed.html');
    });
}



