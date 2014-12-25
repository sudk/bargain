<?php

/**
 * 操作员管理
 *
 * @author sudk
 */
class InstituteController extends AuthBaseController {

    public $defaultAction = 'list';
    public $contentHeader = "机构管理";
    public $bigMenu = "系统管理";

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=sys/institute/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('机构代码', '', '','code');
        $t->set_header('机构名称', '', '','name');
        $t->set_header('联系人', '', '');
        $t->set_header('电话', '', '');
        $t->set_header('账号有效期', '', '');
        $t->set_header('操作员', '', '');
        $t->set_header('操作', '', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        $t = $this->genDataGrid();

        $list = Institute::queryList($page, $this->pageSize, $args);
        
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
    	$this -> smallHeader = "机构列表";
        $this->render('list');
    }

     public function actionNew() {
         $this -> smallHeader = "机构添加";
         $model = new Institute('create');
         if ($_POST['Institute']) {
             $model->attributes = $_POST['Institute'];
             $model->operator=Yii::app()->user->id;
             $model->open_time=date("Y-m-d H:i:s");

             if ($model->save()) {
                 $msg['msg']="添加成功";
                 $msg['status']=1;
                 $model = new Institute('create');
             }else{
                 $msg['msg']="添加失败";
                 $msg['status']=-1;
             }
         }
         $this->render("new", array('model' => $model,'msg' => $msg));
     }

     public function actionEdit() {
         $id = $_GET['id'];
         $model = Institute::model()->findByPk($id);
         if ($_POST['Institute']) {
             $model->scenario = 'modify';
             $model->attributes = $_POST['Institute'];
             if ($model->save()) {
                 $msg['msg']="修改成功";
                 $msg['status']=1;
             }else{
                 $msg['msg']="修改失败";
                 $msg['status']=-1;
             }
         }
         $this->renderPartial("edit", array('model' => $model, 'msg' => $msg));
     }

     public function actionSearch(){
         $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
         $_GET['page'] = $_GET['page'] + 1;
         $args = $_GET['q']; //查询条件
         $list = Institute::queryList($page,10, $args);
         $this->renderPartial('search_list', array('rows' => $list['rows']));
     }

}