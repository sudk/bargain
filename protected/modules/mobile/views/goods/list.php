<div style="position:fixed; z-index:-2;display:block;height:100%;width:100%;">
    <img src="img/bg.jpg" style="height:100%;width:100%;">
</div>
<?php if(count($rows)):?>
    <?php foreach($rows as $row):?>
    <div class="panel panel-default" style="margin-top:5%;">
        <div class="panel-body">
            <a href="./?r=mobile/goods/index&id=<?=$row['id']?>"><img width="100%" style="max-height:200px;" src="<?= Yii::app()->params['upload_file_path'] . "/" . $row['img_url'] ?>" ></a>
        </div>
        <div class="panel-footer"><?=mb_substr($row['name'],0,8)?><span id="price_now" class="text-warning pull-right" style="font-size:20px;font-weight:bolder;vertical-align:middle;">
                        ￥<?= number_format($row['price'] / 100, 2) ?>
                    </span></div>
    </div>
    <?php endforeach;?>
<?php endif;?>
<script>
    $("body").css("background","none");
</script>