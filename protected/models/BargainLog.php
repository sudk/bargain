<?php

/**
 * Operator class file.
 *
 * @author sudk
 * @copyright Copyright &copy; 2003-2014 TrunkBow Co., Inc
 */
class BargainLog extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'bargain_log';
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
        if ($args['uid'] != '')
        {
            $condition.=' AND uid=:uid';
            $params['uid'] = $args['uid'];
        }
        if ($args['goods_id'] != '')
        {
            $condition.=' AND `goods_id` =:goods_id';
            $params['goods_id'] = $args['goods_id'];
        }
        if ($args['bargain_id'] != '')
        {
            $condition.=' AND `bargain_id` =:bargain_id';
            $params['bargain_id'] = $args['bargain_id'];
        }

        $total_num = BargainLog::model()->count($condition, $params); //总记录数

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
    		->from("bargain_log")
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
        $rs['order'] = $_REQUEST['q_order'];
        $rs['url'] = "./?r=mobile/log/grid";
        $rs['rows'] = $rows;

        return $rs;
    }

    public function log($goods_id,$uid,$bargain_id){
        $goods=Goods::model()->findByPk($goods_id);
        $model=new BargainLog();
        $model->goods_id=$goods_id;
        $model->uid=$uid;
        $model->bargain_id=$bargain_id;
        $model->reduce_price=$goods->reduce;
        $model->record_time=date("Y-m-d H:i:s");
        try{
            $model->save();
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    public function hasLog($goods_id,$uid,$bargain_id){
        $condition="goods_id='{$goods_id}' and uid='{$uid}' and bargain_id='{$bargain_id}'";
        return Yii::app()->db->createCommand()
            ->select("count(1)")
            ->from("bargain_log")
            ->where($condition)
            ->queryScalar();
    }

}