<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;
    foreach ($rows as $i => $row) {
        $opt="<a href='javascript:void(0)' onclick='edit(\"{$row['code']}\")'><i class=\"fa fa-fw fa-edit\"></i>编辑</a>";

        $t->echo_td($row['code']);
        $t->echo_td($row['name']);
        $t->echo_td($row['contact']);
        $t->echo_td($row['phone']);
        $t->echo_td($row['valid_time']);
        $t->echo_td($row['operator']);
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
    			共 <?php echo $cnt;?> 条
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
        modal.title="机构编辑";
        modal.url="./index.php?r=sys/institute/edit&id="+id;
        modal.modal();
    }
</script>
