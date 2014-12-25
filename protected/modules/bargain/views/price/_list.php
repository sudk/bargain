<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;
    foreach ($rows as $i => $row) {
        $opt="<a href='javascript:void(0)' onclick='detail(\"{$row['uid']}\",\"{$row['goods_id']}\")'><span class=\"fa fa-fw fa-edit\"></span>详情</a>";
        $t->echo_td($row['uid']);
        $t->echo_td($row['name']);
        $t->echo_td(number_format($row['p_price']/100,2));
        $t->echo_td(number_format($row['price']/100,2));
        $t->echo_td($row['record_time']);
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
    var detail=function(uid,goods_id){
        var modal=new TBModal();
        modal.title="杀价详情";
        modal.url="./index.php?r=bargain/log/list&q[uid]="+uid+"&q[goods_id]="+goods_id;
        modal.modal();
    }
</script>
