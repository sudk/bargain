<?php

/**
 * 商品杀价
 *
 * @author sudk
 */
class LogController extends MobileBaseController
{

    /**
     * 查询
     */
    public function actionGrid()
    {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page']=$_GET['page']+1;
        $args = $_GET['q']; //查询条件
        if ($_REQUEST['q_value'])
        {
            $args[$_REQUEST['q_by']] = $_REQUEST['q_value'];
        }
        $rs = BargainLog::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('rs' => $rs));
    }

    /**
     * 列表
     */
    public function actionList()
    {
        $this->render('list');
    }

}