<?php
echo "表索引";
$i=0;
$t = new SimpleGrid($i++);
$t->url = '';
$t->updateDom = 'datagrid';
$t->set_header('表名', '30', '');
$t->set_header('表引擎', '60', '','');
$t->set_header('注释', '140', '');
$t->echo_grid_header();
$sql="show table status";
$rows=Yii::app()->db->createCommand($sql)->queryAll();
if (is_array($rows))
{
    foreach($rows as $row){
        $t->echo_td($row['Name']);
        $t->echo_td($row['Engine']);
        $t->echo_td($row['Comment']);
        $t->end_row();
    }
}
$t->echo_grid_floor();

echo "表明细";
foreach($rows as $row){

    $sql="show full fields from ".$row['Name'];
    $rows_detail=Yii::app()->db->createCommand($sql)->queryAll();
    echo "<br>";
    echo "<br>";
    echo "表".$row['Name'];
    $t = new SimpleGrid($i++);
    $t->url = '';
    $t->updateDom = 'datagrid';
    $t->set_header('字段名', '80', '');
    $t->set_header('类型', '80', '','');
    $t->set_header('主键', '60', '');
    $t->set_header('默认值', '80', '');
    $t->set_header('备注', '120', '');
    $t->echo_grid_header();
    if (is_array($rows_detail))
    {
        foreach($rows_detail as $row){
            $t->echo_td($row['Field']);
            $t->echo_td($row['Type']);
            $t->echo_td($row['Key']=='PRI'?'是':'否');
            $t->echo_td($row['Default']);
            $t->echo_td($row['Comment']);
            $t->end_row();
        }
    }
    $t->echo_grid_floor();

}
