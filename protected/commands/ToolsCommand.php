<?php

/**
 * Description of Statsystem
 *
 * @author sudk
 */
class ToolsCommand extends CConsoleCommand
{
    /*获得刷卡记录

    */
    public function actionInitstaff()
    {
        Staff::InitStaff();
    }
    /**
     * 生成回访计划
     * @param null $vdate
     */
    public function actionRemindvisit($vdate=null){
        echo MchtVdetail::createDetail($vdate);
    }

    public function actionMchtVisitC(){
        echo "begin compute...";
        MchtVisit::VisitCompute();
        echo "end compute";
    }

    
}
