<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;
    foreach ($rows as $i => $row) {
        $opt="<a href='javascript:void(0)' onclick='edit(\"{$row['id']}\")'><span class=\"fa fa-fw fa-edit\"></span>编辑</a>";
        $opt.="&nbsp;&nbsp;<a href='javascript:void(0)' onclick='del(\"{$row['id']}\",\"{$row['name']}\")'><span class=\"fa fa-fw fa-user\"></span>删除</a>";
        $t->echo_td($row['id']);
        $t->echo_td($row['name']);
        $t->echo_td($row['phone']);
        $t->echo_td(Operator::getStatusTitle($row['status']));
        $t->echo_td($opt);
        $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this -> pageSize;
$pager->itemCount = $cnt;
?>
<div class="row">
	<div class="col-xs-3">
		<div class="dataTables_info" id="example2_info">
    			共<?php echo $cnt;?> 条
    	</div>
	</div>
	<div class="col-xs-9">
		<div class="dataTables_paginate paging_bootstrap">
			<?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
    var edit=function(id){
        var modal=new TBModal();
        modal.title="操作员编辑";
        modal.url="./index.php?r=sys/operator/edit&id="+id;
        modal.modal();
    }
    var del=function(id,name){
        if(!confirm("确定要删除"+name)){
            return;
        }
        $.ajax({
            type: "post",
            data:{id:id},
            dataType:"json",
            url: "./?r=sys/operator/del",
            beforeSend: function(XMLHttpRequest){
                displayLoadingLayer();
            },
            success: function(data, textStatus){
                alert(data.msg);
                if(data.status==0){
                    itemQuery();
                }
            },
            complete: function(XMLHttpRequest, textStatus){
                hideLoadingLayer();
            },
            error: function(){
                alert("请求失败");
            }
        });
    }
    var role=function(id){
        var modal=new TBModal();
        modal.title="操作员权限";
        modal.url="./index.php?r=auth/list&operator_id="+id;
        modal.modal();
    }
</script>
