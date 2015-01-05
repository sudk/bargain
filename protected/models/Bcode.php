<?php

/**
 * Operator class file.
 *
 * @author sudk
 * @copyright Copyright &copy; 2003-2014 TrunkBow Co., Inc
 */
class Bcode extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'bcode';
    }

    public static function HasCode($code){
        $condition="bcode='{$code}'";
        return Yii::app()->db->createCommand()
            ->select("count(1)")
            ->from("bcode")
            ->where($condition)
            ->queryScalar();
    }

}