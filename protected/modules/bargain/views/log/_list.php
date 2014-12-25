<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;
    foreach ($rows as $i => $row) {
        $t->echo_td($row['bargain_id']);
        $t->echo_td($row['record_time']);
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
