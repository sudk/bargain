<?php
/**
 * --请填写模块名称--
 * @author #sudk#
 * @copyright Copyright &copy; 2003-2014 TrunkBow Co., Inc
 */
class Auth extends CFormModel {

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return 'base_operator_auth';
    }

    public function rules(){
        return array(
             //安全性
            array('login_name,auth_id,auth_type', 'safe', 'on' => 'create'),
            array('login_name,auth_id,auth_type', 'safe', 'on' => 'modify'),
         );
    }

    //操作权限设置
    public static function GetAuthDictionary($operator_id){
        $rows = Yii::app()->db->createCommand()
            ->select("auth_id")
            ->from("base_operator_auth")
            ->where("login_name='{$operator_id}'")
            ->queryAll();
        $ar=array();
        if($rows){
            foreach($rows as $row){
                $ar[$row['auth_id']]=$operator_id;
            }
        }
        return $ar;
    }


    //操作权限设置
    public static function GetAuth($operator_id){
        $rows = Yii::app()->db->createCommand()
            ->select("auth_id")
            ->from("base_operator_auth")
            ->where("login_name='{$operator_id}'")
            ->queryAll();
        $ar=array();
        if($rows){
            foreach($rows as $row){
               $ar[]=$row['auth_id'];
            }
        }else{
            $ar[]="imanager";
        }
        return $ar;
    }

    //数据权限的设置
    public static function _GetData($auths){
        $data=false;
        foreach($auths as $auth){
            if($auth=='smanager'){  //超级管理员
                $data['auth']='smanager';
                break;
            }
            if($auth=='riskc_m'&& $data['auth']!='smanager'){
                $data['auth']='riskc_m';     //风险管理员
            }
            if($auth=='custom_m'&& $data['auth']!='smanager'&& $data['auth']!='riskc_m'){
                $data['auth']='custom_m';    //客户经理
                $data['managerid']=Yii::app()->user->id;
            }
            if($auth=='maintain_nor'&& $data['auth']!='smanager'&& $data['auth']!='riskc_m'){
                $data['auth']='maintain_nor';    //终端维护人员
                $data['maintainid']=Yii::app()->user->id;
            }
        }
        if(!$data){
            $data['auth']='custom_m';//默认数据权限
            $data['managerid']=Yii::app()->user->id;
        }
        return $data;
    }
    //数据权限的设置
    public static function GetData($auths){
        $role=require(dirname(__FILE__).'/../data/role.php');
        $data=false;
        $data_range=100;
        foreach($auths as $auth){
            if(isset($role[$auth])){
                $role_data=$role[$auth]['data'];
            }else{
                continue;
            }
            if($role_data<$data_range){
                $data['auth']=array();
                $data['auth'][$auth]=true;
            }elseif($role_data==$data_range){
                $data['auth'][$auth]=true;
            }
            $data_range=$role_data;
        }
        if($data==false){
            $data['auth']['custom_m']=true;//默认数据权限
        }
        return $data;
    }
    public static function GetByCd($condition,$params=array(),$order='auth_id DESC'){
        return Yii::app()->db->createCommand()
            ->select("*")
            ->from("base_operator_auth")
            ->where($condition,$params)
            ->order($order)
            ->queryAll();
    }
}


    