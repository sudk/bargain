<?php

/**
 * 操作员管理
 *
 * @author sudk
 */
class PriceController extends AuthBaseController
{

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
        $t->url = 'index.php?r=bargain/price/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('手机号', '', '', 'bargain_price.uid');
        $t->set_header('商品名称', '', '', 'goods.name');
        $t->set_header('原价', '', '', 'goods.price');
        $t->set_header('现价', '', '', 'bargain_price.price');
        $t->set_header('最后更新', '', 'bargain_price.record_time');
        $t->set_header('操作', '', '');
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

        $list = BargainPrice::queryList($page, $this->pageSize, $args);

        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList()
    {
        $this->smallHeader = "砍价列表";
        $this->render('list');
    }
}