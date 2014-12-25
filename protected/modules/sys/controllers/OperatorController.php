<?php

/**
 * 操作员管理
 *
 * @author sudk
 */
class OperatorController extends AuthBaseController
{

    public $defaultAction = 'list';
    public $contentHeader = "操作员管理";
    public $bigMenu = "系统管理";

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid()
    {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=sys/operator/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('用户名', '', '', 'id');
        $t->set_header('真实姓名', '', '', 'name');
        $t->set_header('电话', '', '');
        $t->set_header('状态', '', '');
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

        $list = Operator::queryList($page, $this->pageSize, $args);

        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList()
    {
        $this->smallHeader = "操作员列表";
        $this->render('list');
    }

    public function actionNew()
    {
        $this->smallHeader = "操作员添加";
        $model = new Operator('create');
        if ($_POST['Operator']) {
            $args = $_POST['Operator'];
            $msg = Operator::add($args);
            if ($msg['status'] == -1) {
                $model->attributes = $_POST['Operator'];
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
                if($_POST['id']=='admin'){
                    $msg['msg'] = '不能删除超级管理员';
                    $msg['status'] = 1;
                    break;
                }
                $operator=new Operator();
                if($operator->delete($_POST['id'])){
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
        $id = $_GET['id'];
        $model = Operator::model()->findByPk($id);
        if ($_POST['Operator']) {
            $msg = null;
            $args = $_POST['Operator'];
            $model->scenario = 'modify';
            do {
                if ($args['password'] <> $args['password_c']) {
                    $msg['msg'] = '两次输入的密码不一致';
                    $msg['status'] = -1;
                    break;
                } else if (trim($args['password'])) {
                    $args['password'] = crypt($args['password']);
                    $model->password = trim($args['password']);
                }
                try {
                    $model->name = trim($args['name']);
                    $model->email = trim($args['email']);
                    $model->addr = trim($args['addr']);
                    $model->phone = trim($args['phone']);
                    $model->status = trim($args['status']);
                    //$model->institute = trim($args['institute']);
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
        $this->renderPartial("edit", array('model' => $model, 'msg' => $msg));
    }

    public function actionPedit()
    {
        $id = Yii::app()->user->id;
        $model = Operator::model()->findByPk($id);
        if ($_POST['Operator']) {
            $msg = null;
            $args = $_POST['Operator'];
            $model->scenario = 'modify';
            do {
                if ($args['password'] <> $args['password_c']) {
                    $msg['msg'] = '两次输入的密码不一致';
                    $msg['status'] = -1;
                    break;
                } else if (trim($args['password'])) {
                    $args['password'] = crypt($args['password']);
                    $model->password = trim($args['password']);
                }
                try {
                    $model->name = trim($args['name']);
                    $model->email = trim($args['email']);
                    $model->addr = trim($args['addr']);
                    $model->phone = trim($args['phone']);
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
        $this->renderPartial("pedit", array('model' => $model, 'msg' => $msg));
    }

}