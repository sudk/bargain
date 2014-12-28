<?php

/**
 * 操作员管理
 *
 * @author sudk
 */
class GoodsController extends AuthBaseController
{

    public $defaultAction = 'list';
    public $contentHeader = "商品列表";
    public $bigMenu = "商品管理";

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid()
    {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=bargain/goods/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('商品名称', '', '', 'name');
        $t->set_header('价格', '', '', 'price');
        $t->set_header('每次递减', '', '');
        $t->set_header('有效期', '', '');
        $t->set_header('状态', '', 'status');
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

        $list = Goods::queryList($page, $this->pageSize, $args);

        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList()
    {
        $this->smallHeader = "商品列表";
        $this->render('list');
    }

    public function actionNew()
    {
        $this->smallHeader = "商品添加";
        $model = new Goods('create');
        if ($_POST['Goods']) {
            $msg['msg'] = '操作成功';
            $msg['status'] = 0;
            $args = $_POST['Goods'];
            $model->id = "g".uniqid();
            $model->name = trim($args['name']);
            $model->desc = $args['desc'];
            $model->price = trim($args['price'])*100;
            $model->reduce = trim($args['reduce'])*100;
            $model->img_url = $args['img_url'];
            $model->start_time = trim($args['start_time']);
            $model->end_time = trim($args['end_time']);
            $model->record_time = date("Y-m-d H:i:s");
            $model->status = trim($args['status']);
            $rs=$model->save();
            if (!$rs) {
                $msg['msg'] = '添加失败';
                $msg['status'] = 1;
                $model->attributes = $_POST['Goods'];
            }else{
                $model = new Goods('create');
            }
        }
        $this->render("new", array('model' => $model, 'msg' => $msg));
    }

    public function actionDel()
    {
        if(trim($_POST['id'])){
            $msg['msg'] = '操作成功';
            $msg['status'] = 0;
            do{
                $rs=Goods::model()->deleteByPk($_POST['id']);
                if($rs){
                    $msg['msg'] = '删除成功';
                    $msg['status'] = 0;
                    break;
                }else{
                    $msg['msg'] = '删除失败';
                    $msg['status'] = 2;
                    break;
                }

            }while(false);
            echo json_encode($msg);
        }
    }

    public function actionEdit()
    {
        $this->smallHeader = "商品编辑";
        $id = $_GET['id'];
        $model = Goods::model()->findByPk($id);
        if ($_POST['Goods']) {
            $msg = null;
            $args = $_POST['Goods'];
            $model->scenario = 'modify';
            do {
                try {
                    $model->name = trim($args['name']);
                    $model->desc = $args['desc'];
                    $model->price = trim($args['price'])*100;
                    $model->reduce = trim($args['reduce'])*100;
                    $model->img_url = $args['img_url'];
                    $model->start_time = trim($args['start_time']);
                    $model->end_time = trim($args['end_time']);
                    $model->status = trim($args['status']);
                    $model->save();
                    $msg['msg'] = '修改成功';
                    $msg['status'] = 0;
                    break;
                } catch (PDOException $e) {
                    $msg['msg'] = $e->getMessage();
                    $msg['status'] = -1;
                    break;
                }
            } while (false);
        }
        $model->price = $model->price/100;
        $model->reduce = $model->reduce/100;
        $this->render("edit", array('model' => $model, 'msg' => $msg));
    }

}