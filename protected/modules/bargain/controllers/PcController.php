<?php

/**
 * 操作员管理
 *
 * @author sudk
 */
class PcController extends MobileBaseController
{
    public function actionIndex(){
        $this->layout="//layouts/mobile";
        $rows=Goods::GetActive();
        $this->render("index",array('rows'=>$rows));
    }

}