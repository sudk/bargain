<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-body table-responsive">
				<div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId;?>_wrapper">
					<?php $this->renderPartial('_toolBox'); ?>
					<div id="datagrid"><?php $this->actionGrid(); ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
