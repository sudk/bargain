<?php
/*
 * 模块编号: M0403
 */
class AuthController extends BaseController
{
    /**
     *
     */
    public function actionList()
    {
        $roles = require(dirname(__FILE__) . '/../data/role.php');
        $auths = Auth::GetAuthDictionary($_GET['operator_id']);
        $this->renderPartial('role', array('roles'=>$roles,'auths'=>$auths));
    }

    public function actionDel(){
        $sql = 'delete from pm_operator_auth where login_name=:login_name and auth_id=:auth_id ';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":login_name", $_POST['login_name'], PDO::PARAM_STR);
        $command->bindParam(":auth_id", $_POST['auth_id'], PDO::PARAM_STR);
        if($command->execute()){
            echo 1;
        }else{
            echo 0;
        }
;    }

    public function actionAdd(){
        $sql = 'insert into pm_operator_auth (login_name,auth_id) values ( :login_name,:auth_id) ';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":login_name", $_POST['login_name'], PDO::PARAM_STR);
        $command->bindParam(":auth_id", $_POST['auth_id'], PDO::PARAM_STR);
        if($command->execute()){
            echo 1;
        }else{
            echo 0;
        }
    }

}