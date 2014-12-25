<?php

/**
 * 操作员管理
 *
 * @author sudk
 */
class LogController extends AuthBaseController
{
    public $gridId = 'log_list';

    public $defaultAction = 'list';
    public $contentHeader = "砍价统计";
    public $bigMenu = "砍价管理";

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid()
    {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=bargain/log/grid';
        $t->updateDom = 'log_datagrid';
        $t->set_header('手机号', '', '', 'bargain_id');
        $t->set_header('砍价时间', '', '', 'record_time');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid()
    {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        $t = $this->genDataGrid();

        $list = BargainLog::queryList($page, $this->pageSize, $args);

        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList()
    {
        $this->smallHeader = "砍价详情";
        $this->renderPartial('list');
    }

}