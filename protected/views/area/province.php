<?php foreach($rows as $code=>$name):?>
     <a class="col-md-6" href="javascript:load_areacode('level=2&p=<?=$code?>')"><?=$name?></a>
<?php endforeach;?>