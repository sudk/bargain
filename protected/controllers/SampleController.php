<?php
/*
 * 模块编号: M1001
 */
class SampleController extends BaseController
{

    /**
     * 查询
     */
    public function actionIndex()
    {

        $command=Yii::app()->db->createCommand("call create_month_report('201402')");
        $rowCount=$command->execute();
        print_r($rowCount);
    }

}