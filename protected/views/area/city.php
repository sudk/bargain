<div class="row">
    <a  href="javascript:load_areacode('level=1')" class="col-lg-11"><i class="fa fa-fw fa-reply"></i>返回上一级</a>
</div>
<?php foreach($rows as $code=>$name):?>
    <a class="col-md-6" href="javascript:load_areacode('level=3&c=<?=$code?>&p=<?=$_GET['p']?>')"><?=$name?></a>
<?php endforeach;?>