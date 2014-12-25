<div class="list-group" style="margin-bottom:5px;">
    <?php if($rs['total_num']>0):?>
        <?php foreach($rs['rows'] as $row):?>
            <a class="list-group-item list-group-item-sm row">
                <div class="col-xs-12">
                    <h5><?=$row['bargain_id']?> ￥-<?=number_format($row['reduce_price']/100,2)?> <?=$row['record_time']?></h5>
                </div>
            </a>
        <?php endforeach;?>
        <?php $this->widget('MobilePager', array('id' => 'list','page_num' =>$rs['page_num'],'total_num' =>$rs['total_num'],'num_of_page'=>$rs['num_of_page'],'condition'=>$rs['condition'],'order'=>$rs['order'],'url'=>$rs['url'])); ?>
    <?php else:?>
        <a class="list-group-item list-group-item-sm row">
            <h3 class="text-center">矮柚，啥都木有！</h3>
        </a>
    <?php endif;?>
</div>