<?php if(count($rows)):?>
    <?php foreach($rows as $row):?>
        <p class="text-left" onclick="set_code('<?=$row['code']?>')"><?=$row['name']."(".$row['code'].")"?></p>
    <?php endforeach;?>
<?php else:?>
    <p class="text-left">无结果</p>
<?php endif;?>