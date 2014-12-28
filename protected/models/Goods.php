<?php

/**
 * Operator class file.
 *
 * @author sudk
 * @copyright Copyright &copy; 2003-2014 TrunkBow Co., Inc
 */
class Goods extends CActiveRecord
{

    const STATUS_NORMAL=1;//正常
    const STATUS_UNLINE=0;//下线

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function GetStatus($key=null)
    {
        $rs = array(
            self::STATUS_NORMAL => '正常',
            self::STATUS_UNLINE => '下线',
        );
        return $key === null ? $rs : $rs[$key];
    }

    public function tableName()
    {
        return 'goods';
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

        $condition = '1=1 ';
        $params = array();
        if ($args['id'] != '')
        {
            $condition.=' AND id=:id';
            $params['id'] = $args['id'];
        }
        if ($args['name'] != '')
        {
            $condition.=' AND `name` like :name';
            $params['name'] = "%".$args['name']."%";
        }
       
        if ($args['status'] != '')
        {
            $condition.=' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = Goods::model()->count($condition, $params); //总记录数

        if ($_REQUEST['q_order'] == '')
        {
            $order = 'record_time DESC';
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
    		->from("goods")
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

    public static function GetTheActiveOne($id=""){
        if($id){
            $condition="id=:id";
            $params=array(
                'id'=>$id
            );
        }else{
            $condition="status=:status and start_time <= :start_time and end_time >= :end_time";
            $start_time=date("Y-m-d");
            $end_time=date("Y-m-d")." 23:59:59";
            $params=array(
                'status'=>Goods::STATUS_NORMAL,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
            );
        }
        $rows = Yii::app()->db->createCommand()
            ->select("*")
            ->from("goods")
            ->where($condition, $params)
            ->order("record_time DESC")
            ->queryRow();
        return $rows;
    }

    public static function GetActive(){
        $condition="status=:status and start_time <= :start_time and end_time >= :end_time";
        $start_time=date("Y-m-d");
        $end_time=date("Y-m-d")." 23:59:59";
        $params=array(
            'status'=>Goods::STATUS_NORMAL,
            'start_time'=>$start_time,
            'end_time'=>$end_time,
        );
        $rows = Yii::app()->db->createCommand()
            ->select("*")
            ->from("goods")
            ->where($condition, $params)
            ->order("record_time DESC")
            ->queryAll();
        return $rows;
    }

}