<div class="row">
    <a  href="javascript:load_areacode('level=2&p=<?=$_GET['p']?>')" class="col-lg-11"><i class="fa fa-fw fa-reply"></i>返回上一级</a>
</div>
<?php foreach($rows as $row):?>
     <a class="col-md-6" href="javascript:set_areacode(<?=$row['a_id']?>)"><?=$row['a_name']?></a>
<?php endforeach;?>