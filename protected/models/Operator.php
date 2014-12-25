<?php

/**
 * Operator class file.
 *
 * @author sudk
 * @copyright Copyright &copy; 2003-2014 TrunkBow Co., Inc
 */
class Operator extends CActiveRecord
{

    public $new_password; //新密码
    public $confirm_password; //确认密码
    public $update_pwd_flag;

    //状态
    const STATUS_NORMAL = '0'; //正常
    const STATUS_DISABLE = '9'; //注销
    const STATUS_FREEZE = '1'; //冻结

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'base_operator';
    }

    public static function getStatusTitle($key=null)
    {
        $rs = array(
            self::STATUS_NORMAL => '正常',
            self::STATUS_DISABLE => '注销',
            self::STATUS_FREEZE => '冻结',
        );
        return $key === null ? $rs : $rs[$key];
    }
    
    public static function getStatusHtml($key=null)
    {
    	$rs = array(
    			self::STATUS_NORMAL => '正常',
    			self::STATUS_DISABLE => '注销',
    			self::STATUS_FREEZE => '冻结',
    	);
    	return $key === null ? $rs : $rs[$key];
    }

    public static function loadRecord($id)
    {
        $model = Operator::model()->findbyPk($id);
        return $model;
    }

    /**
     * 添加
     * @param array $args
     * @return array
     */
    public static function add($args)
    {
        if ($args['id'] == '')
        {
            $r['msg'] = '用户名不能为空';
            $r['status'] = -1;
            return $r;
        }
        //检测账号的惟一性
        $total_num = Operator::model()->count('id=:id', array('id' => $args['id']));
        if ($total_num <> 0)
        {
            $r['msg'] = '用户名已经存在';
            $r['status'] = -1;
            return $r;
        }

        if ($args['password'] == '')
        {
            $r['msg'] = '密码不能为空';
            $r['status'] = -1;
            return $r;
        }

        if ($args['password_c'] == '')
        {
            $r['msg'] = '确认密码不能为空';
            $r['status'] = -1;
            return $r;
        }

        if ($args['password'] <> $args['password_c'])
        {
            $r['msg'] = '两次输入的密码不一致';
            $r['status'] = -1;
            return $r;
        }

        $args['password'] = crypt($args['password']);

        if ((string) $args['status'] == '')
        {
            $args['status'] = Operator::STATUS_NORMAL;
        }
        
        try
        {
            $model = new Operator();
            $model->id = trim($args['id']);
            $model->password = trim($args['password']);
            $model->name = trim($args['name']);
            $model->email = trim($args['email']);
            $model->addr=trim($args['addr']);
            $model->phone = trim($args['phone']);
            $model->status = trim($args['status']);
            $model->institute = trim($args['institute']);
            $model->save();

            $r['msg'] = '添加成功';
            $r['status'] = 0;
        }
        catch (PDOException $e)
        {
            $r['msg'] = $e->getMessage();
            $r['status'] = -1;
        }
        return $r;
    }

    /**
     * 修改操作员基本信息
     * @param array $args
     * @return array
     */
    public static function edit($args)
    {

        if ($args['id'] == '')
        {
            $r['msg'] = '用户名不能为空';
            $r['status'] = -1;
            return $r;
        }
        if ($args['phone'] == '')
        {
            $r['msg'] = '绑定手机号不能为空';
            $r['status'] = -1;
            return $r;
        }
        $model = Operator::loadRecord($args['id']);
        if ($model === null)
        {
            $r['msg'] = '无效的操作员';
            $r['status'] = -1;
            return $r;
        }

        if ($args['password'] <> $args['password_c'])
        {
            $r['msg'] = '两次输入的密码不一致';
            $r['status'] = -1;
            return $r;
        }else{
            $args['password'] = crypt($args['password']);
        }
        try
        {
            if(trim($args['password'])){
                $model->password = trim($args['password']);
            }
            $model->name = trim($args['name']);
            $model->email = trim($args['email']);
            $model->addr=trim($args['addr']);
            $model->phone = trim($args['phone']);
            $model->status = trim($args['status']);
            $model->institute = trim($args['institute']);
            $model->save();
            $r['msg'] = '修改成功';
            $r['status'] = 1;
            $r['model'] = $model;
        }
        catch (PDOException $e)
        {
            $r['msg'] = $e->getMessage();
            $r['status'] = -1;
        }
        return $r;
    }

    /**
     * 修改密码
     * @param array $args
     * @return array
     */
    public static function pwd($args)
    {
        if ($args['operatorid'] == '')
        {
            $r['message'] = '登陆账号不能为空';
            $r['refresh'] = false;
            return $r;
        }
        //操作员必须存在
        $model = Operator::loadRecord($args['operatorid']);
        if ($model === null)
        {
            $r['message'] = '无效的操作员';
            $r['refresh'] = false;
            return $r;
        }

        if ($args['passwd'] == '')
        {
            $r['message'] = '旧密码不能为空';
            $r['refresh'] = false;
            return $r;
        }
        if ($args['passwd'] <> $model->passwd)
        {
            $r['message'] = '旧密码不一致';
            $r['refresh'] = false;
            return $r;
        }

        if ($args['new_password'] == '')
        {
            $r['message'] = '新密码不能为空';
            $r['refresh'] = false;
            return $r;
        }
        if ($args['confirm_password'] == '')
        {
            $r['message'] = '确认密码不能为空';
            $r['refresh'] = false;
            return $r;
        }
        if ($args['new_password'] <> $args['confirm_password'])
        {
            $r['message'] = '两次输入的密码不一致';
            $r['refresh'] = false;
            return $r;
        }
        try
        {
            $model->passwd = trim($args['new_password']);
            $model->save();

            $r['message'] = '重设密码成功';
            $r['refresh'] = true;
            $r['model'] = $model;
        }
        catch (PDOException $e)
        {
            $r['message'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryRows($args = array())
    {
        $condition = '';
        $params = array();

        if ($args['operatorid'] != '')
        {
            $condition.= ( $condition == '') ? ' operatorid=:operatorid' : ' AND operatorid=:operatorid';
            $params['operatorid'] = $args['operatorid'];
        }
        if ($args['name'] != '')
        {
            $condition.= ( $condition == '') ? ' name =:name ' : ' AND name =:name';
            $params['name'] = $args['name'];
        }
        if ($args['phone'] != '')
        {
            $condition.= ( $condition == '') ? ' phone=:phone' : ' AND phone=:phone';
            $params['phone'] = $args['phone'];
        }
        if ($args['area'] != '')
        {
            $condition.= ( $condition == '') ? ' area=:area' : ' AND area=:area';
            $params['area'] = $args['area'];
        }
        if ($args['status'] != '')
        {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        if ($args['schoolid'] != '')
        {
            $condition.= ( $condition == '') ? ' schoolid=:schoolid' : ' AND schoolid=:schoolid';
            $params['schoolid'] = $args['schoolid'];
        }
        if ($args['type'] != '')
        {
            $condition.= ( $condition == '') ? ' type=:type' : ' AND type=:type';
            $params['type'] = $args['type'];
        }

        $criteria = new CDbCriteria();
        $criteria->condition = $condition;
        $criteria->params = $params;

        $rows = Operator::model()->findAll($criteria);
        return $rows;
    }

    /**
     * 删除
     * @param  array $operatorid
     * @return array
     */
    public function delete($id=null)
    {
        $sql = 'DELETE FROM base_operator WHERE id=:id';
        try
        {

            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":id", $id, PDO::PARAM_STR);
            $command->execute();
            return true;
        }
        catch (CDbException $e)
        {
            return false;
        }
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array())
    {

        $condition = '';
        $params = array();
        if ($args['id'] != '')
        {
            $condition.= ( $condition == '') ? ' id=:id' : ' AND id=:id';
            $params['id'] = $args['id'];
        }
        if ($args['name'] != '')
        {
            $condition.= ( $condition == '') ? ' `name` =:name ' : ' AND `name` =:name';
            $params['name'] = $args['name'];
        }
       
        if ($args['status'] != '')
        {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = Operator::model()->count($condition, $params); //总记录数

        if ($_REQUEST['q_order'] == '')
        {
            $order = 'id';
        }
        else
        {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1).' DESC';
            else
                $order = $_REQUEST['q_order'].' ASC';
        }

        $rows = Yii::app()->db->createCommand()
    		->select("*")
    		->from("base_operator")
    		->where($condition, $params)
    		->order($order)
    		->limit($pageSize)
    		->offset($page * $pageSize)
    		->queryAll();

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

}