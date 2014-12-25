<?php

/**
 * Operator class file.
 *
 * @author sudk
 * @copyright Copyright &copy; 2003-2014 TrunkBow Co., Inc
 */
class BargainPrice extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'bargain_price';
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
            $condition.=' AND bargain_price.uid=:uid';
            $params['uid'] = $args['uid'];
        }
        if ($args['name'] != '')
        {
            $condition.=' AND goods.name like :name';
            $params['name'] = "%".$args['name']."%";
        }

        //$total_num = BargainPrice::model()->count($condition, $params); //总记录数
        $total_num = Yii::app()->db->createCommand()
            ->select("count(1)")
            ->from("bargain_price")
            ->leftJoin("goods","bargain_price.goods_id=goods.id")
            ->where($condition, $params)
            ->queryScalar();

        if ($_REQUEST['q_order'] == '')
        {
            $order = 'bargain_price.record_time DESC';
        }
        else
        {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1).' DESC';
            else
                $order = $_REQUEST['q_order'].' ASC';
        }

        $rows = Yii::app()->db->createCommand()
    		->select("bargain_price.*,goods.price p_price,goods.name")
    		->from("bargain_price")
            ->leftJoin("goods","bargain_price.goods_id=goods.id")
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

    public function reduce($goods_id,$uid){
        $goods=Goods::model()->findByPk($goods_id);
        if($goods){
            try{
                $model=BargainPrice::model()->find("goods_id='{$goods_id}' and uid='{$uid}'");
                $model->price=$model->price-$goods->reduce;
                $model->record_time=date("Y-m-d H:i:s");
                return $model->save();
            }catch (Exception $e){
                return false;
            }
        }else{
            return false;
        }
    }

    public function firstAdd($goods_id,$uid){
        $goods=Goods::model()->findByPk($goods_id);
        if($goods){
            try{
                $model=BargainPrice::model()->find("goods_id='{$goods_id}' and uid='{$uid}'");
                if($model){
                    return true;
                }
                $model=new BargainPrice();
                $model->uid=$uid;
                $model->goods_id=$goods_id;
                $model->price=$goods->price;
                $model->record_time=date("Y-m-d H:i:s");
                return $model->save();
            }catch (Exception $e){
                return false;
            }
        }else{
            return false;
        }
    }

    public function getPrice($goods_id,$uid){
        return Yii::app()->db->createCommand()
            ->select("bargain_price.price price_now,goods.price price_pass")
            ->from("bargain_price")
            ->leftJoin("goods","bargain_price.goods_id=goods.id")
            ->where("goods_id='{$goods_id}' and uid='{$uid}'")
            ->queryRow();
    }

}